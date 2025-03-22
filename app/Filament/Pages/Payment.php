<?php

namespace App\Filament\Pages;

use App\Models\Transaction;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

class Payment extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.payment';

    public $transaction;
    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }


    public function mount(string $uuid): void
    {
        $this->transaction = Transaction::findorFail($uuid);
        $this->form->fill([
            'payment_method' => $this->transaction->payment_method ?? null,
            'payment_proof' => $this->transaction->payment_proof ?? null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('payment_method')
                            ->options([
                                'BANK_TRANSFER' => 'Bank Transfer',
                                'VIRTUAL_ACCOUNT' => 'Virtual Account',
                                'E_WALLET' => 'E-Wallet',
                            ])
                            ->required()
                            ->default($this->data['payment_method'] ?? null),
                        FileUpload::make('payment_proof')
                            ->image()
                            ->required()
                            ->disk(env('FILAMENT_FILESYSTEM_DISK'))
                            ->directory('payment-proofs')
                            ->previewable(true)
                            ->visibility('private')
                            ->columnSpanFull(),
                    ])
            ])->statePath('data');
    }

    public function edit()
    {
        $validatedData = $this->form->getState();

        if (isset($validatedData['payment_proof']) && $validatedData['payment_proof'] !== $this->transaction->payment_proof) {
            if ($this->transaction->payment_proof) {
                Storage::delete($this->transaction->payment_proof);
            }
        }

        $this->transaction->update([
            'payment_method' => $validatedData['payment_method'],
            'payment_proof' => $validatedData['payment_proof'],
        ]);

        Notification::make()
            ->title('Payment Successfully')
            ->body('Payment Proof has been uploaded successfully')
            ->success()
            ->send();

        return redirect('/admin');
    }
}

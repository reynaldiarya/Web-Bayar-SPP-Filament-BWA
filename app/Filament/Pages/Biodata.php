<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Biodata extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.biodata';

    public $user;
    public ?array $data = [];

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->form->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'image' => $this->user->image,
            'scanned_diploma' => $this->user->scanned_diploma,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->nullable()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->label('Phone Number')
                            ->placeholder('Enter Your Phone Number')
                            ->maxLength(20),
                        FileUpload::make('image')
                            ->label('Profile Picture')
                            ->columnSpanFull()
                            ->required()
                            ->image()
                            ->disk(env('FILAMENT_FILESYSTEM_DISK'))
                            ->directory('images')
                            ->previewable(true)
                            ->visibility('private')
                            ->placeholder('Upload Your Profile Picture'),
                        FileUpload::make('scanned_diploma')
                            ->columnSpanFull()
                            ->required()
                            ->image()
                            ->disk(env('FILAMENT_FILESYSTEM_DISK'))
                            ->directory('scanned-diplomas')
                            ->previewable(true)
                            ->visibility('private')
                            ->placeholder('Upload Your Diploma Picture'),
                    ])
            ])->statePath('data');
    }

    public function edit(): void
    {
        $validatedData = $this->form->getState();
        $this->user->name = $validatedData['name'];
        $this->user->email = $validatedData['email'];
        $this->user->phone = $validatedData['phone'];

        if (!empty($validatedData['password'])) {
            $this->user->password = Hash::make($validatedData['password']);
        }

        if (isset($validatedData['image']) && $validatedData['image'] !== $this->user->image) {
            if ($this->user->image && Storage::exists($this->user->image)) {
                Storage::delete($this->user->image);
            }
            $this->user->image = $validatedData['image'];
        }

        if (isset($validatedData['scanned_diploma']) && $validatedData['scanned_diploma'] !== $this->user->scanned_diploma) {
            if ($this->user->scanned_diploma && Storage::exists($this->user->scanned_diploma)) {
                Storage::delete($this->user->scanned_diploma);
            }
            $this->user->scanned_diploma = $validatedData['scanned_diploma'];
        }

        $this->user->save();

        Notification::make()
            ->title('Biodata Updated')
            ->success()
            ->body('Your biodata has been updated successfully.')
            ->send();
    }
}

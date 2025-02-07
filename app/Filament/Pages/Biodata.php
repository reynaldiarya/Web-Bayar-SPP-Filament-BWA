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

    public function mount():void
    {
        $this->user = Auth::user();
        $this->form->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'image' => $this->user->image,
            'scanijazah' => $this->user->scanijazah,
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
                      ->maxLength(255),
                FileUpload::make('image')
                      ->image()
                      ->columnSpanFull(),
                FileUpload::make('scanijazah')
                      ->image()
                      ->columnSpanFull(),
              ])
            ])->statePath('data');
    }

    public function edit(): void
    {
        $validatedData = $this->form->getState();
        $this->user->name = $validatedData['name'];
        $this->user->email = $validatedData['email'];
        $this->user->phone = $validatedData['phone'];

        if(!empty($validatedData['password'])){
            $this->user->password = Hash::make($validatedData['password']);
        }

        if(isset($validatedData['image'])){
            if($this->user->image){
                Storage::delete($this->user->image);
            }
            $this->user->image = $validatedData['image'];
        }

        if(isset($validatedData['scanijazah'])){
            if($this->user->scanijazah){
                Storage::delete($this->user->scanijazah);
            }
            $this->user->scanijazah = $validatedData['scanijazah'];
        }

        $this->user->save();
        
        Notification::make()
        ->title('Biodata Updated')
        ->success()
        ->body('Your biodata has been updated successfully.')
        ->send();
    }
}

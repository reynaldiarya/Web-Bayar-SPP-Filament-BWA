<?php

namespace App\Filament\Auth;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as AuthRegister;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Register extends AuthRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->label('Phone Number')
                            ->placeholder('Enter Your Phone Number'),
                        FileUpload::make('image')
                            ->label('Profile Picture')
                            ->columnSpanFull()
                            ->required()
                            ->image()
                            ->directory('images')
                            ->placeholder('Upload Your Profile Picture'),
                        FileUpload::make('scanned_diploma')
                            ->columnSpanFull()
                            ->required()
                            ->image()
                            ->directory('scanned-diplomas')
                            ->placeholder('Upload Your Diploma Picture'),
                    ])->statePath('data'),
            )
        ];
    }

    protected function submit(): void
    {
        $data = $this->form->getState();
        $user = User::Create([
            'name' => $this->state['data']['name'],
            'email' => $this->state['data']['email'],
            'password' => Hash::make($data['password']),
            'phone' => $this->state['data']['phone'],
            'image' => $this->state['data']['image'],
            'scanned_diploma' => $this->state['data']['scanned_diploma'],
        ]);

        Auth::login($user);
    }
}

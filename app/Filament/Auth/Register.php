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
                            ->placeholder('Upload Your Profile Picture'),
                        FileUpload::make('scanijazah')
                            ->label('Scan Ijazah')
                            ->columnSpanFull()
                            ->required()
                            ->image()
                            ->placeholder('Upload Your Ijazah Picture'),

                    ])

                    ->statePath('data'),
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
            'scanijazah' => $this->state['data']['scanijazah'],
        ]);

        Auth::login($user);
    }
}

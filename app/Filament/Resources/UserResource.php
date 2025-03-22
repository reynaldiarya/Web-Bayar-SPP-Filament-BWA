<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->disk(env('FILAMENT_FILESYSTEM_DISK'))
                    ->directory('images')
                    ->previewable(true)
                    ->visibility('private')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('scanned_diploma')
                    ->image()
                    ->disk(env('FILAMENT_FILESYSTEM_DISK'))
                    ->directory('scanned-diplomas')
                    ->previewable(true)
                    ->visibility('private')
                    ->columnSpanFull(),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->getStateUsing(
                        fn($record) => $record->image
                            ? (
                                Storage::exists($record->image)
                                ? route('file.get', $record->image)
                                : null
                            )
                            : null
                    )
                    ->width(45)
                    ->height(45),
                Tables\Columns\ImageColumn::make('scanned_diploma')
                    ->getStateUsing(
                        fn($record) => $record->scanned_diploma
                            ? (
                                Storage::exists($record->scanned_diploma)
                                ? route('file.get', $record->scanned_diploma)
                                : null
                            )
                            : null
                    )
                    ->width(45)
                    ->height(45),
                Tables\Columns\TextColumn::make('roles.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function getNavigationSort(): int
    {
        return 1; // Urutan pertama
    }
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-user';  // Ikon outline
    }
    public static function getTitle(): string
    {
        return 'User'; // Ganti dengan title yang Anda inginkan
    }
}

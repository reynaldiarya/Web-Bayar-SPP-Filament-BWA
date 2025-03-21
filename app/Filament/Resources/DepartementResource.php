<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartementResource\Pages;
use App\Filament\Resources\DepartementResource\RelationManagers;
use App\Models\Departement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartementResource extends Resource
{
    protected static ?string $model = Departement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('semester')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->prefix('Rp'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->formatStateUsing(function ($state) {
                        $formatted = number_format($state, 0, '.', ',');
                        return "Rp $formatted";
                    })
                    ->sortable(),
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
            'index' => Pages\ListDepartements::route('/'),
            'create' => Pages\CreateDepartement::route('/create'),
            'edit' => Pages\EditDepartement::route('/{record}/edit'),
        ];
    }


    public static function getNavigationSort(): int
    {
        return 2; // Urutan pertama
    }
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-building-office';  // Ikon outline
    }
    public static function getTitle(): string
    {
        return 'Departement'; // Ganti dengan title yang Anda inginkan
    }
}

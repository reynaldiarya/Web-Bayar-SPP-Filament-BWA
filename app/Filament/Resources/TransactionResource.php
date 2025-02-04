<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Departement;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->default('TRX-' . mt_rand(1000, 9999))
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name'),
                Forms\Components\TextInput::make('payment_status')
                    ->readOnly()
                    ->default('PENDING')
                    ->maxLength(255),
                Forms\Components\FieldSet::make('Departement')
                    ->schema([
                        Forms\Components\Select::make('departement_id')
                            ->required()
                            ->label('Departement & Semester')
                            ->options(Departement::query()->get()->mapWithKeys(function ($departement) {
                                return [
                                    $departement->id => $departement->name . ' - Semester ' . $departement->semester
                                ];
                            })->toArray())
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($departement = Departement::find($state)) {
                                    $set('departement_cost', $departement->cost);
                                } else {
                                    $set('departement_cost', null);
                                }
                            }),

                        Forms\Components\TextInput::make('departement_cost')
                            ->label('Rp')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'PENDING' => 'warning',
                        'SUCCESS' => 'green',
                        'FAILED' => 'red',
                        default => 'secondary',
                    }),
                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->width(450)
                    ->height(225),
                Tables\Columns\TextColumn::make('departement.name')
                    ->label('Departement')
                    ->searchable(),
                Tables\Columns\TextColumn::make('departement.semester')
                    ->label('Semester')
                    ->searchable(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
    public static function getNavigationSort(): int
    {
        return 3; // Urutan pertama
    }
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-list';  // Ikon outline
    }
    public static function getTitle(): string
    {
        return 'Transaction'; // Ganti dengan title yang Anda inginkan
    }
}

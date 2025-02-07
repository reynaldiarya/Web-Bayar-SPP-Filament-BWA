<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AdminTransaction extends BaseWidget
{
    use HasWidgetShield;
    protected static ?string $heading = "Transaction History Admin";
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->orderBy('created_at', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Transaction Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('departement.name')
                    ->label('Departement'),
                Tables\Columns\TextColumn::make('departement.semester')
                    ->label('Semester'),
                Tables\Columns\TextColumn::make('payment_method')
                ->label('Payment Method'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'PENDING' => 'warning',
                        'SUCCESS' => 'green',
                        'FAILED' => 'red',
                        default => 'secondary',
                    }),
                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Payment Proof')
                    ->width(450)
                    ->height(225),
                Tables\Columns\TextColumn::make('departement.cost')
                    ->label('Cost')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}

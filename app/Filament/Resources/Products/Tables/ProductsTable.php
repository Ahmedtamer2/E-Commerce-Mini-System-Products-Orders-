<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-product.png'))
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('السعر')
                    ->money('EGP')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('المخزون')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'available' => 'success',
                        'out_of_stock' => 'danger',
                        'discontinued' => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'available' => 'متوفر',
                        'out_of_stock' => 'نفذ من المخزون',
                        'discontinued' => 'متوقف',
                        default => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'available' => 'متوفر',
                        'out_of_stock' => 'نفذ من المخزون',
                        'discontinued' => 'متوقف',
                    ])
                    ->label('الحالة'),
            ])
            ->actions([
                ViewAction::make()
                    ->label('عرض'),
                    
                EditAction::make()
                    ->label('تعديل'),
                    
                DeleteAction::make()
                    ->label('حذف'),
            ])
            ->bulkActions([
                
            ]);
    }
}
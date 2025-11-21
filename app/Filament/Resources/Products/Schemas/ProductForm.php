<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Product Details')
                    ->tabs([
                        Tab::make('Basic Information')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                \Filament\Forms\Components\Textarea::make('description')
                                    ->columnSpanFull()
                                    ->maxLength(65535),

                                \Filament\Forms\Components\TextInput::make('sku')
                                    ->label('SKU')
                                    ->unique('products', 'sku', ignoreRecord: true)
                                    ->required()
                                    ->maxLength(50),
                            ]),

                        Tab::make('Pricing & Inventory')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0),

                                \Filament\Forms\Components\TextInput::make('stock_quantity')
                                    ->label('Stock Quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0),
                            ]),

                        Tab::make('Media')
                            ->schema([
                                \Filament\Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->directory('products')
                                    ->columnSpanFull()
                                    ->imageEditor(),

                                \Filament\Forms\Components\FileUpload::make('gallery')
                                    ->multiple()
                                    ->image()
                                    ->directory('products/gallery')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Status')
                            ->schema([
                                \Filament\Forms\Components\Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger'),

                                \Filament\Forms\Components\DatePicker::make('publish_at')
                                    ->label('Publish Date')
                                    ->default(now()),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}

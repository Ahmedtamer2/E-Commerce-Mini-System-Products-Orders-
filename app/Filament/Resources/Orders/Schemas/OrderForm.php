<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Order Information')
                    ->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('status')
                            ->options([
                                'pending' => 'قيد الانتظار',
                                'processing' => 'قيد المعالجة',
                                'completed' => 'مكتمل',
                                'cancelled' => 'ملغي',
                            ])
                            ->default('pending')
                            ->required(),

                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        DatePicker::make('order_date')
                            ->label('Order Date')
                            ->default(now())
                            ->required(),
                    ])->columns(2),

                Section::make('Address Information')
                    ->schema([
                        Textarea::make('shipping_address')
                            ->label('Shipping Address')
                            ->required(),

                        Textarea::make('billing_address')
                            ->label('Billing Address')
                            ->required(),
                    ])->columns(2),

                Section::make('Payment Information')
                    ->schema([
                        Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash on Delivery',
                                'card' => 'Credit Card',
                                'bank_transfer' => 'Bank Transfer',
                            ])
                            ->required(),

                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->required(),

                        Toggle::make('is_paid')
                            ->label('Payment Received')
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x')
                            ->reactive()
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set('payment_status', $state ? 'paid' : 'pending')
                            ),
                    ])->columns(3),

                Section::make('Order Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),

                                TextInput::make('price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->createItemButtonLabel('Add Order Item')
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Order Notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

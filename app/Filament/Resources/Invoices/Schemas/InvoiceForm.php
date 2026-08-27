<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\ClientService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Client
                |--------------------------------------------------------------------------
                */

                Select::make('user_id')
                    ->label('Client')
                    ->relationship(
                        name: 'client',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('role', 'client')
                            ->where('status', 'active')
                            ->orderBy('name')
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => "{$record->name} — {$record->company_name}"
                    )
                    ->searchable([
                        'name',
                        'email',
                        'phone',
                        'company_name',
                    ])
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('client_service_id', null);
                    })
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Client Service
                |--------------------------------------------------------------------------
                */

                Select::make('client_service_id')
                    ->label('Service')
                    ->options(function (Get $get) {

                        $userId = $get('user_id');

                        if (! $userId) {
                            return [];
                        }

                        return ClientService::query()
                            ->where('user_id', $userId)
                            ->where('status', 'active')
                            ->get()
                            ->mapWithKeys(function ($service) {

                                return [
                                    $service->id => sprintf(
                                        '%s — ৳%s',
                                        match ($service->service_type) {
                                            'seo' => 'SEO',
                                            'website' => 'Website Development',
                                            'digital_marketing' => 'Digital Marketing',
                                            default => ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $service->service_type
                                                )
                                            ),
                                        },
                                        number_format(
                                            $service->total_amount,
                                            2
                                        )
                                    ),
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Invoice Number
                |--------------------------------------------------------------------------
                */

                TextInput::make('invoice_number')
                    ->label('Invoice Number')
                    ->default(
                        fn () => 'INV-' . now()->format('YmdHis') . '-' . random_int(100, 999)
                    )
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Dates
                |--------------------------------------------------------------------------
                */

                DatePicker::make('invoice_date')
                    ->label('Invoice Date')
                    ->default(now())
                    ->required(),

                DatePicker::make('due_date')
                    ->label('Due Date')
                    ->afterOrEqual('invoice_date')
                    ->required(),

                                    /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'unpaid' => 'Unpaid',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('draft')
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Invoice Items
                |--------------------------------------------------------------------------
                */

                Repeater::make('items')
                    ->label('Invoice Items')
                    ->relationship('items')
                    ->schema([

                        TextInput::make('item_name')
                            ->label('Item')
                            ->required(),

                        TextInput::make('description')
                            ->label('Description'),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->minValue(0.01)
                            ->required()
                            ->live()
                            ->afterStateUpdated(
                                function (
                                    Get $get,
                                    Set $set
                                ) {
                                    self::calculateItemAmount(
                                        $get,
                                        $set
                                    );
                                }
                            ),

                        TextInput::make('unit_price')
                            ->label('Unit Price')
                            ->numeric()
                            ->prefix('৳')
                            ->default(0)
                            ->minValue(0)
                            ->required()
                            ->live()
                            ->afterStateUpdated(
                                function (
                                    Get $get,
                                    Set $set
                                ) {
                                    self::calculateItemAmount(
                                        $get,
                                        $set
                                    );
                                }
                            ),

                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->prefix('৳')
                            ->disabled()
                            ->dehydrated()
                            ->default(0),

                    ])
                    ->columns(5)
                    ->defaultItems(1)
                    ->addActionLabel('Add Item')
                    ->live()
                    ->afterStateUpdated(
                        function (
                            Get $get,
                            Set $set
                        ) {
                            self::calculateSubtotal(
                                $get,
                                $set
                            );
                        }
                    )
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Subtotal
                |--------------------------------------------------------------------------
                */

                TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->prefix('৳')
                    ->default(0)
                    ->disabled()
                    ->dehydrated(),

                /*
                |--------------------------------------------------------------------------
                | Discount
                |--------------------------------------------------------------------------
                */

                Select::make('discount_type')
                    ->label('Discount Type')
                    ->options([
                        'fixed' => 'Fixed Amount',
                        'percentage' => 'Percentage (%)',
                    ])
                    ->default('fixed')
                    ->live()
                    ->required()
                    ->afterStateUpdated(function (
                        Get $get,
                        Set $set
                    ) {
                        self::calculateTotal($get, $set);
                    }),

                TextInput::make('discount_value')
                    ->label('Discount')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->suffix(function (Get $get) {
                        return $get('discount_type') === 'percentage'
                            ? '%'
                            : '৳';
                    })
                    ->live()
                    ->required()
                    ->afterStateUpdated(function (
                        Get $get,
                        Set $set
                    ) {
                        self::calculateTotal($get, $set);
                    }),

                TextInput::make('discount_amount')
                    ->label('Discount Amount')
                    ->numeric()
                    ->prefix('৳')
                    ->default(0)
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | Tax
                |--------------------------------------------------------------------------
                */

                TextInput::make('tax')
                    ->label('Tax')
                    ->numeric()
                    ->prefix('৳')
                    ->default(0)
                    ->minValue(0)
                    ->live()
                    ->afterStateUpdated(
                        function (
                            Get $get,
                            Set $set
                        ) {
                            self::calculateTotal(
                                $get,
                                $set
                            );
                        }
                    ),

                /*
                |--------------------------------------------------------------------------
                | Total
                |--------------------------------------------------------------------------
                */

                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->numeric()
                    ->prefix('৳')
                    ->default(0)
                    ->disabled()
                    ->dehydrated(),


                /*
                |--------------------------------------------------------------------------
                | Notes
                |--------------------------------------------------------------------------
                */

                Textarea::make('notes')
                    ->label('Notes')
                    ->columnSpanFull(),

            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Item Amount
    |--------------------------------------------------------------------------
    */

    protected static function calculateItemAmount(Get $get,Set $set): void {

        $quantity = (float) ($get('quantity') ?? 0);
        $unitPrice = (float) ($get('unit_price') ?? 0);

        $amount = $quantity * $unitPrice;

        $set(
            'amount',
            number_format($amount, 2, '.', '')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Subtotal
    |--------------------------------------------------------------------------
    */

    protected static function calculateSubtotal(Get $get, Set $set): void {

        $items = $get('items') ?? [];

        $subtotal = collect($items)->sum(function ($item) {

            return
                (float) ($item['quantity'] ?? 0)
                *
                (float) ($item['unit_price'] ?? 0);
        });

        $set(
            'subtotal',
            number_format($subtotal, 2, '.', '')
        );

        self::calculateTotal($get, $set);
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Total
    |--------------------------------------------------------------------------
    */

    // protected static function calculateTotal(Get $get, Set $set): void {
    //     $subtotal = (float) ($get('subtotal') ?? 0);
    //     $discountType = $get('discount_type') ?? 'fixed';
    //     $discountValue = (float) ($get('discount_value') ?? 0);
    //     $tax = (float) ($get('tax') ?? 0);
    //     if ($discountType === 'percentage') {
    //         $discountAmount = ($subtotal * $discountValue) / 100;
    //     } else {
    //         $discountAmount = $discountValue;
    //     }
    //     $discountAmount = min($discountAmount, $subtotal);
    //     $total = $subtotal - $discountAmount + $tax;
    //     $set('discount_amount', round($discountAmount, 2));
    //     $set('total_amount', round(max(0, $total), 2));
    // }

    protected static function calculateTotal(Get $get, Set $set): void
    {
        $subtotal = (float) ($get('subtotal') ?? 0);

        $discountType = $get('discount_type') ?? 'fixed';
        $discountValue = (float) ($get('discount_value') ?? 0);

        $tax = (float) ($get('tax') ?? 0);

        // Calculate discount
        if ($discountType === 'percentage') {
            $discountAmount = ($subtotal * $discountValue) / 100;
        } else {
            $discountAmount = $discountValue;
        }

        // Discount cannot exceed subtotal
        $discountAmount = min(
            max(0, $discountAmount),
            $subtotal
        );

        // Calculate total
        $total = $subtotal - $discountAmount + max(0, $tax);

        $set(
            'discount_amount',
            number_format($discountAmount, 2, '.', '')
        );

        $set(
            'total_amount',
            number_format(max(0, $total), 2, '.', '')
        );
    }



}
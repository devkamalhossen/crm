<?php

namespace App\Filament\Client\Resources\Payments;

use App\Filament\Resources\Payments\Schemas\PaymentInfolist;
use App\Models\Payment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static UnitEnum|string|null $navigationGroup = 'Client Portal';
    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.invoice_number')->label('Invoice')->sortable(),
                TextColumn::make('payment_date')->date('d M Y')->sortable(),
                TextColumn::make('amount')->money('BDT')->sortable(),
                TextColumn::make('payment_method')->label('Method')->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : '—')->badge(),
                TextColumn::make('status')->badge()->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : '—')->sortable(),
                TextColumn::make('transaction_id')->label('Transaction ID')->placeholder('—')->toggleable(),
            ])
            ->recordActions([\Filament\Actions\ViewAction::make()])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }
}

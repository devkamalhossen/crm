<?php

namespace App\Filament\Account\Resources\Commissions;

use App\Filament\Account\Resources\Commissions\Pages\ListCommissions;
use App\Filament\Account\Resources\Commissions\Tables\CommissionsTable;
use App\Filament\Resources\Commissions\Schemas\CommissionInfolist;
use App\Models\Commission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CommissionResource extends Resource
{
    protected static ?string $model = Commission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Commission Settlement';

    protected static ?string $modelLabel = 'Commission';

    protected static ?string $pluralModelLabel = 'Commission Settlement';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CommissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommissionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommissions::route('/'),
        ];
    }
}

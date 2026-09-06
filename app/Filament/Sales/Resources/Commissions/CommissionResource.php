<?php

namespace App\Filament\Sales\Resources\Commissions;

use App\Filament\Sales\Resources\Commissions\Pages\ListCommissions;
use App\Filament\Sales\Resources\Commissions\Tables\CommissionsTable;
use App\Filament\Resources\Commissions\Schemas\CommissionInfolist;
use App\Models\Commission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CommissionResource extends Resource
{
    protected static ?string $model = Commission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static ?string $navigationLabel = 'My Commissions';
    protected static ?string $modelLabel = 'Commission';
    protected static ?string $pluralModelLabel = 'My Commissions';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return CommissionsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CommissionInfolist::configure($schema);
    }

    public static function getEloquentQuery(): Builder
    {
        $salesTeamId = auth()->user()?->salesTeam?->getKey();

        return parent::getEloquentQuery()->where('sales_team_id', $salesTeamId ?? 0);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommissions::route('/'),
        ];
    }
}
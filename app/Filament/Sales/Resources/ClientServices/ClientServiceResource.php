<?php

namespace App\Filament\Sales\Resources\ClientServices;

use App\Filament\Resources\ClientServices\Schemas\ClientServiceInfolist;
use App\Filament\Sales\Resources\ClientServices\Pages\ListClientServices;
use App\Filament\Sales\Resources\ClientServices\Tables\ClientServicesTable;
use App\Models\ClientService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClientServiceResource extends Resource
{
    protected static ?string $model = ClientService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static ?string $navigationLabel = 'My Conversions';
    protected static ?string $pluralModelLabel = 'My Conversions';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClientServiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientServicesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $salesTeamId = auth()->user()?->salesTeam?->getKey();

        return parent::getEloquentQuery()
            ->whereHas('salesTeams', fn ($query) => $query->whereKey($salesTeamId ?? 0));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClientServices::route('/'),
        ];
    }
}
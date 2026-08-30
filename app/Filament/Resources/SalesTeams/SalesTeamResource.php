<?php

namespace App\Filament\Resources\SalesTeams;

use App\Filament\Resources\SalesTeams\Pages\CreateSalesTeam;
use App\Filament\Resources\SalesTeams\Pages\EditSalesTeam;
use App\Filament\Resources\SalesTeams\Pages\ListSalesTeams;
use App\Filament\Resources\SalesTeams\Pages\ViewSalesTeam;
use App\Filament\Resources\SalesTeams\Schemas\SalesTeamForm;
use App\Filament\Resources\SalesTeams\Schemas\SalesTeamInfolist;
use App\Filament\Resources\SalesTeams\Tables\SalesTeamsTable;
use App\Models\SalesTeam;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalesTeamResource extends Resource
{
    protected static ?string $model = SalesTeam::class;

    protected static UnitEnum|string|null $navigationGroup = 'Billing & Sales';
    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SalesTeamForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SalesTeamInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesTeamsTable::configure($table);
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
            'index' => ListSalesTeams::route('/'),
            'create' => CreateSalesTeam::route('/create'),
            'view' => ViewSalesTeam::route('/{record}'),
            'edit' => EditSalesTeam::route('/{record}/edit'),
        ];
    }
}

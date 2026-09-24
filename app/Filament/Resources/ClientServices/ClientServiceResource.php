<?php

namespace App\Filament\Resources\ClientServices;

use App\Filament\Resources\ClientServices\Pages\CreateClientService;
use App\Filament\Resources\ClientServices\Pages\EditClientService;
use App\Filament\Resources\ClientServices\Pages\ListClientServices;
use App\Filament\Resources\ClientServices\Pages\ViewClientService;
use App\Filament\Resources\ClientServices\Schemas\ClientServiceForm;
use App\Filament\Resources\ClientServices\Schemas\ClientServiceInfolist;
use App\Filament\Resources\ClientServices\Tables\ClientServicesTable;
use App\Models\ClientService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ClientServiceResource extends Resource
{
    protected static ?string $model = ClientService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $recordTitleAttribute = 'name';

    // start of global search area from hare 
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'client.name',
            'client.email',
            'client.phone',
            'client.company_name',
            'service_type',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Client'       => $record->client?->name,
            'Phone'        => $record->client?->phone,
            'Service Type' => $record->service_type,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['client']);
    }
    
    // end of global search area from hare 

    public static function form(Schema $schema): Schema
    {
        return ClientServiceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClientServiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientServicesTable::configure($table);
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
            'index' => ListClientServices::route('/'),
            'create' => CreateClientService::route('/create'),
            'view' => ViewClientService::route('/{record}'),
            'edit' => EditClientService::route('/{record}/edit'),
        ];
    }
}

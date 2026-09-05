<?php

namespace App\Filament\Client\Resources\ClientServices;

use App\Filament\Resources\ClientServices\Schemas\ClientServiceInfolist;
use App\Models\ClientService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ClientServiceResource extends Resource
{
    protected static ?string $model = ClientService::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static UnitEnum|string|null $navigationGroup = 'Client Portal';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'service_type';

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
        return ClientServiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service_type')->label('Service')->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : '—')->badge()->sortable(),
                TextColumn::make('payment_type')->label('Billing')->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : '—')->badge()->sortable(),
                TextColumn::make('start_date')->date('d M Y')->sortable(),
                TextColumn::make('end_date')->date('d M Y')->placeholder('—')->sortable(),
                TextColumn::make('project_status')->label('Project Status')->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : '—')->badge()->sortable(),
                TextColumn::make('status')->badge()->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : '—')->sortable(),
            ])
            ->filters([
                SelectFilter::make('service_type')->options([
                    'seo' => 'SEO',
                    'website' => 'Website Development',
                    'digital_marketing' => 'Digital Marketing',
                ]),
                SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'expired' => 'Expired',
                    'cancelled' => 'Cancelled',
                ]),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientServices::route('/'),
            'view' => Pages\ViewClientService::route('/{record}'),
        ];
    }
}

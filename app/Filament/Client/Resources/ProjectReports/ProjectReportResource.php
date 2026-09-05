<?php

namespace App\Filament\Client\Resources\ProjectReports;

use App\Filament\Resources\ProjectReports\Schemas\ProjectReportInfolist;
use App\Models\ProjectReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProjectReportResource extends Resource
{
    protected static ?string $model = ProjectReport::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;
    protected static UnitEnum|string|null $navigationGroup = 'Client Portal';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('project.clientService', fn (Builder $query) => $query->where('user_id', auth()->id()));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectReportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.clientService.service_type')->label('Service')->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : '—')->badge()->sortable(),
                TextColumn::make('report_type')->label('Report')->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : '—')->badge()->sortable(),
                TextColumn::make('report_date')->date('d M Y')->sortable(),
                TextColumn::make('due_date')->date('d M Y')->placeholder('—')->sortable(),
                TextColumn::make('status')->badge()->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : '—')->sortable(),
                TextColumn::make('report_link')->label('Report Link')->formatStateUsing(fn (?string $state): string => $state ? 'Open report' : '—')->url(fn ($record) => $record->report_link)->openUrlInNewTab(),
            ])
            ->recordActions([\Filament\Actions\ViewAction::make()])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectReports::route('/'),
            'view' => Pages\ViewProjectReport::route('/{record}'),
        ];
    }
}

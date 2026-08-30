<?php

namespace App\Filament\Resources\ProjectMeetings;

use App\Filament\Resources\ProjectMeetings\Pages\CreateProjectMeeting;
use App\Filament\Resources\ProjectMeetings\Pages\EditProjectMeeting;
use App\Filament\Resources\ProjectMeetings\Pages\ListProjectMeetings;
use App\Filament\Resources\ProjectMeetings\Pages\ViewProjectMeeting;
use App\Filament\Resources\ProjectMeetings\Schemas\ProjectMeetingForm;
use App\Filament\Resources\ProjectMeetings\Schemas\ProjectMeetingInfolist;
use App\Filament\Resources\ProjectMeetings\Tables\ProjectMeetingsTable;
use App\Models\ProjectMeeting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProjectMeetingResource extends Resource
{
    protected static ?string $model = ProjectMeeting::class;

    protected static UnitEnum|string|null $navigationGroup = 'Project Status';
    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'agenda';

    public static function form(Schema $schema): Schema
    {
        return ProjectMeetingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectMeetingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectMeetingsTable::configure($table);
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
            'index' => ListProjectMeetings::route('/'),
            'create' => CreateProjectMeeting::route('/create'),
            'view' => ViewProjectMeeting::route('/{record}'),
            'edit' => EditProjectMeeting::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\ProjectMeetings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectMeetingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('project.id')
                    ->label('Project'),
                TextEntry::make('projectReport.id')
                    ->label('Project report')
                    ->placeholder('-'),
                TextEntry::make('meeting_date')
                    ->date(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('title')
                    ->placeholder('-'),
                TextEntry::make('agenda')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('reminder_sent_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

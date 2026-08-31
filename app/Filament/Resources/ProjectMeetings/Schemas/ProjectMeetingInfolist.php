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
                TextEntry::make('project.clientService.client.name')
                    ->label('Client')
                    ->placeholder('-'),

                TextEntry::make('project.clientService.service_type')
                    ->label('Service')
                    ->formatStateUsing(
                        fn ($state) => $state
                            ? str($state)->replace('_', ' ')->title()
                            : '-'
                    )
                    ->placeholder('-'),

                TextEntry::make('project.projectManager.name')
                    ->label('Project Manager')
                    ->placeholder('-'),

                TextEntry::make('projectReport.report_type')
                    ->label('Report Type')
                    ->formatStateUsing(
                        fn ($state) => $state
                            ? str($state)->replace('_', ' ')->title()
                            : '-'
                    )
                    ->placeholder('-'),

                TextEntry::make('projectReport.report_date')
                    ->label('Report Date')
                    ->date()
                    ->placeholder('-'),

                TextEntry::make('meeting_date')
                    ->label('Meeting Date')
                    ->date(),

                TextEntry::make('status')
                    ->label('Status')
                    ->badge(),

                TextEntry::make('title')
                    ->label('Meeting Title')
                    ->placeholder('-'),

                TextEntry::make('agenda')
                    ->label('Agenda')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('notes')
                    ->label('Notes')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('reminder_at')
                    ->label('Reminder At')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('reminder_sent_at')
                    ->label('Reminder Sent At')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\SmsReminderSettings;

use App\Filament\Resources\SmsReminderSettings\Pages\CreateSmsReminderSetting;
use App\Filament\Resources\SmsReminderSettings\Pages\EditSmsReminderSetting;
use App\Filament\Resources\SmsReminderSettings\Pages\ListSmsReminderSettings;
use App\Filament\Resources\SmsReminderSettings\Pages\ViewSmsReminderSetting;
use App\Filament\Resources\SmsReminderSettings\Schemas\SmsReminderSettingForm;
use App\Filament\Resources\SmsReminderSettings\Schemas\SmsReminderSettingInfolist;
use App\Filament\Resources\SmsReminderSettings\Tables\SmsReminderSettingsTable;
use App\Models\SmsReminderSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SmsReminderSettingResource extends Resource
{
    protected static ?string $model = SmsReminderSetting::class;

    protected static UnitEnum|string|null $navigationGroup = 'Settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $recordTitleAttribute = 'message';

    public static function form(Schema $schema): Schema
    {
        return SmsReminderSettingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SmsReminderSettingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SmsReminderSettingsTable::configure($table);
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
            'index' => ListSmsReminderSettings::route('/'),
            'create' => CreateSmsReminderSetting::route('/create'),
            'view' => ViewSmsReminderSetting::route('/{record}'),
            'edit' => EditSmsReminderSetting::route('/{record}/edit'),
        ];
    }
}

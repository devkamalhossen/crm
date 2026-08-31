<?php

namespace App\Filament\Resources\CommissionRules;

use App\Filament\Resources\CommissionRules\Pages\CreateCommissionRule;
use App\Filament\Resources\CommissionRules\Pages\EditCommissionRule;
use App\Filament\Resources\CommissionRules\Pages\ListCommissionRules;
use App\Filament\Resources\CommissionRules\Pages\ViewCommissionRule;
use App\Filament\Resources\CommissionRules\Schemas\CommissionRuleForm;
use App\Filament\Resources\CommissionRules\Schemas\CommissionRuleInfolist;
use App\Filament\Resources\CommissionRules\Tables\CommissionRulesTable;
use App\Models\CommissionRule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CommissionRuleResource extends Resource
{
    protected static ?string $model = CommissionRule::class;

    protected static UnitEnum|string|null $navigationGroup = 'Billing & Sales';
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPercentBadge;

    protected static ?string $recordTitleAttribute = 'service_type';

    public static function form(Schema $schema): Schema
    {
        return CommissionRuleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CommissionRuleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommissionRulesTable::configure($table);
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
            'index' => ListCommissionRules::route('/'),
            'create' => CreateCommissionRule::route('/create'),
            'view' => ViewCommissionRule::route('/{record}'),
            'edit' => EditCommissionRule::route('/{record}/edit'),
        ];
    }
}

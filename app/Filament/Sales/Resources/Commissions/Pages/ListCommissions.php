<?php

namespace App\Filament\Sales\Resources\Commissions\Pages;

use App\Filament\Sales\Resources\Commissions\CommissionResource;
use Filament\Resources\Pages\ListRecords;

class ListCommissions extends ListRecords
{
    protected static string $resource = CommissionResource::class;
}
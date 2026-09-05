<?php

namespace App\Filament\Client\Resources\ClientServices\Pages;

use App\Filament\Client\Resources\ClientServices\ClientServiceResource;
use Filament\Resources\Pages\ListRecords;

class ListClientServices extends ListRecords
{
    protected static string $resource = ClientServiceResource::class;
}

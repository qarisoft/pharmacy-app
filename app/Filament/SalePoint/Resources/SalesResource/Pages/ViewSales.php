<?php

namespace App\Filament\SalePoint\Resources\SalesResource\Pages;

use App\Filament\SalePoint\Resources\SalesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSales extends ViewRecord
{
    protected static string $resource = SalesResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

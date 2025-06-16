<?php

namespace App\Filament\SalePoint\Resources\WithDrawResource\Pages;

use App\Filament\SalePoint\Resources\WithDrawResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWithDraw extends ViewRecord
{
    protected static string $resource = WithDrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

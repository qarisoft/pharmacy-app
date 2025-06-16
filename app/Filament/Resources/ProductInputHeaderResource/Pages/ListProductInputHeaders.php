<?php

namespace App\Filament\Resources\ProductInputHeaderResource\Pages;

use App\Filament\Resources\ProductInputHeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductInputHeaders extends ListRecords
{
    protected static string $resource = ProductInputHeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

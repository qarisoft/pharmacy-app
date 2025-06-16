<?php

namespace App\Filament\Resources\ProductInputResource\Pages;

use App\Filament\Resources\ProductInputResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductInputs extends ListRecords
{
    protected static string $resource = ProductInputResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

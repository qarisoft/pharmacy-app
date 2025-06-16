<?php

namespace App\Filament\Resources\ProductInputResource\Pages;

use App\Filament\Resources\ProductInputResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductInput extends EditRecord
{
    protected static string $resource = ProductInputResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

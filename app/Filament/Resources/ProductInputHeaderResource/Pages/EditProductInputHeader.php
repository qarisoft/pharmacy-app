<?php

namespace App\Filament\Resources\ProductInputHeaderResource\Pages;

use App\Filament\Resources\ProductInputHeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductInputHeader extends EditRecord
{
    protected static string $resource = ProductInputHeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

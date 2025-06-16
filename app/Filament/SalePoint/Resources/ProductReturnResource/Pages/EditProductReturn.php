<?php

namespace App\Filament\SalePoint\Resources\ProductReturnResource\Pages;

use App\Filament\SalePoint\Resources\ProductReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductReturn extends EditRecord
{
    protected static string $resource = ProductReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

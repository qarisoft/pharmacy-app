<?php

namespace App\Filament\SalePoint\Resources\ProductReturnResource\Pages;

use App\Filament\SalePoint\Resources\ProductReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductReturn extends CreateRecord
{
    protected static string $resource = ProductReturnResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['end_price'] = $data['end_price'] - $data['discount'];
        return $data;
    }
}

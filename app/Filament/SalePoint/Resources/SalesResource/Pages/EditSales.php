<?php

namespace App\Filament\SalePoint\Resources\SalesResource\Pages;

use App\Filament\SalePoint\Resources\SalesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSales extends EditRecord
{
    protected static string $resource = SalesResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function getRedirectUrl(): ?string
    {
        return ViewSales::getUrl(['record'=>$this->record]);
    }



}

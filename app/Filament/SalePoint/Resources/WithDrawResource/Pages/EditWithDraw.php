<?php

namespace App\Filament\SalePoint\Resources\WithDrawResource\Pages;

use App\Filament\SalePoint\Resources\WithDrawResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWithDraw extends EditRecord
{
    protected static string $resource = WithDrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

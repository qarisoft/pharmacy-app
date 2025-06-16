<?php

namespace App\Filament\Resources\MeasureUnitResource\Pages;

use App\Filament\Resources\MeasureUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMeasureUnit extends EditRecord
{
    protected static string $resource = MeasureUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

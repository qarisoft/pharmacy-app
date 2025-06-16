<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeasureUnitResource\Pages;
//use App\Filament\Resources\MeasureUnitResource\RelationManagers;
use App\Models\Products\MeasureUnit;
use App\Models\Products\MeasureUnitName;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MeasureUnitResource extends Resource
{
    protected static ?string $model = MeasureUnitName::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function CreationFormArray(): array
    {
        return [
            TextInput::make('name')
                ->label(__('name'))
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(MeasureUnitResource::CreationFormArray());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMeasureUnits::route('/'),
//            'create' => Pages\CreateMeasureUnit::route('/create'),
//            'edit' => Pages\EditMeasureUnit::route('/{record}/edit'),
        ];
    }
}

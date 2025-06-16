<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Products\MeasureUnit;
use App\Models\Products\MeasureUnitName;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('name')
                    ->options(MeasureUnitName::all()->pluck('name','name')),

                TextInput::make('count')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->default(2),

                TextInput::make('discount')->numeric()->default(0),
//                TextInput::make('product.uni')->readOnly(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('count')
            ->columns([
                TextColumn::make('isCost')->badge()->state(fn(MeasureUnit $record) => $record->isCost() ? "isCost" : "no"),
                TextColumn::make('name'),
                TextColumn::make('count'),
                TextColumn::make('sell-Price')->state(fn(MeasureUnit $record)=>$record->sellPrice()),
                TextColumn::make('discount'),
                TextColumn::make('is_primary')
                    ->state(fn($record)=>$record->count === 1?'primary':($record->isCost() ? 'isCost' : ""))
                    ->badge(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('cost')->action(function (MeasureUnit $record) {
                    $a = $record->product->lastStoreItem;
                    $a->update(['unit_id' => $record->id]);
                }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

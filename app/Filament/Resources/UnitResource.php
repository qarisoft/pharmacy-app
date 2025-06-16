<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;

//use App\Filament\Resources\UnitResource\RelationManagers;
use App\Models\Products\MeasureUnit;
use App\Models\Products\MeasureUnitName;
use App\Models\Store\ProductInput;
use App\Models\Store\ProductInputHeader;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Colors\Color;
class UnitResource extends Resource
{
    protected static ?string $model = MeasureUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('name')->options(MeasureUnitName::query()->pluck('name', 'name')->toArray())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('isCost')->badge()->state(fn(MeasureUnit $record) => $record->isCost() ? "isCost" : "no"),
                TextColumn::make('count'),
                TextColumn::make('product.lastStoreItem.header.bill_number'),
                TextColumn::make('unit-count')->state(fn($record) => $record->product->units()->count() )
                    ->label('p'),
                TextColumn::make('cost-price')
                    ->badge()->color(Color::Fuchsia)
                    ->state(fn(MeasureUnit $record)=>number_format($record->costPrice(),0))
                    ->label('Cost Price'),
                TextColumn::make('sell-Price')
                    ->badge()->color(Color::Cyan)
                    ->state(fn($record) => $record->sellPrice())->label('Sell Price'),
                TextColumn::make('profit-price')
                    ->badge()->color(Color::Green)
                    ->state(fn($record) => number_format($record->profit(),0)),
                TextColumn::make('product.name_ar')->searchable(),
                TextColumn::make('product_id')->searchable(),
            ])
            ->filters([
                Filter::make('is_featured')->query(function (Builder $query) {
                    $query->whereHas('product',function (Builder $query) {
                        $query->whereHas('inputItems',function (Builder $query) {
                            $query->doesntHave('unit');
                        });
                    });
                }),
//                Tables\Filters\Filter::make('negative')
//                    ->query(fn($query) =>$query->where('profit', '<', 0)),

//                SelectFilter::make('header')
//                    ->options(fn (): array => ProductInputHeader::query()->pluck('bill_number', 'id')->all())
//                ->query(fn (): array => ProductInputHeader::query()->pluck('name', 'id')->all()),
            ])
            ->actions([
                Action::make('cost')->action(function (MeasureUnit $record) {
                    $a = $record->product->lastStoreItem;
                    $a->update(['unit_id' => $record->id]);
                }),
                Action::make('edit-p')
                    ->url(fn($record): string => route('filament.admin.resources.products.edit', $record->product))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->groups(['product.id'])
            ->defaultGroup('product.id')
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


    public static function getEloquentQuery(): Builder
    {
        return  MeasureUnit::query()->whereHas('product', function (Builder $query) {
            $query->where('unit_price','>',0);
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}

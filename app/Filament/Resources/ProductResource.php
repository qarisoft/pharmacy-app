<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
//use App\Models\Products\MeasureUnit;
use App\Models\Products\MeasureUnitName;
use App\Models\Products\Product;
use App\Models\Store\ProductInputHeader;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function CreationArray(): array
    {
        return [
            TextInput::make('name_ar')->required(),
            TextInput::make('unit_price'),
            TextInput::make('name_en'),
            TextInput::make('scientific_name'),
            TextInput::make('barcode'),
            Select::make('company_id')->relationship('company', 'name'),

        ];
    }

    protected static ?string $navigationGroup = 'Products';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ProductResource::CreationArray());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name_ar')->searchable()
                    ->label(__('name_ar')),
                TextColumn::make('name_en')->searchable(),
                TextColumn::make('lastStoreItem.unit_cost_price')->label(__('cost_price')),
                TextColumn::make('unit_price'),
                TextColumn::make('inputs')
                    ->label(__('in_store'))
                    ->state(fn(Product $record) => $record->inputItemsCount() - $record->soldItemsCount()),
                TextColumn::make('scientific_name')->label(__('scientific_name')),
                TextColumn::make('barcode'),
                TextColumn::make('company.name')
                    ->label(__('company'))
                    ->badge(),
            ])
            ->filters([
//                SelectFilter::make('header')
//                    ->relationship('header', 'bill_number')
//                    ->options(fn (): array => ProductInputHeader::query()->pluck('bill_number', 'id')->all()),
                Filter::make('noPrice')->label(__('no_price'))
                    ->query(fn (Builder $query): Builder => $query->where('unit_price', 0)),

                Filter::make('withPrice')->label(__('withPrice'))
                    ->query(fn (Builder $query): Builder => $query->where('unit_price', '>',0))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->persistFiltersInSession();
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UnitsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
           'create' => Pages\CreateProduct::route('/create'),
           'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

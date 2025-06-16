<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductInputResource\Pages;

//use App\Filament\Resources\ProductInputResource\RelationManagers;
use App\Models\Products\Product;
use App\Models\Store\ProductInput;
use App\Models\Store\ProductInputHeader;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;

//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductInputResource extends Resource
{
    protected static ?string $model = ProductInput::class;
    protected static ?string $navigationGroup = 'Store';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->options(fn() => Product::pluck('name_ar', 'id'))
                    ->required()
                    ->autofocus(fn($operation) => $operation == 'create')
                    ->searchable()
                    ->getSearchResultsUsing(fn($search) => Product::search($search))
                    ->columnSpan(3),
                TextInput::make('quantity')
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                        $set('total_cost_price', $get('unit_cost_price') * $state);
                    })
                    ->default(1)
                    ->columns(1),
                DatePicker::make('expire_date')
                    ->columnSpan(2),
                Select::make('unit_id')
                    ->live()
                    ->options(function($get){
                        $pId=$get('product_id');
                        $product=Product::find($pId);
                        if ($product) {
                            $aa = [];
                            foreach($product->units as $u){
                                $aa[$u->id]=$u->name.' -'.$u->count;
                            }

                            return $aa;
                        }
                        return [];
                    }),
                Forms\Components\TextInput::make('unit_cost_price')
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                        $set('total_cost_price', $get('quantity') * $state);
                    })
                    ->default(0),

                Forms\Components\TextInput::make('total_cost_price')
                    ->numeric()
                    ->readOnly()
                    ->default(0)
            ])->columns(4);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('product.name_ar')->label(__('product'))->searchable(),
                TextColumn::make('unit_cost_price')->label(__('unit_cost_price')),
                TextColumn::make('quantity')->label(__('quantity')),
                TextColumn::make('product.barcode')
                    ->label(__('barcode'))->copyable(),
                TextColumn::make('product.unit_price')
                    ->label(__('unit_price'))
                ->copyable()
                ,
                TextColumn::make('header.bill_number')->label(__('bill_number')) ,
            ])
            ->filters([

                Tables\Filters\Filter::make('noid')->query(fn($query) => $query->whereNull('unit_id')),
                SelectFilter::make('header')
                    ->relationship('header', 'bill_number')
                    ->options(fn (): array => ProductInputHeader::query()->pluck('bill_number', 'id')->all())

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('edit-product')
                    ->url(fn (ProductInput $record): string => route('filament.admin.resources.products.edit', $record->product))
                    ->openUrlInNewTab()

            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])

            ;
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
            'index' => Pages\ListProductInputs::route('/'),
//            'create' => Pages\CreateProductInput::route('/create'),
//            'edit' => Pages\EditProductInput::route('/{record}/edit'),
        ];
    }
}

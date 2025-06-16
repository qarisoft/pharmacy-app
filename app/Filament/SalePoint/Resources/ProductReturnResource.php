<?php

namespace App\Filament\SalePoint\Resources;

use App\Filament\SalePoint\Resources\ProductReturnResource\Pages;

//use App\Filament\SalePoint\Resources\ProductReturnResource\RelationManagers;
use App\Models\Products\MeasureUnit;
use App\Models\Products\Product;
use App\Models\Refund\ReturnHeader;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductReturnResource extends Resource
{
    protected static ?string $model = ReturnHeader::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('items')->label(__('items'))
                    ->relationship('items')
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        $price = 0;
                        foreach ($state as $priceItem) {
                            $quantity = $priceItem['quantity'] ?? 1;
                            $unit_id = $priceItem['unit_id'];
                            if ($unit_id) {
                                $price = $price + MeasureUnit::getSellPrice($unit_id, $quantity);
                            }
                        }
                        $set('end_price', $price - intval($get('discount') ?? 0));
                    })
                    ->schema([
                        Select::make('product_id')
                            ->label(__('product'))
                            ->options(fn() => Product::query()->where('unit_price', '>', 0)->pluck('name_ar', 'id'))
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $set('unit_id', Product::query()->find($state)?->units()->first()?->id);
                                $set('end_price', null);
                            })
                            ->required()
                            ->autofocus(fn($operation) => $operation == 'create')
                            ->searchable()
                            ->columnSpan(3)
                            ->getSearchResultsUsing(fn($search) => Product::search($search)),

                        Select::make('unit_id')
                            ->label(__('unit'))
                            ->required()
                            ->live()
                            ->preload()
                            ->native(false)
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $set('end_price', MeasureUnit::getSellPrice($state, $get('quantity')));
                            })
                            ->options(fn(Get $get) => MeasureUnit::query()
                                ->where('product_id', $get('product_id'))
                                ->pluck('name', 'id'))
                        ,

                        TextInput::make('quantity')
                            ->label(__('quantity'))
                            ->numeric()
                            ->live(debounce: 600)
                            ->required()
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $set('end_price', intval((MeasureUnit::getSellPrice($get('unit_id'), $state))));
                            })
                            ->required()
                            ->default(0)
                            ->columns(1),

                        TextInput::make('end_price')
                            ->label(__('price'))
                            ->numeric()
                            ->default(0),
                            TextInput::make('cost_price')
                            ->label(__('price'))
                            ->numeric()
                            ->visible(false)
                            ->default(0),

                    ])->columns(8)
                    ->columnSpanFull(),

                Fieldset::make()->schema([

                    TextInput::make('discount')
                        ->label(__('discount'))
                        ->numeric()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                            $items = $get('items');
                            $price = 0;
                            foreach ($items as $item) {
                                $price = $price + $item['end_price'];
                            }
                            $set('end_price', $price - $state);
                        })
                        ->inlineLabel()
                        ->default(0),

                    TextInput::make('end_price')
                        ->inlineLabel()
                        ->label(__('end_price'))
                        ->numeric()
                        ->readOnly()
                        ->default(0),
                    TextInput::make('cost_price')
                        ->inlineLabel()
                        ->visible(false)
                        ->label(__(''))
                        ->numeric()
                        ->readOnly()
                        ->default(0),
                ])->columnSpan(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListProductReturns::route('/'),
            'create' => Pages\CreateProductReturn::route('/create'),
            'view' => Pages\ViewProductReturn::route('/{record}'),
            'edit' => Pages\EditProductReturn::route('/{record}/edit'),
        ];
    }
}

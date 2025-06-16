<?php

namespace App\Filament\SalePoint\Resources;

use App\Filament\SalePoint\Resources\SalesResource\Pages;
use App\Models\Products\MeasureUnit;
use App\Models\Products\Product;
use App\Models\Sales\SaleHeader;
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
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SalesResource extends Resource
{
    protected static ?string $model = SaleHeader::class;

    public static function getLabel(): ?string
    {
        return __('Sales');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Sales');
    }


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('items')->label(__('items'))
                    ->relationship('items')
                    ->live()
//                    ->mutateRelationshipDataBeforeFillUsing(function ($data) {
//                        dump($data);
//                    })
//                    ->lazy()
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        $price = 0;
                        foreach ($state as $priceItem) {
                            $quantity = $priceItem['quantity'] ?? 1;
                            $unit_id = $priceItem['unit_id'];
                            if ($unit_id) {
                                $price = $price + MeasureUnit::getSellPrice($unit_id, $quantity);
                            }
                        }
                        $discount = $get('discount');
                        $set('end_price', $price - intval($discount ?? 0));
                    })
                    ->schema([
                        Select::make('product_id')
                            ->label(__('product'))
                            ->options(fn() => Product::query()->where('unit_price', '>', 0)->pluck('name_ar', 'id'))
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $p=Product::query()->find($state);
                                $set('unit_id', $p?->units()->first()?->id);
                                $set('end_price', null);
                                $set('instore', $p->inStore());
                            })
                            ->required()
                            ->autofocus(fn($operation) => $operation == 'create')
                            ->searchable()
                            ->columnSpan(3)
                            ->getSearchResultsUsing(fn($search) => Product::search($search)),
//                        TextInput::make('instore')->disabled()->readOnly(),

                        Select::make('unit_id')
                            ->label(__('unit'))
                            ->required()
                            ->live()
                            ->preload()
                            ->native(false)
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $set('end_price', MeasureUnit::getSellPrice($state,$get('quantity')));
                            })
                            ->options(fn(Get $get) => MeasureUnit::query()
                                ->where('product_id', $get('product_id'))
                                ->pluck('name', 'id'))
                        ,

                        TextInput::make('quantity')
                            ->label(__('quantity'))
                            ->numeric()
                            ->live(onBlur: true)
                            ->required()
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $set('end_price', intval((MeasureUnit::getSellPrice($get('unit_id'),$state)) ));
//                                $set('cost_price', intval((MeasureUnit::getCostPrice($get('unit_id'),$state)) ));
                            })
                            ->required()
                            ->default(0)
                            ->columns(1),


                        TextInput::make('end_price')
                            ->label(__('price'))
                            ->numeric()
                            ->default(0),
                        TextInput::make('cost_price')
                            ->label(__('cost_price'))
                            ->numeric()
                            ->default(0)
                    ])->columns(8)
                    ->columnSpanFull(),

                Fieldset::make()
                    ->schema([
                    TextInput::make('discount')
                        ->label(__('discount'))
                        ->numeric()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                            $items = $get('items');
                            $addition = $get('addition');
                            $price = 0;
                            foreach ($items as $item) {
                                $price = $price + $item['end_price'];
                            }
                            $set('end_price', $price - $state + $addition);
                        })
                        ->inlineLabel()
                        ->default(0),
                        TextInput::make('addition')
                            ->label(__('addition'))
                            ->numeric()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $items = $get('items');
                                $discount = $get('discount');
                                $price = 0;
                                foreach ($items as $item) {
                                    $price = $price + $item['end_price'];
                                }
                                $set('end_price', $price - $discount+ $state);
                            })
                            ->inlineLabel()
                            ->default(0),


                    TextInput::make('end_price')->inlineLabel()
                        ->label(__('end_price'))
                        ->numeric()
                        ->readOnly()
                        ->default(0),
                ])->columnSpan(1),
                Forms\Components\Textarea::make('customer_name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('customer_name'),
                Tables\Columns\TextColumn::make('end_price')->searchable()
                    ->summarize(Sum::make('Sum')),
                Tables\Columns\TextColumn::make('profit_price')
                    ->hidden(fn()=>! auth()->user()->is_admin)
                    ->summarize(Tables\Columns\Summarizers\Sum::make('Sum')),
//                Tables\Columns\TextColumn::make('pf')->state(fn($record)=>$record->items()->sum('profit')),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->since(),
                Tables\Columns\TextColumn::make('created_at')->label('creation'),
                Tables\Columns\TextColumn::make('updated_at'),
            ])->searchDebounce('1ms')
            ->defaultSort('id', 'desc')
            ->filters([
//                Tables\Filters\Filter::make('today')
//                    ->default()
//                    ->query(fn(Builder $query): Builder => $query->where('created_at', '>', today()->toDateString())),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->persistFiltersInSession();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->is_admin){
            return SaleHeader::query();
        }
        return SaleHeader::query()->where('created_at', '>', today()->toDateString());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSales::route('/create'),
            'view' => Pages\ViewSales::route('/{record}'),
            'edit' => Pages\EditSales::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\ProductInputHeaderResource\RelationManagers;

use App\Models\Products\Product;
use App\Models\Store\ProductInput;
use App\PaymentType;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')->relationship('product', 'name_ar')
                    ->createOptionForm([
                        TextInput::make('name_ar')->required()
                    ])
                    ->options(fn() => Product::pluck('name_ar', 'id'))
                    ->required()
                    ->autofocus(fn($operation) => $operation == 'create')
                    ->searchable()
                    ->getSearchResultsUsing(fn($search) => Product::search($search)),
                TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->columns(1),

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
                DatePicker::make('expire_date')
                    ->columnSpan(2),
                Select::make('payment_type')->options(['debit'=>'debit','cash'=>'cash']),

                Forms\Components\TextInput::make('unit_cost_price')
                    ->numeric()->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('quantity')
            ->columns([
                Tables\Columns\TextColumn::make('product.name_ar')->searchable(),
                Tables\Columns\TextColumn::make('expire_date'),
                Tables\Columns\TextColumn::make('payment_type'),
                Tables\Columns\TextColumn::make('unit_cost_price'),
                Tables\Columns\TextColumn::make('product.unit_price'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('total_cost_price')->money(locale: 'en'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            ]);
    }
}

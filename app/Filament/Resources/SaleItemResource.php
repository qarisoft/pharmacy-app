<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleItemResource\Pages;
//use App\Filament\Resources\SaleItemResource\RelationManagers;
//use App\Models\SaleItem;
use App\Models\Products\MeasureUnit;
use App\Models\Sales\SaleHeader;
use App\Models\Sales\SaleItem;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PHPUnit\Util\Filter;

class SaleItemResource extends Resource
{
    protected static ?string $model = SaleItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('product.name'),
//                TextInput::make('quantity')->numeric(),
//                TextInput::make('end_price'),
//                TextInput::make('cost_price'),
//                TextInput::make('profit'),
//                TextInput::make('unit_id'),
            ]);
    }
//$table->foreignId('product_id');
//$table->foreignId('header_id')->nullable();
//$table->integer('quantity');
//$table->double('end_price');
//$table->double('cost_price')->nullable();
//$table->double('unit_cost_price')->nullable();
//$table->double('product_price')->nullable();
//$table->double('discount')->nullable();
//$table->double('profit')->default(0);
//$table->integer('unit_id')->default(1);
//$table->integer('unit_count')->nullable();
    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('product.name_ar')->searchable(),
                TextColumn::make('unit.name')->searchable(),
                TextColumn::make('quantity'),
                TextColumn::make('end_price')->label('sell')->summarize(Tables\Columns\Summarizers\Sum::make('Sum')),
                TextColumn::make('cost')->state(fn(SaleItem $record)=>$record->costPrice()),
                TextColumn::make('profit')->summarize(Sum::make('Sum')),
            ])
            ->filters([
                Tables\Filters\Filter::make('negative')
                    ->query(fn($query) =>$query->where('profit', '<', 0)),
                Tables\Filters\SelectFilter::make('header_id')
                    ->options(SaleHeader::query()->pluck('id', 'id')->toArray())
                    ->query(fn (Builder $query): Builder => $query->where('id', '>', 0)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('update-p')->action(fn(SaleItem $record)=>$record->updateProfit())
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
            'index' => Pages\ListSaleItems::route('/'),
            'create' => Pages\CreateSaleItem::route('/create'),
            'edit' => Pages\EditSaleItem::route('/{record}/edit'),
        ];
    }
}

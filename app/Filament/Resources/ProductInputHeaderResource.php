<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductInputHeaderResource\Pages;
//use App\Filament\Resources\ProductInputHeaderResource\RelationManagers;
use App\Filament\Resources\ProductInputHeaderResource\RelationManagers\ItemsRelationManager;
use App\Models\Store\ProductInputHeader;
use App\Models\Store\Vendor;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductInputHeaderResource extends Resource
{
    protected static ?string $model = ProductInputHeader::class;
    protected static ?string $navigationGroup = 'Store';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('vendor_id')
                    ->options(fn()=>Vendor::pluck('name', 'id')->toArray())
                    ->createOptionForm([
                        TextInput::make('name')->label(__('name')),
                    ])
                    ->label(__('vendor')),
                TextInput::make('bill_number')->label(__('bill_number')),
                TextInput::make('total_price')->label(__('total_price'))->default(0),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('bill_number')
                    ->label(__('bill_number')),
                TextColumn::make('total_price')
                    ->summarize(Sum::make('Sum'))
                    ->money(locale: 'en')
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
            ItemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductInputHeaders::route('/'),
            'create' => Pages\CreateProductInputHeader::route('/create'),
            'edit' => Pages\EditProductInputHeader::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\SalePoint\Resources;

use App\Filament\SalePoint\Resources\WithDrawResource\Pages;
//use App\Filament\SalePoint\Resources\WithDrawResource\RelationManagers;
use App\Models\Refund\WithDraw;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Database\Eloquent\SoftDeletingScope;

class WithDrawResource extends Resource
{
    protected static ?string $model = WithDraw::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('note')->required(),
                TextInput::make('amount')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('note'),
                TextColumn::make('amount'),
                TextColumn::make('created_at'),
                TextColumn::make('createdBy.name'),
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
            'index' => Pages\ListWithDraws::route('/'),
            'create' => Pages\CreateWithDraw::route('/create'),
            'view' => Pages\ViewWithDraw::route('/{record}'),
            'edit' => Pages\EditWithDraw::route('/{record}/edit'),
        ];
    }
}

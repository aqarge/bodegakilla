<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Forms\Components\TextInput::make('name_pro')->label('Nombre del producto'),
                Forms\Components\Textarea::make('descrip_pro')->label('Descripcion del producto'),
                Forms\Components\TextInput::make('price_pro')->label('Precio del producto'),
                Forms\Components\TextInput::make('stock_pro')->label('Cantidad de productos'),
                Forms\Components\DatePicker::make('expiration')->label('Fecha de vencimiento'),
                Forms\Components\Select::make('pro_types_id')
                    ->relationship('pro_types', 'name_protype')
                    ->label('Tipo de producto'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_pro')->label('Nombre del producto'),
                Tables\Columns\TextColumn::make('descrip_pro')->label('Descripcion'),
                Tables\Columns\TextColumn::make('price_pro')->label('Precio del producto'),
                Tables\Columns\TextColumn::make('stock_pro')->label('Cantidad de productos'),
                Tables\Columns\TextColumn::make('expiration')->label('Fecha de vencimiento'),
                Tables\Columns\TextColumn::make('pro_types.name_protype')->label('Tipo de producto'),

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
            //
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

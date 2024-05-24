<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DebtProductResource\Pages;
use App\Filament\Resources\DebtProductResource\RelationManagers;
use App\Models\DebtProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DebtProductResource extends Resource
{
    protected static ?string $model = DebtProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Fiados';
    protected static ?string $navigationGroup = 'Principales registros';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('debt_id')
                ->required()
                ->relationship('debt', 'name_debt')
                ->label('Deuda')
                ->searchable()
                ->createOptionForm([
                    Forms\Components\Select::make('client_id')
                    ->required()
                    ->relationship('client', 'name_cli')
                    ->label('Cliente')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name_cli')->required()->label('Nombre del cliente'),
                        Forms\Components\Textarea::make('surname_cli')->label('Apellidos del cliente'),
                        Forms\Components\TextInput::make('nick_cli')->label('Apodo del cliente'),
                        Forms\Components\TextInput::make('phone_cli')->label('Celular'),
                    ]),

                Forms\Components\TextInput::make('name_debt')->label('Nombre de la deuda'),
                Forms\Components\TextInput::make('descrip_debt')->label('Descripción de la deuda'),
                ]),

            Forms\Components\Select::make('product_id')
                ->required()
                ->relationship('product', 'name_pro')
                ->label('Producto')
                ->createOptionForm([
                    Forms\Components\TextInput::make('name_pro')->required()->label('Nombre del producto'),
                Forms\Components\Textarea::make('descrip_pro')->label('Descripcion del producto'),
                Forms\Components\TextInput::make('price_pro')->required()->label('Precio del producto'),
                Forms\Components\TextInput::make('stock_pro')->required()->label('Cantidad de productos'),
                Forms\Components\DatePicker::make('expiration')->label('Fecha de vencimiento'),
                Forms\Components\Select::make('pro_type_id')->required()
                    ->relationship('pro_types', 'name_protype')
                    ->label('Tipo de producto')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name_protype')->required()->label('Nombre de tipo'),
                        Forms\Components\Textarea::make('descrip_protype')->label('Descripción'),
                    ]),
                ])
                ->searchable(),

            Forms\Components\TextInput::make('quantity')
                ->required()
                ->numeric()
                ->label('Cantidad'),

            Forms\Components\TextInput::make('amount_debt')
                ->required()
                ->label('Monto de la deuda'),


        ]);
}


public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('debt.name_debt')->label('Deuda'),
            Tables\Columns\TextColumn::make('product.name_pro')->label('Producto'),
            Tables\Columns\TextColumn::make('quantity')->label('Cantidad'),
            Tables\Columns\TextColumn::make('amount_debt')->label('Monto'),
            Tables\Columns\TextColumn::make('status_debt')->label('Estado'),
            Tables\Columns\TextColumn::make('created_at')->label('Fecha de creación'),
            Tables\Columns\TextColumn::make('updated_at')->label('Fecha de actualización'),
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
            'index' => Pages\ListDebtProducts::route('/'),
            'create' => Pages\CreateDebtProduct::route('/create'),
            'edit' => Pages\EditDebtProduct::route('/{record}/edit'),
        ];
    }
}

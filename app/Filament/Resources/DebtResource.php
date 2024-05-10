<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DebtResource\Pages;
use App\Filament\Resources\DebtResource\RelationManagers;
use App\Models\Debt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DebtResource extends Resource
{
    protected static ?string $model = Debt::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Fiados';
    protected static ?string $navigationGroup = 'Libro de fiados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('clients_id')
                    ->relationship('clients', 'name_cli')
                    ->label('Cliente'),
                Forms\Components\Select::make('product_ids')
                    ->relationship('products', 'name_pro')
                    ->multiple()
                    ->label('Productos'),
                Forms\Components\TextInput::make('descrip_debt')->label('Descripción de la deuda'),
                Forms\Components\TextInput::make('amount_debt')->label('Monto de la deuda'),
                Forms\Components\Checkbox::make('status_debt')->label('Estado de la deuda'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('clients.name_cli')->label('Cliente'),
                Tables\Columns\TextColumn::make('products.name_pro')->label('Producto'),
                Tables\Columns\TextColumn::make('descrip_debt')->label('Descripción'),
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
            'index' => Pages\ListDebts::route('/'),
            'create' => Pages\CreateDebt::route('/create'),
            'edit' => Pages\EditDebt::route('/{record}/edit'),
        ];
    }
}

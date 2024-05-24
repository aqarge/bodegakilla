<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;



class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Transacciones';
    protected static ?string $navigationGroup = 'Libro de caja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount_tran')->required()->label('Amount Transaction'),
                Forms\Components\Textarea::make('descrip_tran')->label('Description Transaction'),

                Forms\Components\Select::make('tran_types_id')->required()
                    ->relationship('tran_types', 'name_type')
                    ->label('Transaction Type')
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name_type')->required()->label('Name Type'),
                        Forms\Components\Textarea::make('descrip_type')->label('Description Type'),
                    ]),


                Forms\Components\Select::make('boxes_id')->required()
                    ->relationship('boxes', 'opening')
                    ->label('fecha de caja')
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\DatePicker::make('opening')->required()->label('Fecha de apertura'),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
{
    // Calcular el total de ingresos
    $totalIngresos = Transaction::where('tran_types_id', '=', 1)->sum('amount_tran');

    // Calcular el total de egresos
    $totalEgresos = Transaction::where('tran_types_id', '=', 2)->sum('amount_tran');

    // Calcular el saldo
    $saldo = $totalIngresos - $totalEgresos;

    return $table
    ->columns([
        Tables\Columns\TextColumn::make('amount_tran')->label('Monto'),
        Tables\Columns\TextColumn::make('tran_types.name_type')->label('Tipo'),
        Tables\Columns\TextColumn::make('boxes.opening')->label('Caja'),
        Tables\Columns\TextColumn::make('descrip_tran')->label('Descripcion'),
        Tables\Columns\TextColumn::make('created_at')->label('Fecha de Creación'),
    ])
    ->filters([


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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
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
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\IconColumn;
use App\Events\TransactionCreated;
use App\Filament\Resources\TransactionResource\Pages\CreateTransaction;



class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-s-currency-dollar';
    protected static ?string $navigationLabel = '►►TRANSACCIONES';
    protected static ?string $navigationGroup = 'Información de caja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount_tran')->required()->label('Amount Transaction'),
                Forms\Components\Textarea::make('descrip_tran')->label('Description Transaction'),
                Forms\Components\Select::make('boxes_id')->required()
                    ->relationship('boxes', 'opening')
                    ->label('fecha de caja')
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\DatePicker::make('opening')->required()->label('Fecha de apertura'),
                    ]),
                Radio::make('type_tran')->label('Estado de la deuda')
                ->options([
                    'inicial' => 'Saldo inicial',
                    'ingreso' => 'Ingreso',
                    'egreso' => 'Egreso',
                ])

            ]);
    }

    public static function table(Table $table): Table
{
    return $table
    ->columns([
        Tables\Columns\TextColumn::make('amount_tran')->label('Monto'),
        IconColumn::make('type_tran')->label('Tipo')
                ->icon(fn (string $state): string => match ($state) {
                    'inicial' => 'heroicon-c-banknotes',
                    'ingreso' => 'heroicon-m-arrow-trending-up',
                    'egreso' => 'heroicon-m-arrow-trending-down',
                
                })
                ->color(fn (string $state): string => match ($state) {
                    'inicial' => 'info',
                    'ingreso' => 'success',
                    'egreso' => 'danger',
                    default => 'gray',
                }),
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

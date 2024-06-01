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
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Filters\Filter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction as TablesExportBulkAction;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-c-presentation-chart-line';
    //protected static ?string $navigationLabel = '►►TRANSACCIONES';
    protected static ?string $modelLabel = '►►TRANSACCIONES';
    protected static ?string $navigationGroup = 'Información de caja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount_tran')->required()->label('Monto de transacción'),
                Forms\Components\Textarea::make('descrip_tran')->label('Descripción'),
                Forms\Components\Select::make('boxes_id')->required()
                    ->relationship('boxes', 'opening')
                    ->label('Fecha de caja')
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\DatePicker::make('opening')->required()->label('Fecha de apertura'),
                    ]),
                Radio::make('type_tran')->label('Tipo de transacción')
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
        Tables\Columns\TextColumn::make('boxes.opening')->label('Caja')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('descrip_tran')->label('Descripcion'),
        Tables\Columns\TextColumn::make('created_at')->label('Fecha de Creación'),
    ])
    ->filters([
        Filter::make('created_at')->label('Fecha de creación')
    ->form([
        DatePicker::make('Desde'),
        DatePicker::make('Hasta'),
    ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when(
                $data['Desde'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
            )
            ->when(
                $data['Hasta'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
            );
    }),
    Tables\Filters\SelectFilter::make('type_tran')->label('Tipo de transacción')
    ->options([
        'inicial' => 'Monto inicial',
        'ingreso' => 'Ingresos',
        'egreso' => 'Egresos',
    ]),
    

    ])
    ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
    ])
    ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
            TablesExportBulkAction::make(),
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

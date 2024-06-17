<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DebtResource\Pages;
use App\Filament\Resources\DebtResource\RelationManagers;
use App\Filament\Resources\DebtResource\RelationManagers\ProductsRelationManager;
use App\Models\Debt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\IconColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction as TablesExportBulkAction;
use App\Models\Client;

class DebtResource extends Resource
{
    protected static ?string $model = Debt::class;

    protected static ?string $navigationIcon = 'heroicon-s-rectangle-stack';
    protected static ?string $modelLabel = '►►FIADOS';
    protected static ?string $navigationGroup = 'Información de fiados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('totaldebt_id')
                    ->required()
                    ->relationship('totaldebt', 'name_debt')
                    ->label('Cuenta')
                    ->preload() 
                    ->searchable() 
                    ->createOptionForm([
                        Forms\Components\Select::make('client_id')
                    ->required()
                    ->relationship('client', 'name_cli')
                    ->label('Cliente')
                    ->preload() 
                    ->searchable() 
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name_cli')->required()->label('Nombre del cliente'),
                        Forms\Components\Textarea::make('surname_cli')->label('Apellidos del cliente'),
                        Forms\Components\TextInput::make('nick_cli')->label('Apodo del cliente'),
                        Forms\Components\TextInput::make('phone_cli')->label('Celular'),
                    ])
                    ->afterStateUpdated(function (callable $set, $state) {
                        $client = Client::find($state);
                        if ($client) {
                            $set('name_debt', $client->name_cli);
                        }
                    })
                  ,
                Forms\Components\TextInput::make('name_debt')->label('Nombre de la deuda (nombre del cliente)'),
                Forms\Components\TextArea::make('notes')->label('Notas'),
                Forms\Components\TextInput::make('total_amount')->default(0)->prefix('S/. ')->label('Monto total de la deuda'),
                Radio::make('state_debt')->label('Estado de la deuda')
                ->options([
                    '0' => 'Falta pagar',
                    '1' => 'Pagado',
                ])->default(0),
                Radio::make('risk')->label('Cantidad por cobrar')
                ->options([
                    'baja' => 'Baja',
                    'moderada' => 'Moderada',
                    'alta' => 'Alta',
                ])
                ->default('baja')
                    ]),
                Forms\Components\Textarea::make('descrip_debt')->label('Notas'),
                Forms\Components\TextInput::make('total_debt')->default(0)->prefix('S/. ')->label('Monto total del fiado'),
                


                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('totaldebt.name_debt')->label('Cuenta')
                ->searchable(),
                Tables\Columns\TextColumn::make('descrip_debt')->label('Descripción'),
                Tables\Columns\TextColumn::make('total_debt')->prefix('S/. ')->label('Deuda total'),
                Tables\Columns\TextColumn::make('created_at')->label('Fecha de creación'),
                
            ])
            ->filters([
                //
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
            ProductsRelationManager::class
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

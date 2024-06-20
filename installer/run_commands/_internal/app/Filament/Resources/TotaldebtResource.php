<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TotaldebtResource\Pages;
use App\Filament\Resources\TotaldebtResource\RelationManagers;
use App\Models\Totaldebt;
use App\Models\Client; // Importar el modelo de Cliente
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

class TotaldebtResource extends Resource
{
    protected static ?string $model = Totaldebt::class;

    protected static ?string $navigationIcon = 'heroicon-c-document-text';
    protected static ?string $modelLabel = 'Cuentas';
    protected static ?string $navigationGroup = 'Información de fiados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->required()
                    ->relationship('client', 'name_cli')
                    ->label('Cliente')
                    ->preload()
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
                    }),
                Forms\Components\TextInput::make('name_debt')
                    ->label('Nombre de la deuda (nombre del cliente)'),
                Forms\Components\TextArea::make('notes')->label('Notas'),
                Forms\Components\TextInput::make('total_amount')->prefix('S/. ')->label('Monto total de la deuda')->default(0),
                Radio::make('state_debt')->label('Estado de la deuda')
                ->options([
                    '0' => 'Falta pagar',
                    '1' => 'Pagado',
                ])
                ->default(0),
                Radio::make('risk')->label('Cantidad por cobrar')
                ->options([
                    'nula' => 'Nula',
                    'baja' => 'Baja',
                    'moderada' => 'Moderada',
                    'alta' => 'Alta',
                ])
                ->default('nula')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_debt')->label('Cliente')
                ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')->prefix('S/. ')->label('Deuda total'),
                IconColumn::make('state_debt')->label('Estado')
                ->icon(fn (string $state): string => match ($state) {
                    '0' => 'heroicon-s-hand-thumb-down',
                    '1' => 'heroicon-s-hand-thumb-up',
                })
                ->color(fn (string $state): string => match ($state) {
                    '0' => 'danger',
                    '1' => 'success',
                    default => 'gray',
                }),
                IconColumn::make('risk')->label('Cantidad por cobrar')
                ->icon(fn (string $state): string => match ($state) {
                    'nula' => 'heroicon-c-minus-circle',
                    'baja' => 'heroicon-m-shield-check',
                    'moderada' => 'heroicon-c-pause-circle',
                    'alta' => 'heroicon-m-shield-exclamation',
                })
                ->color(fn (string $state): string => match ($state) {
                    'nula' => 'gray',
                    'baja' => 'success',
                    'moderada' => 'warning',
                    'alta' => 'danger',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('notes')->label('Notas'),
                Tables\Columns\TextColumn::make('created_at')->label('Fecha de creación'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('state_debt')->label('¿Pagado o no?')
                ->options([
                    '0' => 'No pagado',
                    '1' => 'Pagado',
                ]),
                Tables\Filters\SelectFilter::make('risk')->label('Cantidad por cobrar')
                ->options([
                    'baja' => 'Baja',
                    'moderada' => 'Moderada',
                    'alta' => 'Alta',
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    TablesExportBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTotaldebts::route('/'),
            'create' => Pages\CreateTotaldebt::route('/create'),
            'edit' => Pages\EditTotaldebt::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TotaldebtResource\Pages;
use App\Filament\Resources\TotaldebtResource\RelationManagers;
use App\Models\Totaldebt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\IconColumn;

class TotaldebtResource extends Resource
{
    protected static ?string $model = Totaldebt::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Cuentas';
    protected static ?string $navigationGroup = 'Información de fiados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\TextInput::make('name_debt')->label('Nombre de la deuda (nombre del cliente)'),
                Forms\Components\TextArea::make('notes')->label('Notas'),
                Forms\Components\TextInput::make('total_amount')->label('Monto total de la deuda'),
                Radio::make('state_debt')->label('Estado de la deuda')
                ->options([
                    '0' => 'Falta pagar',
                    '1' => 'Pagado',
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_debt')->label('Cliente'),
                Tables\Columns\TextColumn::make('total_amount')->label('Deuda total'),
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
                Tables\Columns\TextColumn::make('notes')->label('Notas'),
            
                Tables\Columns\TextColumn::make('created_at')->label('Fecha de creación'),
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
            'index' => Pages\ListTotaldebts::route('/'),
            'create' => Pages\CreateTotaldebt::route('/create'),
            'edit' => Pages\EditTotaldebt::route('/{record}/edit'),
        ];
    }
}

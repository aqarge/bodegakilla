<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\IconColumn;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pagos';
    protected static ?string $navigationGroup = 'Información de fiados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('totaldebt_id')
                    ->required()
                    ->relationship('totaldebt', 'name_debt')
                    ->label('Cuenta')
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
                    ]),
                Forms\Components\TextArea::make('notes')->label('Notas'),
                Forms\Components\TextInput::make('total_amount')->label('Monto a pagar'),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}

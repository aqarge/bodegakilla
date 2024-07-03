<?php

namespace App\Filament\Resources\TotaldebtResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Totaldebt;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction as TablesExportBulkAction;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';
    protected static ?string $modelLabel = 'Pagos';

    protected static ?string $recordTitleAttribute = 'name_debt';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('totaldebt_id')
                    ->default($this->ownerRecord->id),
                Forms\Components\Select::make('totaldebt_id')
                    ->required()
                    ->relationship('totaldebt', 'name_debt')
                    ->label('Cuenta')
                    ->default($this->ownerRecord->id)
                    ->disabled(), // Desactivar para que no se pueda cambiar
                Forms\Components\TextInput::make('pay')
                    ->label('Monto a pagar')
                    ->required()
                    ->numeric()
                    ->prefix('S/. '),
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        $lastPaymentId = $this->ownerRecord->payments()->latest()->first()->id ?? null;

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('totaldebt.name_debt')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pay')
                    ->label('Pago')
                    ->prefix('S/. '),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notas'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->sortable(),
            ])
            ->filters([
                // Añadir filtros si es necesario
            ])
            ->defaultSort('created_at', 'desc') // Ordenar por fecha de creación en orden descendente
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Asegurarse de que totaldebt_id esté en los datos del formulario
                        if (!isset($data['totaldebt_id'])) {
                            $data['totaldebt_id'] = $this->ownerRecord->id;
                        }

                        // Actualizar el monto total de la deuda
                        $totaldebt = Totaldebt::find($data['totaldebt_id']);
                        
                        if ($totaldebt) {
                            $totaldebt->total_amount -= $data['pay'];
                            $totaldebt->save();
                        }

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->icon('heroicon-m-inbox-arrow-down')
                    ->iconButton()
                    ->url(fn ($record): string => route('debttotal.total', ['debt' => $record]))
                    ->visible(fn ($record): bool => $record->id === $lastPaymentId),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    TablesExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

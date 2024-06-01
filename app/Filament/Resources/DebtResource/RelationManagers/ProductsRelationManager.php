<?php

namespace App\Filament\Resources\DebtResource\RelationManagers;

use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Tables\Actions\AttachAction;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_id')->label('Producto')->hidden(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name_pro')
            ->columns([
                Tables\Columns\TextColumn::make('name_pro'),
                Tables\Columns\TextColumn::make('price_pro'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('subtotal'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $producto = Product::find($state);
                                $set('precio', $producto->price_pro);
                                // Recalcula el subtotal si ya se ha ingresado una cantidad
                                $quantity = $get('quantity');
                                if ($quantity) {
                                    $set('subtotal', $producto->price_pro * $quantity);
                                }
                            }),
                        Forms\Components\TextInput::make('precio')
                            ->label('Precio')
                            ->disabled(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')
                            ->numeric()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $precio = $get('precio');
                                if ($precio) {
                                    $set('subtotal', $precio * $state);
                                }
                            }),
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->required()
                            ->numeric()
                        ]),
                        Tables\Actions\Action::make('pdf')->icon('heroicon-m-inbox-arrow-down')->iconButton()
                        ->url(fn (): string => route('debttotal.total', ['debt' => $this->getOwnerRecord()]))
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

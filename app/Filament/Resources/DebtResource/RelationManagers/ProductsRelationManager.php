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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction as TablesExportBulkAction;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';
    protected static ?string $modelLabel = 'productos para fiado';

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
                Tables\Columns\TextColumn::make('name_pro')->label('Producto'),
                Tables\Columns\TextColumn::make('price_pro')->label('Precio')->numeric()
                ->prefix('S/. '),
                Tables\Columns\TextColumn::make('quantity')->label('Cantidad'),
                Tables\Columns\TextColumn::make('subtotal')->label('Sub Total')->numeric()
                ->prefix('S/. '),
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
    })
    ->label('Producto')
    ->searchable()
    ->getSearchResultsUsing(function (string $query) {
        return Product::where('name_pro', 'like', "%{$query}%")
            ->orWhere('id', 'like', "%{$query}%")
            ->get()
            ->mapWithKeys(fn ($product) => [$product->id => $product->name_pro . ' (Código: ' . $product->id . ')']);
    })
    ->getOptionLabelUsing(fn ($value) => Product::find($value)?->name_pro . ' (Código: ' . $value . ')'),
                        Forms\Components\TextInput::make('precio')
                            ->label('Precio')
                            ->disabled()
                            ->numeric()
                            ->prefix('S/. '),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')
                            ->numeric()
                            ->required()
                            ->prefix('Unidades o gramos')
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
                            ->prefix('S/. ')
                        ]),
                        Tables\Actions\Action::make('Calcular')->icon('heroicon-m-calculator')->iconButton()
                        ->url(fn (): string => route('debttotal.total', ['debt' => $this->getOwnerRecord()]))
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    TablesExportBulkAction::make(),
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

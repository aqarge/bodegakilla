<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProTypeResource\Pages;
use App\Filament\Resources\ProTypeResource\RelationManagers;
use App\Models\Pro_type;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction as TablesExportBulkAction;

class ProTypeResource extends Resource
{
    protected static ?string $model = Pro_type::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-plus';
    protected static ?string $modelLabel = 'Tipos de productos';
    protected static ?string $navigationGroup = 'Inventario de productos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_protype')->required()->label('Nombre de tipo'),
                Forms\Components\Textarea::make('descrip_protype')->label('Descripción'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_protype')->label('Nombre de tipo')
                ->searchable(),
                Tables\Columns\TextColumn::make('descrip_protype')->label('Descripcion'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProTypes::route('/'),
            'create' => Pages\CreateProType::route('/create'),
            'edit' => Pages\EditProType::route('/{record}/edit'),
        ];
    }
}

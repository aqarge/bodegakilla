<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BoxResource\Pages;
use App\Filament\Resources\BoxResource\RelationManagers;
use App\Models\Box;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BoxResource extends Resource
{
    protected static ?string $model = Box::class;

    protected static ?string $navigationIcon = 'heroicon-m-archive-box';
    protected static ?string $navigationLabel = 'Cajas diarias';
    protected static ?string $navigationGroup = 'Información de caja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('opening')->required()->label('Fecha de apertura'),
                Forms\Components\TextInput::make('income')->label('Ingresos del día'),
                Forms\Components\TextInput::make('expenses')->label('Egresos del día'),
                Forms\Components\TextInput::make('revenue')->label('Ganancia del día'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('opening')->label('Fecha de apertura'),
                Tables\Columns\TextColumn::make('income')->label('Ingresos del día'),
                Tables\Columns\TextColumn::make('expenses')->label('Egresos del día'),
                Tables\Columns\TextColumn::make('revenue')->label('Ganancia del día'),
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
            'index' => Pages\ListBoxes::route('/'),
            'create' => Pages\CreateBox::route('/create'),
            'edit' => Pages\EditBox::route('/{record}/edit'),
        ];
    }
}

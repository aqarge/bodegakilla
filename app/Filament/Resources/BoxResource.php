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
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction as TablesExportBulkAction;

class BoxResource extends Resource
{
    protected static ?string $model = Box::class;

    protected static ?string $navigationIcon = 'heroicon-m-archive-box';
    protected static ?string $modelLabel = 'Cajas diarias';
    protected static ?string $navigationGroup = 'Información de caja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('opening')->required()->unique()->label('Fecha de apertura'),
                Forms\Components\TextInput::make('income')->label('Ingresos del día')->default(0),
                Forms\Components\TextInput::make('expenses')->label('Egresos del día')->default(0),
                Forms\Components\TextInput::make('revenue')->label('Ganancia del día')->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('opening')->label('Fecha de apertura')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('income')->label('Ingresos del día'),
                Tables\Columns\TextColumn::make('expenses')->label('Egresos del día'),
                Tables\Columns\TextColumn::make('revenue')->label('Saldo del día'),
            ])
            ->filters([
                Filter::make('opening')->label('Fecha de apertura de caja')
    ->form([
        DatePicker::make('Desde'),
        DatePicker::make('Hasta'),
    ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when(
                $data['Desde'],
                fn (Builder $query, $date): Builder => $query->whereDate('opening', '>=', $date),
            )
            ->when(
                $data['Hasta'],
                fn (Builder $query, $date): Builder => $query->whereDate('opening', '<=', $date),
            );
    })
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBoxes::route('/'),
            'create' => Pages\CreateBox::route('/create'),
            //'edit' => Pages\EditBox::route('/{record}/edit'),
        ];
    }
}

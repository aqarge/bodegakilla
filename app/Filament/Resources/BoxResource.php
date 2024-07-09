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
use Illuminate\Validation\Rule;

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
                Forms\Components\DatePicker::make('opening')
                ->required()
                ->label('Fecha de apertura')
                ->rule(function ($record) {
                    return [
                        'required',
                        Rule::unique('boxes', 'opening')->ignore($record),
                    ];
                }),
                Forms\Components\TextInput::make('inbalance')->label('Saldo inicial')->prefix('S/. ')->numeric()->default(0),
                Forms\Components\TextInput::make('income')->label('Ingresos del día')->prefix('S/. ')->numeric()->default(0),
                Forms\Components\TextInput::make('expenses')->label('Egresos del día')->prefix('S/. ')->numeric()->default(0),
                Forms\Components\TextInput::make('revenue')->label('Saldo del día')->prefix('S/. ')->numeric()->default(0),
                Forms\Components\TextInput::make('tobalance')->label('Saldo total')->prefix('S/. ')->numeric()->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('opening')->label('Fecha de apertura')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('inbalance')->prefix('S/. ')->label('Saldo inicial'),
                Tables\Columns\TextColumn::make('income')->prefix('S/. ')->label('Ingresos del día'),
                Tables\Columns\TextColumn::make('expenses')->prefix('S/. ')->label('Egresos del día'),
                Tables\Columns\TextColumn::make('revenue')->prefix('S/. ')->label('Saldo del día'),
                Tables\Columns\TextColumn::make('tobalance')->prefix('S/. ')->label('Saldo total'),
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
            ->defaultSort('created_at', 'desc')
            ->actions([
                //Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    TablesExportBulkAction::make(),
                    //Tables\Actions\DeleteBulkAction::make(),
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

<?php

namespace App\Filament\Resources\DebtProductResource\Pages;

use App\Filament\Resources\DebtProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDebtProducts extends ListRecords
{
    protected static string $resource = DebtProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

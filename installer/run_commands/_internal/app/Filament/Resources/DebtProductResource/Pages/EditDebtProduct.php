<?php

namespace App\Filament\Resources\DebtProductResource\Pages;

use App\Filament\Resources\DebtProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDebtProduct extends EditRecord
{
    protected static string $resource = DebtProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

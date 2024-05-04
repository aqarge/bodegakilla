<?php

namespace App\Filament\Resources\ProTypeResource\Pages;

use App\Filament\Resources\ProTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProType extends EditRecord
{
    protected static string $resource = ProTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

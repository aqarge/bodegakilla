<?php

namespace App\Filament\Resources\TranTypeResource\Pages;

use App\Filament\Resources\TranTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranType extends EditRecord
{
    protected static string $resource = TranTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

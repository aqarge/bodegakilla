<?php

namespace App\Filament\Resources\TranTypeResource\Pages;

use App\Filament\Resources\TranTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranTypes extends ListRecords
{
    protected static string $resource = TranTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

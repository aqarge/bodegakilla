<?php

namespace App\Filament\Resources\ProTypeResource\Pages;

use App\Filament\Resources\ProTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProTypes extends ListRecords
{
    protected static string $resource = ProTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\TotaldebtResource\Pages;

use App\Filament\Resources\TotaldebtResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTotaldebts extends ListRecords
{
    protected static string $resource = TotaldebtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

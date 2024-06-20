<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Box;

class TotalBalance extends BaseWidget

{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 4;
    protected function getStats(): array

    {
        // Obtener la última caja creada
        $latestBox = Box::orderBy('opening', 'desc')->first();

        // Obtener el saldo total de la última caja
        $totalBalance = $latestBox ? $latestBox->tobalance : 0;

        return [
            Stat::make('Saldo Total Última Caja', $totalBalance)
                ->description('Saldo total correspondiente a la última caja creada')
                ->color('primary'),
        ];
    }
}

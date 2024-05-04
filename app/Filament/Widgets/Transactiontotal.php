<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Transaction;

class TransactionTotal extends BaseWidget
{
    protected function getStats(): array
    {
        // Calcular el total de ingresos
        $totalIngresos = Transaction::where('tran_types_id', '=', 1)->sum('amount_tran');

        // Calcular el total de egresos
        $totalEgresos = Transaction::where('tran_types_id', '=', 2)->sum('amount_tran');

        // Calcular el saldo
        $saldo = $totalIngresos - $totalEgresos;

        return [
            Stat::make('Ingresos', $totalIngresos),
            Stat::make('Egresos', $totalEgresos),
            Stat::make('Saldo', $saldo),
        ];
    }
    
}

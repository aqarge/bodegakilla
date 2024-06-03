<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Transaction;

class Transactiontotal extends BaseWidget
{
    protected function getStats(): array
    {
        // Calcular el total de ingresos
        $totalIngresos = Transaction::where('type_tran', 'ingreso')->sum('amount_tran');

        // Calcular el total de egresos
        $totalEgresos = Transaction::where('type_tran', 'egreso')->sum('amount_tran');

        // Calcular el saldo
        $saldoInicial = Transaction::where('type_tran', 'inicial')->sum('amount_tran');
        $saldo = $saldoInicial + $totalIngresos - $totalEgresos;

        return [
            Stat::make('Ingresos', $totalIngresos)
                ->description('Total de ingresos')
               // ->descriptionIcon('heroicon-o-trending-up')
                ->color('success'),
            Stat::make('Egresos', $totalEgresos)
                ->description('Total de egresos')
                //->descriptionIcon('heroicon-o-trending-down')
                ->color('danger'),
            Stat::make('Saldo', $saldo)
                ->description('Saldo total')
               // ->descriptionIcon('heroicon-o-cash')
                ->color('primary'),
        ];
    }
}

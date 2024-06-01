<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Totaldebt;

class TotaldebtStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Calcular el total de cuentas no pagadas
        $totalNoPagadas = Totaldebt::where('state_debt', '0')->count();

        // Calcular el total de cuentas pagadas
        $totalPagadas = Totaldebt::where('state_debt', '1')->count();

        // Calcular el monto total de cuentas no pagadas
        $montoTotalNoPagadas = Totaldebt::where('state_debt', '0')->sum('total_amount');

        return [
            Stat::make('Total No Pagadas', $totalNoPagadas)
                ->description('Cuentas no pagadas')
                //->descriptionIcon('heroicon-s-exclamation-circle')
                ->color('danger'),
            
            Stat::make('Total Pagadas', $totalPagadas)
                ->description('Cuentas pagadas')
               // ->descriptionIcon('heroicon-s-check-circle')
                ->color('success'),
            
            Stat::make('Monto Total No Pagadas', $montoTotalNoPagadas)
                ->description('Monto de cuentas no pagadas')
                //->descriptionIcon('heroicon-s-cash')
                ->color('warning'),
        ];
    }
}

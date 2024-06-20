<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Totaldebt;
use Filament\Support\Enums\IconPosition;

class TotaldebtStats extends BaseWidget
{
    protected static ?int $sort = 5;
    protected function getStats(): array
    {
        // Calcular el total de cuentas no pagadas
        $totalNoPagadas = Totaldebt::where('state_debt', '0')->count();

        // Calcular el total de cuentas pagadas
        $totalPagadas = Totaldebt::where('state_debt', '1')->count();

        // Calcular el monto total de cuentas no pagadas
        $montoTotalNoPagadas = Totaldebt::where('state_debt', '0')->sum('total_amount');

        // Calcular cuentas con montos de deuda bajo, moderado y alto
        $bajo = Totaldebt::where('risk', 'baja')->count();
        $moderado = Totaldebt::where('risk', 'moderada')->count();
        $alto = Totaldebt::where('risk', 'alta')->count();

        return [
            Stat::make('Total de Cuentas NO Pagadas', $totalNoPagadas)
                ->description('Cuentas no pagadas')
                ->descriptionIcon('heroicon-s-hand-thumb-down', IconPosition::Before)
                ->color('danger'),
            
            Stat::make('Total de Cuentas Pagadas', $totalPagadas)
                ->description('Cuentas pagadas')
                ->descriptionIcon('heroicon-s-hand-thumb-up', IconPosition::Before)
                ->color('success'),
            
            Stat::make('Monto total de Cuentas no Pagadas', $montoTotalNoPagadas)
                ->description('Monto de cuentas no pagadas')
                ->descriptionIcon('heroicon-m-arrow-trending-down', IconPosition::Before)
                ->color('warning'),

            Stat::make('Cuentas con Deuda Alta', $alto)
                ->description('Cantidad de cuentas con deuda alta. Exigir pago de cuenta!')
                ->descriptionIcon('heroicon-m-shield-exclamation', IconPosition::Before)
                ->color('danger'),

            Stat::make('Cuentas con Deuda Moderada', $moderado)
                ->description('Cantidad de cuentas con deuda moderada')
                ->descriptionIcon('heroicon-c-pause-circle', IconPosition::Before)
                ->color('warning'),

            Stat::make('Cuentas con Deuda Baja', $bajo)
                ->description('Cantidad de cuentas con deuda baja')
                ->descriptionIcon('heroicon-m-shield-check', IconPosition::Before)
                ->color('success'),

            
        ];
    }
}

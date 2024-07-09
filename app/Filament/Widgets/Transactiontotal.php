<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Box;
use Carbon\Carbon;

class Transactiontotal extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Rango de fechas por defecto (hoy)
        $today = Carbon::today();
        $endOfToday = Carbon::today()->endOfDay();

        // Aplicar el filtro de fechas a las consultas usando el campo 'opening'
        $query = Box::query()
            ->whereBetween('opening', [$today, $endOfToday]);

        // Calcular el total de ingresos
        $totalIngresos = $query->sum('income');

        // Calcular el total de egresos
        $totalEgresos = $query->sum('expenses');

        return [
            Stat::make('Ingresos', $totalIngresos)
                ->description('Total de ingresos de hoy')
                ->color('success'),
            Stat::make('Egresos', $totalEgresos)
                ->description('Total de egresos de hoy')
                ->color('danger'),
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Box;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class Transactiontotal extends BaseWidget
{
    use InteractsWithPageFilters;
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $desde = $this->filters['desde'] ?? null;
        $hasta = $this->filters['hasta'] ?? null;

        // Rango de fechas por defecto (hoy)
        $defaultDesde = Carbon::today();
        $defaultHasta = Carbon::today()->endOfDay();

        // Aplicar el filtro de fechas a las consultas usando el campo 'opening'
        $query = Box::query()
            ->when($desde, function ($query, $desde) {
                return $query->where('opening', '>=', Carbon::parse($desde));
            })
            ->when($hasta, function ($query, $hasta) {
                return $query->where('opening', '<=', Carbon::parse($hasta));
            })
            ->when(!$desde && !$hasta, function ($query) use ($defaultDesde, $defaultHasta) {
                return $query->whereBetween('opening', [$defaultDesde, $defaultHasta]);
            });

        // Calcular el total de ingresos
        $totalIngresos = $query->sum('income');

        // Calcular el total de egresos
        $totalEgresos = $query->sum('expenses');


        return [
            Stat::make('Ingresos', $totalIngresos)
                ->description('Total de ingresos del día')
                ->color('success'),
            Stat::make('Egresos', $totalEgresos)
                ->description('Total de egresos del día')
                ->color('danger'),
        ];
    }
}

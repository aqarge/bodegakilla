<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Box;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class BoxesChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Fluctuación de Cajas Diarias';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $desde = $this->filters['desde'];
        $hasta = $this->filters['hasta'];
        
        // Si no se especifican fechas, mostrar los últimos 14 días
        $defaultDesde = Carbon::today();
        $defaultHasta = Carbon::today()->addDays(4)->endOfDay();

        // Query base para obtener las cajas
        $query = Box::query()
            ->orderBy('opening')
            ->when($desde, function ($query, $desde) {
                return $query->where('opening', '>=', Carbon::parse($desde));
            })
            ->when($hasta, function ($query, $hasta) {
                return $query->where('opening', '<=', Carbon::parse($hasta));
            })
            ->when(!$desde && !$hasta, function ($query) use ($defaultDesde, $defaultHasta) {
                return $query->whereBetween('opening', [$defaultDesde, $defaultHasta]);
            });

        // Obtener los datos de las cajas según el filtro aplicado
        $boxes = $query->get();

        // Extraer las fechas, ingresos, egresos, saldos, saldo inicial, y saldo total
        $dates = $boxes->pluck('opening')->toArray();
        $inbalance = $boxes->pluck('inbalance')->toArray();
        $income = $boxes->pluck('income')->toArray();
        $expenses = $boxes->pluck('expenses')->toArray();
        $revenue = $boxes->pluck('revenue')->toArray();
        $tobalance = $boxes->pluck('tobalance')->toArray();

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $income,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                ],
                [
                    'label' => 'Egresos',
                    'data' => $expenses,
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                ],
                [
                    'label' => 'Saldo Inicial',
                    'data' => $inbalance,
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                ],
                [
                    'label' => 'Saldo del Día',
                    'data' => $revenue,
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                ],
                [
                    'label' => 'Saldo Total',
                    'data' => $tobalance,
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

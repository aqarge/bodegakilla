<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Box;

class BoxesChart extends ChartWidget
{
    protected static ?string $heading = 'Fluctuación de Cajas Diarias';

    protected function getData(): array
    {
        // Obtener los datos de las cajas ordenadas por fecha de apertura
        $boxes = Box::orderBy('opening')->get();

        // Extraer las fechas, ingresos, egresos y saldos
        $dates = $boxes->pluck('opening')->toArray();
        $income = $boxes->pluck('income')->toArray();
        $expenses = $boxes->pluck('expenses')->toArray();
        $revenue = $boxes->pluck('revenue')->toArray();

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
                    'label' => 'Saldos',
                    'data' => $revenue,
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

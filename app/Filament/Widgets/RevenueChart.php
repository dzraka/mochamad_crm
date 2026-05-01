<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Tren Pendapatan Bulanan';

    protected ?array $options = [
        'elements' => [
            'line' => [
                'tension' => 0.4,
            ],
        ],
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'grid' => [
                    'display' => false,
                ],
            ],
            'x' => [
                'grid' => [
                    'display' => false,
                ],
            ],
        ],
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');

            $query = Project::where('status', 'approved')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month);

            if (auth()->user()->isSales()) {
                $query->where('user_id', auth()->id());
            }

            $revenue = $query->sum('total_price');
            $data[] = $revenue;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Revenue',
                    'data' => $data,
                    'borderColor' => '#4F46E5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

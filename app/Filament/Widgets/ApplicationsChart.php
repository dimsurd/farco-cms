<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use Filament\Widgets\ChartWidget;

class ApplicationsChart extends ChartWidget
{
    protected static ?string $heading = 'Applications Received (This Year)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Simple manual aggregation to be DB-agnostic
        $applications = Application::whereYear('created_at', now()->year)->get();
        
        $data = collect(range(1, 12))->map(function ($month) use ($applications) {
            return $applications->filter(function ($app) use ($month) {
                return $app->created_at->month === $month;
            })->count();
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Applications',
                    'data'  => $data,
                    'borderColor' => '#f59e0b', // Amber-500
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)', // Amber with opacity
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

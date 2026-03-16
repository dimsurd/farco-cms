<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\Department;
use Filament\Widgets\ChartWidget;

class ApplicationsByDepartmentChart extends ChartWidget
{
    protected static ?string $heading = 'Applications by Department';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Fetch all departments
        $departments = Department::withCount(['occupancies as total_applications' => function ($query) {
            $query->join('applications', 'applications.occupancy_id', '=', 'occupancies.id');
        }])->get();

        // Separate labels and data
        $labels = [];
        $data   = [];

        // Beautiful curated modern dashboard colors
        $colors = [
            '#f59e0b', // Amber
            '#3b82f6', // Blue
            '#10b981', // Emerald
            '#ef4444', // Red
            '#8b5cf6', // Violet
            '#ec4899', // Pink
            '#0ea5e9', // Sky
        ];

        foreach ($departments as $department) {
            // Count total applications joined through occupancies belonging to department
            // Let's do it safely in PHP if SQL relations are tricky, but SQL count is better
            // OR Simple distinct query:
            $appCount = Application::whereHas('occupancy', function ($q) use ($department) {
                $q->where('department_id', $department->id);
            })->count();

            if ($appCount > 0) {
                $labels[] = $department->name;
                $data[]   = $appCount;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Applications',
                    'data'  => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        // A nice visual doughnut
        return 'doughnut';
    }
}

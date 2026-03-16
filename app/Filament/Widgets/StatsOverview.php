<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\Occupancy;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Make it load first
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Simple counts
        $totalJobs = Occupancy::count();
        $activeJobs = Occupancy::where('status', 'active')->count();
        
        $totalApps = Application::count();
        $appsThisMonth = Application::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count();

        return [
            Stat::make('Active Job Postings', $activeJobs)
                ->description('Out of ' . $totalJobs . ' total positions')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color($activeJobs > 0 ? 'success' : 'danger'),

            Stat::make('Total Applications', $totalApps)
                ->description('All time applicant submissions')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Applications This Month', $appsThisMonth)
                ->description('Candidates submitted in ' . now()->format('F'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($appsThisMonth > 0 ? 'success' : 'gray'),
        ];
    }
}

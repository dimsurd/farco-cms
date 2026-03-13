<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Resources\Pages\ListRecords;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_csv')
                ->label('Export to CSV')
                ->icon('heroicon-o-document-arrow-down')
                ->color('primary')
                ->action(function () {
                    $records = $this->getFilteredTableQuery()->get();
                    $filename = 'applications_' . now()->format('Y-m-d_H-i-s') . '.csv';

                    return response()->streamDownload(function () use ($records) {
                        $handle = fopen('php://output', 'w');
                        fputcsv($handle, ['ID', 'Job Position', 'Applicant Name', 'Email', 'Phone', 'Message', 'File URL', 'Submitted At']);
                        
                        foreach ($records as $record) {
                            fputcsv($handle, [
                                $record->id,
                                $record->occupancy?->job_title,
                                $record->full_name,
                                $record->email,
                                $record->phone_number,
                                $record->message,
                                asset('storage/' . $record->file_path),
                                $record->created_at,
                            ]);
                        }
                        fclose($handle);
                    }, $filename, ['Content-Type' => 'text/csv']);
                }),
        ];
    }
}

<?php

namespace App\Filament\Exports;

use App\Models\Application;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ApplicationExporter extends Exporter
{
    protected static ?string $model = Application::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('occupancy.job_title')
                ->label('Job Position'),
            ExportColumn::make('full_name')
                ->label('Applicant Name'),
            ExportColumn::make('email')
                ->label('Email Address'),
            ExportColumn::make('phone_number')
                ->label('Phone Number'),
            ExportColumn::make('message')
                ->label('Message'),
            ExportColumn::make('file_path')
                ->label('File URL')
                ->formatStateUsing(fn ($state) => asset('storage/' . $state)),
            ExportColumn::make('created_at')
                ->label('Submitted At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your application export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}

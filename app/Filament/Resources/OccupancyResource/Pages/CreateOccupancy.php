<?php

namespace App\Filament\Resources\OccupancyResource\Pages;

use App\Filament\Resources\OccupancyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOccupancy extends CreateRecord
{
    protected static string $resource = OccupancyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        return $data;
    }
}

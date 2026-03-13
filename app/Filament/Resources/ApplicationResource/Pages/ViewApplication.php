<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Applicant Details')
                ->schema([
                    TextEntry::make('occupancy.job_title')->label('Applied Position'),
                    TextEntry::make('full_name'),
                    TextEntry::make('email'),
                    TextEntry::make('phone_number'),
                    TextEntry::make('created_at')->dateTime(),
                ])->columns(2),

            Section::make('Message')
                ->schema([
                    TextEntry::make('message')->columnSpanFull()->placeholder('No message provided.'),
                ]),

            Section::make('Attachment')
                ->schema([
                    TextEntry::make('file_path')
                        ->label('File')
                        ->formatStateUsing(fn ($state) => basename($state))
                        ->url(fn ($state) => asset('storage/' . $state), shouldOpenInNewTab: true),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

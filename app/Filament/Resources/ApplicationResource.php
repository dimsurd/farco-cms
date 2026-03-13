<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Careers Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('occupancy_id')
                ->label('Job Position')
                ->relationship('occupancy', 'job_title')
                ->searchable()
                ->required()
                ->columnSpanFull(),

            Forms\Components\TextInput::make('full_name')->required()->maxLength(255),
            Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
            Forms\Components\TextInput::make('phone_number')->tel()->required()->maxLength(50),

            Forms\Components\Textarea::make('message')->rows(3)->columnSpanFull(),

            Forms\Components\TextInput::make('file_path')
                ->label('File Path')
                ->disabled()
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('occupancy.job_title')
                    ->label('Job Position')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone_number')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('occupancy')
                    ->relationship('occupancy', 'job_title')
                    ->label('Job Position'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Submitted From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Submitted Until'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    \Filament\Tables\Actions\BulkAction::make('export_csv')
                        ->label('Export Selected to CSV')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
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
                        })
                ]),

            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canCreate(): bool
    {
        return false; // Applications come only from the API
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'view'  => Pages\ViewApplication::route('/{record}'),
        ];
    }
}

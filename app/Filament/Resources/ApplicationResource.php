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
                    ->relationship('occupancy', 'job_title'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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

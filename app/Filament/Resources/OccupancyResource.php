<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OccupancyResource\Pages;
use App\Models\Department;
use App\Models\Location;
use App\Models\Occupancy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OccupancyResource extends Resource
{
    protected static ?string $model = Occupancy::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Careers';

    protected static ?string $navigationGroup = 'Careers Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Job Information')
                ->schema([
                    Forms\Components\TextInput::make('job_title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('department_id')
                        ->label('Department')
                        ->options(Department::pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('location_id')
                        ->label('Location')
                        ->options(Location::pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('work_mode')
                        ->options([
                            'On-site' => 'On-site',
                            'Hybrid'  => 'Hybrid',
                            'Remote'  => 'Remote',
                        ])
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'draft'    => 'Draft',
                            'active'   => 'Active',
                            'inactive' => 'Inactive',
                        ])
                        ->default('draft')
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Job Description')
                ->schema([
                    Forms\Components\RichEditor::make('job_description')
                        ->required()
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'blockquote', 'bold', 'bulletList', 'codeBlock', 'h2', 'h3',
                            'italic', 'link', 'orderedList', 'redo', 'strike', 'underline', 'undo',
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('job_title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('location.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('work_mode')
                    ->colors([
                        'success' => 'Remote',
                        'warning' => 'Hybrid',
                        'primary' => 'On-site',
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger'  => 'inactive',
                        'warning' => 'draft',
                    ]),

                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft'    => 'Draft',
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('work_mode')
                    ->label('Work Mode')
                    ->options([
                        'On-site' => 'On-site',
                        'Hybrid'  => 'Hybrid',
                        'Remote'  => 'Remote',
                    ]),
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOccupancies::route('/'),
            'create' => Pages\CreateOccupancy::route('/create'),
            'edit'   => Pages\EditOccupancy::route('/{record}/edit'),
        ];
    }
}

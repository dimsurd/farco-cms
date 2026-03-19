<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApiKeyResource\Pages;
use App\Models\ApiKey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApiKeyResource extends Resource
{
    protected static ?string $model = ApiKey::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'API Keys';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Key Name')
                ->required()
                ->placeholder('e.g., Webflow Frontend')
                ->maxLength(255)
                ->columnSpanFull(),

            Forms\Components\TextInput::make('key')
                ->label('API Key')
                ->disabled()
                ->dehydrated(false)
                ->placeholder('Auto-generated on creation')
                ->helperText('Copy the key from the table list using the copy icon.')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('allowed_domain')
                ->label('Allowed Domain')
                ->placeholder('e.g., yoursite.webflow.io')
                ->helperText('Leave empty to allow all domains.')
                ->maxLength(255),

            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->label('API Key')
                    ->copyable()
                    ->copyMessage('API key copied!')
                    ->fontFamily('mono')
                    ->formatStateUsing(fn(string $state): string => substr($state, 0, 8) . '••••••••••••••••' . substr($state, -6)),

                Tables\Columns\TextColumn::make('allowed_domain')
                    ->label('Allowed Domain')
                    ->default('All domains')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Last Used')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ? $state->diffForHumans() : 'Never'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiKeys::route('/'),
            'create' => Pages\CreateApiKey::route('/create'),
            'edit' => Pages\EditApiKey::route('/{record}/edit'),
        ];
    }
}

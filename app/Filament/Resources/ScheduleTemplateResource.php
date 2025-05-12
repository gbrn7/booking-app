<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleTemplateResource\Pages;
use App\Filament\Resources\ScheduleTemplateResource\RelationManagers;
use App\Models\ScheduleTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ScheduleTemplateResource extends Resource
{
    protected static ?string $model = ScheduleTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?string $navigationLabel = 'Template  Jadwal';

    protected static ?string $modelLabel = 'Template  Jadwal ';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->placeholder('Masukkan nama template jadwal, ex: Senin')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->label('Nama Template'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Template'),
                Tables\Columns\TextColumn::make('schedule_template_details_count')
                    ->label('Jumlah Kelas')
                    ->counts('scheduleTemplateDetails'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Tamplate tidak ditemukan');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ScheduleTemplateDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScheduleTemplates::route('/'),
            'edit' => Pages\EditScheduleTemplate::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Filament\Resources\SchedulesResource\Pages\ViewSchedules;
use App\Models\Schedule;
use App\Models\ScheduleTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Jadwal Kelas ';

    protected static ?string $modelLabel = 'Jadwal Kelas';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('date')
                    ->required()
                    ->label('Hari/Tanggal')
                    ->locale('id')
                    ->unique()
                    ->displayFormat('l, d F Y')
                    ->native(false),
                Forms\Components\Select::make('template_id')
                    ->label('Template')
                    ->placeholder('Custom')
                    ->visible(fn($livewire) => !($livewire instanceof EditRecord))
                    ->options(function () {
                        return ScheduleTemplate::all()
                            ->mapWithKeys(function ($scheduleTemplate) {
                                return [
                                    $scheduleTemplate->id => $scheduleTemplate->name
                                ];
                            });
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->label('Hari/Tanggal')->dateTime('l, d F Y'),
                Tables\Columns\TextColumn::make('schedule_details_count')
                    ->label('Jumlah Kelas')
                    ->counts('scheduleDetails'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Jadwal tidak ditemukan');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ScheduleDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'view' => ViewSchedules::route('/{record}'),
            'index' => Pages\ListSchedules::route('/'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}

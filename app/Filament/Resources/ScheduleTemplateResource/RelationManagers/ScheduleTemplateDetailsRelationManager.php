<?php

namespace App\Filament\Resources\ScheduleTemplateResource\RelationManagers;

use App\Models\Classes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ScheduleTemplateDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'scheduleTemplateDetails';

    public function getTableHeading(): string
    {
        return 'Jadwal Template'; // Custom heading
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('class_id')
                    ->required()
                    ->options(function () {
                        return Classes::with(['classType', 'groupClassType'])
                            ->get()
                            ->mapWithKeys(function ($class) {
                                return [
                                    $class->id => $class->name . ' - ' . $class->groupClassType->name . ' - ' .
                                        $class->classType->name
                                ];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->label('Kelas'),
                Forms\Components\TextInput::make('quota')
                    ->required()
                    ->numeric()
                    ->label('Kuota'),
                Forms\Components\TimePicker::make('schedule_time')
                    ->required()
                    ->label('Jam')
                    ->displayFormat('H:i')
                    ->native(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('classes.name')->label('Nama Kelas'),
                Tables\Columns\TextColumn::make('classes.groupClassType.name')->label('Tipe Kelas'),
                Tables\Columns\TextColumn::make('classes.classType.name')->label('Jenis Kelas'),
                Tables\Columns\TextColumn::make('classes.instructure_name')->label('Nama Instruktur'),
                Tables\Columns\TextColumn::make('quota')->label('Kuota'),
                Tables\Columns\TextColumn::make('schedule_time')->label('Jam')->dateTime('H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_id')
                    ->relationship(name: 'classes', titleAttribute: 'name')
                    ->label('Kelas'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Jadwal Template')
                    ->modalHeading('Tambah Jadwal Template'),
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
            ->emptyStateHeading('Jadwal tidak ditemukan');
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassesResource\Pages;
use App\Filament\Resources\ClassesResource\RelationManagers;
use App\Models\Classes;
use App\Models\PackageSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;


class ClassesResource extends Resource
{
    protected static ?string $model = Classes::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kelas';

    protected static ?string $modelLabel = 'Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama'),
                Forms\Components\Select::make('class_type_id')
                    ->required()
                    ->relationship(name: 'classType', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->label('Jenis Kelas'),
                Forms\Components\Select::make('group_class_type_id')
                    ->required()
                    ->relationship(name: 'groupClassType', titleAttribute: 'name')
                    ->preload()
                    ->label('Tipe Kelas'),
                Forms\Components\TextInput::make('instructure_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Instruktur'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')->label('Nama'),
                TextEntry::make('classType.name')->label('Jenis Kelas'),
                TextEntry::make('groupClassType.name')->label('Tipe Kelas'),
                TextEntry::make('instructure_name')->label('Nama Instruktur'),
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
                Tables\Columns\TextColumn::make('name')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('classType.name')->label('Jenis Kelas'),
                Tables\Columns\TextColumn::make('groupClassType.name')->label('Tipe Kelas'),
                Tables\Columns\TextColumn::make('instructure_name')->searchable()->label('Nama Instruktur'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_type_id')
                    ->relationship(name: 'classType', titleAttribute: 'name')
                    ->label('Jenis Kelas'),
                Tables\Filters\SelectFilter::make('group_class_type_id')
                    ->relationship(name: 'groupClassType', titleAttribute: 'name')
                    ->label('Tipe Kelas'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Kelas Dihapus')
                            ->body(
                                'Data Kelas Berhasil Dihapus'
                            )
                    )
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Kelas tidak ditemukan');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClasses::route('/'),
            'create' => Pages\CreateClasses::route('/create'),
            'edit' => Pages\EditClasses::route('/{record}/edit'),
        ];
    }
}

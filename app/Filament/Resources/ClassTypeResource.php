<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassTypeResource\Pages;
use App\Filament\Resources\ClassTypeResource\RelationManagers;
use App\Models\ClassType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClassTypeResource extends Resource
{
    protected static ?string $model = ClassType::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationLabel = 'Jenis Kelas';

    protected static ?string $modelLabel = 'Jenis Kelas';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama'),
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
                Tables\Columns\TextColumn::make('classes_count')
                    ->label('Jumlah Kelas')
                    ->counts('classes'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Jenis Kelas tidak ditemukan');
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
            'index' => Pages\ListClassTypes::route('/'),
            'create' => Pages\CreateClassType::route('/create'),
            'edit' => Pages\EditClassType::route('/{record}/edit'),
        ];
    }
}

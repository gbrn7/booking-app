<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Paket Kelas';

    protected static ?string $modelLabel = 'Paket Kelas';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('class_id')
                    ->required()
                    ->relationship(name: 'classes', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->label('Kelas'),
                Forms\Components\TextInput::make('number_of_session')
                    ->required()
                    ->numeric()
                    ->label('Jumlah Sesi'),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->label('Harga')
                    ->numeric(),
                Forms\Components\Checkbox::make('is_trial')
                    ->label('Status Trial'),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->label('Durasi Valid')
                    ->numeric(),
                Forms\Components\Select::make('unit')
                    ->options([
                        'day' => 'Hari',
                        'week' => 'Minggu',
                        'month' => 'Bulan',
                        'year' => 'Tahun',
                    ])
                    ->label('Satuan Durasi Valid')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classes.name')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('number_of_session')->label('Jumlah Sesi'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),
                Tables\Columns\IconColumn::make('is_trial')
                    ->label('Status Trial')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),
                Tables\Columns\TextColumn::make('duration')->label('Durasi'),
                Tables\Columns\TextColumn::make('duration_unit')
                    ->label('Satuan')
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'day' => 'Hari',
                        'week' => 'Minggu',
                        'month' => 'Bulan',
                        'year' => 'Tahun',
                        default => 'Minggu',
                    }),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_id')
                    ->relationship(name: 'classes', titleAttribute: 'name')
                    ->label('Kelas'),
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
            ->emptyStateHeading('Paket tidak ditemukan');;
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}

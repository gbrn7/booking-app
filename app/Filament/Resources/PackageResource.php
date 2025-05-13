<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\ClassType;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Paket Kelas';

    protected static ?string $modelLabel = 'Paket Kelas';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('class_type_id')
                    ->required()
                    ->options(function () {
                        return ClassType::with('groupClassType')
                            ->get()
                            ->mapWithKeys(function ($classType) {
                                return [
                                    $classType->id => $classType->name . ' - ' . $classType->groupClassType->name
                                ];
                            });
                    })
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
                Tables\Columns\TextColumn::make('classType.name')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('classType.groupClassType.name')->label('Tipe Kelas'),
                Tables\Columns\TextColumn::make('number_of_session')->label('Jumlah Sesi'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),
                Tables\Columns\IconColumn::make('is_trial')
                    ->label('Status Trial')
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('info'),
                Tables\Columns\TextColumn::make('duration')->label('Durasi'),
                Tables\Columns\TextColumn::make('duration_unit')
                    ->label('Satuan Durasi')
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'day' => 'Hari',
                        'week' => 'Minggu',
                        'month' => 'Bulan',
                        'year' => 'Tahun',
                        default => 'Minggu',
                    }),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_type_id')
                    ->relationship(name: 'classType', titleAttribute: 'name')
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
            ->emptyStateHeading('Paket tidak ditemukan');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('classType.name')->label('Nama'),
                TextEntry::make('number_of_session')->label('Jumlah Sesi'),
                TextEntry::make('price')->label('Harga'),
                IconEntry::make('is_trial')
                    ->boolean()
                    ->label('Status Trial')
                    ->trueColor('warning')
                    ->falseColor('info'),
                TextEntry::make('duration')->label('Durasi'),
                TextEntry::make('duration_unit')->label('Satuan Durasi'),
            ]);
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

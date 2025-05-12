<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageTransactionResource\Pages;
use App\Filament\Resources\PackageTransactionResource\RelationManagers;
use App\Models\Package;
use App\Models\PackageTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PackageTransactionResource extends Resource
{
    protected static ?string $model = PackageTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Member ';

    protected static ?string $modelLabel = 'Member ';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('payment_status', 'success')->orderBy('id', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('package_id')
                    ->required()
                    ->options(function () {
                        return Package::with('classes')
                            ->get()
                            ->mapWithKeys(function ($package) {
                                return [
                                    $package->id => $package->classes->name . ' - ' . $package->number_of_session . 'x Sesi - IDR ' . number_format($package->price, 0, ".", ".") . " - Valid untuk " . $package->duration . " " . $package->localDurationUnit() . " Setelah kelas pertama"
                                ];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->label('Paket'),
                Forms\Components\TextInput::make('customer_name')
                    ->required()
                    ->placeholder('Masukkan nama customer')
                    ->maxLength(255)
                    ->label('Nama Customer'),
                Forms\Components\TextInput::make('phone_num')
                    ->required()
                    ->placeholder('Masukkan nomor telepon customer')
                    ->maxLength(255)
                    ->label('Nomor WA Customer'),
                Forms\Components\TextInput::make('email')
                    ->maxLength(255)
                    ->placeholder('Masukkan email customer')
                    ->label('Email Customer'),
                Forms\Components\Select::make('payment_status')
                    ->required()
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'failure' => 'Gagal',
                        'success' => 'Sukses',
                    ])
                    ->default('success')
                    ->selectablePlaceholder(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('class_name')->label('Nama Kelas'),
                Tables\Columns\TextColumn::make('customer_name')->label('Nama Member'),
                Tables\Columns\TextColumn::make('phone_num')->label('Nomor Wa'),
                Tables\Columns\TextColumn::make('number_of_session')->label('Jumlah Sesi'),
                Tables\Columns\TextColumn::make('number_of_session_left')->label('Sisa Sesi'),
                Tables\Columns\TextColumn::make('instructure_name')->label('Nama Instruktur'),
                Tables\Columns\TextColumn::make('redeem_code')->label('Kode Redeem'),
                Tables\Columns\TextColumn::make('valid_until')->label('Kadaluarsa')->default('-'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->emptyStateHeading('Member tidak ditemukan');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PackageSchedulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackageTransactions::route('/'),
            'create' => Pages\CreatePackageTransaction::route('/create'),
            'view' => Pages\ViewPackageTransaction::route('/{record}'),
            'edit' => Pages\EditPackageTransaction::route('/{record}/edit'),
        ];
    }
}

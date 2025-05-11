<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\PackageTransaction;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $model = PackageTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = "Transaksi ";

    protected static ?string $modelLabel = "Transaksi ";

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_name')
                    ->required()
                    ->placeholder('Masukkan nama customer')
                    ->maxLength(255)
                    ->label('Nama Customer'),
                Forms\Components\TextInput::make('phone_num')
                    ->required()
                    ->placeholder('Masukkan no WA customer')
                    ->maxLength(255)
                    ->label('Nama Customer'),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->placeholder('Masukkan email customer')
                    ->maxLength(255)
                    ->label('Nama Customer'),
                Forms\Components\Select::make('payment_status')
                    ->required()
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'failure' => 'Gagal',
                        'success' => 'Sukses',
                    ])
                    ->default('pending')
                    ->selectablePlaceholder(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('phone_num')->searchable()->label('Nomor Telepon'),
                Tables\Columns\TextColumn::make('email')->searchable()->label('Email'),
                Tables\Columns\IconColumn::make('payment_status')
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-s-minus',
                        'failure' => 'heroicon-o-x-mark',
                        'success' => 'heroicon-o-check-badge',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'failure' => 'danger',
                        'success' => 'success',
                        default => 'gray',
                    })
                    ->label('Status Pembayaran'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        "pending" => "Pending",
                        "failure" => "Gagal",
                        "success" => "Sukses",
                    ])
                    ->label('Status Pembayaran'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}

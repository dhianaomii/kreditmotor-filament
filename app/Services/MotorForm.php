<?php

namespace App\Services;

use FIlament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Fieldset;


final class MotorForm{
    public static function schema(): array {
        return [
            Fieldset::make()->schema([
                TextInput::make('nama_motor')
                ->label('Nama Motor')
                ->required(),
            Select::make('jenis_motor_id')
                ->label('Jenis Motor')
                ->relationship('JenisMotor', 'jenis')
                ->required(),
            TextInput::make('harga_jual')
                ->label('Harga Jual')
                ->numeric()
                ->prefix('Rp')
                ->required(),
                // ->formatStateUsing(fn ($state) => number_format((float) $state, 2, '.', ',')),
            TextInput::make('deskripsi_motor')
                ->label('Deskripsi Motor')
                ->required(),
            TextInput::make('warna')
                ->label('Warna')
                ->required()
                ->maxLength(50),
            TextInput::make('kapasitas_mesin')
                ->label('Kapasitas Mesin')
                ->required(),
            Select::make('tahun_produksi')
                ->label('Tahun Produksi')
                ->options(array_combine(range(date('Y'), 1990), range(date('Y'), 1990)))
                ->required(),
            TextInput::make('stok')
                ->label('Stok')
                ->numeric()
                ->required(),
            FileUpload::make('foto1')
                ->label('Foto Utama')
                ->image()
                ->disk('public')
                ->directory('photos')
                ->visibility('public')
                ->required(),
            FileUpload::make('foto2')
                ->label('Foto Tambahan 1')
                ->image()
                ->disk('public')
                ->directory('photos')
                ->visibility('public'),
            FileUpload::make('foto3')
                ->label('Foto Tambahan 2')
                ->image()
                ->disk('public')
                ->directory('photos')
                ->visibility('public'),
            ]),
        ];
    }
}
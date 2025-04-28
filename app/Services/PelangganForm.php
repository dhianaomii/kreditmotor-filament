<?php

namespace App\Services;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle; // Tambahkan ini

final class PelangganForm
{
    public static function schema(): array
    {
        return [
            Fieldset::make()->schema([
                TextInput::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->required(),
                    Toggle::make('is_blocked') // Tambahkan field ini
                    ->label('Blokir Pelanggan')
                    ->default(false)
                    ->helperText('Aktifkan untuk memblokir pelanggan agar tidak bisa login.'),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->unique(table: 'pelanggans', ignoreRecord: true),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    // ->required()
                    ->hiddenOn('edit'), // Sembunyikan password saat edit
                TextInput::make('no_hp')
                    ->label('Nomor HP')
                    ->required()
                    ->numeric(),
                Grid::make(1)->schema([
                    FileUpload::make('foto')
                        ->label('Foto Pelanggan')
                        ->image()
                        ->disk('public')
                        ->directory('photos')
                        ->visibility('public'),
                ]),
                Fieldset::make('Alamat 1')->schema([
                    TextInput::make('alamat1')
                        ->label('Alamat'),
                    TextInput::make('kota1')
                        ->label('Kota'),
                    TextInput::make('provinsi1')
                        ->label('Provinsi'),
                    TextInput::make('kode_pos1')
                        ->label('Kode Pos'),
                ]),
                Fieldset::make('Alamat 2 (Opsional)')->schema([
                    TextInput::make('alamat2')
                        ->label('Alamat'),
                    TextInput::make('kota2')
                        ->label('Kota'),
                    TextInput::make('provinsi2')
                        ->label('Provinsi'),
                    TextInput::make('kode_pos2')
                        ->label('Kode Pos'),
                ]),
                Fieldset::make('Alamat 3 (Opsional)')->schema([
                    TextInput::make('alamat3')
                        ->label('Alamat'),
                    TextInput::make('kota3')
                        ->label('Kota'),
                    TextInput::make('provinsi3')
                        ->label('Provinsi'),
                    TextInput::make('kode_pos3')
                        ->label('Kode Pos'),
                ]),
            ])
        ];
    }
}
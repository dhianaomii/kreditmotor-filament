<?php

namespace App\Services;

use FIlament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;


final class PengirimanForm{

    public static function schema(): array {
        return [
            Fieldset::make()->schema([
                TextInput::make('no_invoice')
                    ->label('Nomor Invoice')
                    ->required(),
                Select::make('kredit_id')
                    ->label('Status Kredit')
                    ->relationship('Kredit', 'status_kredit')
                    ->required(),
                DatePicker::make('tgl_kirim')
                    ->label('Tanggal Pengiriman')
                    ->native(false) // Gunakan date picker bawaan Filament, bukan browser
                    ->displayFormat('d-m-Y') // Format tampilan di form
                    ->format('Y-m-d') // Format penyimpanan di database
                    ->required(),
                DatePicker::make('tgl_tiba')
                    ->label('Tanggal Tiba')
                    ->native(false) // Gunakan date picker bawaan Filament, bukan browser
                    ->displayFormat('d-m-Y') // Format tampilan di form
                    ->format('Y-m-d') // Format penyimpanan di database
                    ->required(),
                Select::make('status_kirim')
                    ->label('Status Pengiriman')
                    ->options([
                        'Sedang Dikirim' => 'Sedang Dikirim',
                        'Tiba Ditujuan' => 'Tiba Ditujuan',
                    ]),
                TextInput::make('nama_kurir')
                    ->label('Nama Kurir')
                    ->required(),
                TextInput::make('telpon_kurir')
                    ->label('Telepon Kurir')
                    ->required(),
                TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->required(),
                Grid::make(1)->schema([        
                    FileUpload::make('bukti_foto')
                        ->label('Bukti Foto')
                        ->image()
                        ->disk('public')
                        ->directory('photos')
                        ->visibility('public'),
                ])
            ])
        ];
    }
}
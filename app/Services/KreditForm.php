<?php

namespace App\Services;

use Faker\Provider\Image;
use FIlament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;

final class KreditForm{

    public static function schema(): array {
        return [
            Fieldset::make()->schema([
                Select::make('pengajuan_kredit_id')
                    ->label('Pengajuan Kredit')
                    ->relationship('PengajuanKredit', 'id')
                    // ->relationship('PengajuanKredit.pelanggan', 'nama_pelanggan')
                    // ->getRelationship('Pelanggan', 'nama_pelanggan')
                    ->required(),
                Select::make('metode_pembayaran_id')
                    ->relationship('MetodePembayaran', 'metode_pembayaran')
                    ->required()
                    ->label('Metode Pembayaran'),
                DatePicker::make('tgl_mulai_kredit')
                    ->label('Tanggal Mulai Kredit')
                    ->native(false) // Gunakan date picker bawaan Filament, bukan browser
                    ->displayFormat('d-m-Y') // Format tampilan di form
                    ->format('Y-m-d') // Format penyimpanan di database
                    ->required(),
                DatePicker::make('tgl_selesai_kredit')
                    ->label('Tanggal Selesai Kredit')
                    ->native(false) // Gunakan date picker bawaan Filament, bukan browser
                    ->displayFormat('d-m-Y') // Format tampilan di form
                    ->format('Y-m-d') // Format penyimpanan di database
                    ->required(),
                FileUpload::make('url_bukti_bayar')
                    ->label('Bukti Bayar DP')
                    ->image()
                    ->disk('public')
                    ->directory('bukti_bayar')
                    ->visibility('public'),
                TextInput::make('sisa_kredit')
                    ->label('Sisa Kredit')
                    ->numeric()
                    ->step('0.01')
                    ->required(),
                Select::make('status_kredit')
                    ->label('Status Kredit')
                    ->options([
                        'Dicicil' => 'Dicicil',
                        'Macet' => 'Macet',
                        'Lunas' => 'Lunas',
                    ])
                    ->required(),
                TextInput::make('keterangan_status_kredit')
                    ->label('Keterangan Status Kredit')
                    ->required(),
            ])
        ];
    }
}
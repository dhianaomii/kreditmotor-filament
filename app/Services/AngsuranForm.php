<?php

namespace App\Services;

use FIlament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;


final class AngsuranForm {

    public static function schema(): array {
        return [
            Fieldset::make()->schema([
                Select::make('kredit_id')
                    ->label('Status Kredit')
                    ->relationship('Kredit', 'status_kredit')
                    ->required(),
                DatePicker::make('tgl_bayar')
                    ->label('Tanggal Pembayaran')
                    ->native(false) // Gunakan date picker bawaan Filament, bukan browser
                    ->displayFormat('d-m-Y') // Format tampilan di form
                    ->format('Y-m-d') // Format penyimpanan di database
                    ->required(),
                TextInput::make('angsuran_ke')
                    ->label('Angsuran Ke')
                    ->numeric()
                    ->required(),
                TextInput::make('total_bayar')
                    ->label('Total Pembayaran')
                    ->numeric()
                    ->step('0.01')
                    ->prefix('Rp') // Optional
                    ->inputMode('decimal'),
                TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->required()
                    ->maxLength(255),
            ])
        ];
    }
}
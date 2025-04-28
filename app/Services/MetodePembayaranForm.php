<?php

namespace App\Services;

use FIlament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;


final class MetodePembayaranForm {

    public static function schema(): array {
        return [
            FIeldset::make()->schema([
                TextInput::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->required(),
                TextInput::make('tempat_bayar')
                    ->label('Tempat Bayar'),
                TextInput::make('no_rekening')
                    ->label('No Rekening'),
                FileUpload::make('logo')
                    ->label('Foto')
                    ->image()
                    ->disk('public')
                    ->directory('photos')
                    ->visibility('public'),
            ])
        ];
    }
}
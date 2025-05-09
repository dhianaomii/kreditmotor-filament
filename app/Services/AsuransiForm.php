<?php

namespace App\Services;

use FIlament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;


final class AsuransiForm{

    public static function schema(): array {
        return [
            Fieldset::make()->schema([
                TextInput::make('nama_perusahaan_asuransi')
                    ->label('Nama Perusahaan Asuransi')
                    ->required(),
                TextInput::make('nama_asuransi')
                    ->label('Nama Asuransi')
                    ->required(),
                TextInput::make('margin_asuransi')
                    ->label('Margin Asuransi')
                    ->numeric()
                    ->step('0.01')
                    ->suffix('%'),
                    // ->prefix('Rp') // Optional
                    // ->inputMode('decimal'),
                TextInput::make('no_rekening')
                    ->label('Nomor Rekening')
                    ->required(),
                Grid::make(1)->schema([    
                    FileUpload::make('logo')
                        ->label('Logo Perusahaan')
                        ->image()
                        ->disk('public')
                        ->directory('asuransi')
                        ->visibility('public'),
                ])
            ])
                ];
    }
}
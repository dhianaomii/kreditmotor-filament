<?php

namespace App\Services;

use FIlament\Forms;
use Filament\Forms\Components\TextInput;

use Filament\Forms\Components\Fieldset;


final class JenisCicilanForm {

    public static function schema(): array {
        return [
            Fieldset::make()->schema([
                TextInput::make('jenis_cicilan')
                    ->label('Jenis Cicilan')
                    ->required(),
                TextInput::make('lama_cicilan')
                    ->label('Lama Cicilan')
                    ->required()
                    ->numeric()
                    ->suffix('Bulan'),
                TextInput::make('margin_kredit')
                    ->label('Margin Kredit')
                    ->numeric()
                    ->step('0.01')
                    ->suffix('%'),
                    // ->prefix('Rp') // Optional
                    // ->inputMode('decimal'),
                TextInput::make('dp')
                    ->label('Dp')
                    ->numeric()
                    ->step('0.01')
                    ->suffix('%'),
                    // ->prefix('Rp') // Optional
            ]) 
    ];
    }
}
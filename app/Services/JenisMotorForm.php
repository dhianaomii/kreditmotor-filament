<?php

namespace App\Services;

use FIlament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;


final class JenisMotorForm {
    public static function schema(): array {
        return [
            TextInput::make('merk')
                ->required()
                ->maxLength(255)
                ->label('Merk Motor'),
            Select::make('jenis')
                ->options([
                    'Bebek' => 'Bebek',
                    'Skuter' => 'Skuter', 
                    'Dual Sport' => 'Dual Sport', 
                    'Naked Sport' => 'Naked Sport',
                    'Sport Bike' => 'Sport Bike',
                    'Retro' => 'Retro', 
                    'Cruiser' => 'Cruiser',
                    'Sport Touring' => 'Sport Touring',
                    'Dirt Bike' => 'Dirt Bike', 
                    'Motorcross' => 'Motorcross', 
                    'Scrambler' => 'Scrambler',
                    'ATV' => 'ATV',
                    'Motor Adventure' => 'Motor Adventure',
                    'Lainnya' => 'Lainnya'
                ])
                ->required()
                ->label('Jenis Motor'),
            TextInput::make('deskripsi_jenis')
                ->required()
                ->maxLength(255)
                ->label('Deskripsi Jenis Motor'),
            FileUpload::make('image')
                ->image()
                ->disk('public')
                ->directory('jenismotor')
                ->visibility('public')
                ->required()
                ->label('Foto'),
                ];
    }
}
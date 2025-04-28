<?php

namespace App\Services;

use FIlament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;


final class UserForm{

    public static function schema(): array {
        return [
            Fieldset::make()->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()                        
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'ceo' => 'CEO',
                        'marketing' => 'Marketing',
                        'kurir' => 'Kurir', 
                    ])
                    ->required(),
           ])
        ];
    }
}
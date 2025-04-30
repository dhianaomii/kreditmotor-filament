<?php

namespace App\Services;

use FIlament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;


final class BlogForm{

    public static function schema(): array {
        return [
            Fieldset::make()->schema([
                Select::make('user_id')
                    ->label('Penulis Konten')
                    ->relationship('User', 'name', function ($query) {
                        $query->where('role', 'admin'); // Hanya tampilkan pengguna dengan role admin
                    })
                    ->required()
                    ->live(),
                TextInput::make('judul')
                    ->label('Judul Content')
                    ->required(),
                
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published'
                    ])
                    ->default('draft')
                    ->required(),
                DatePicker::make('publish_at')
                    ->label('Tanggal Penerbitan'),
                Grid::make(1)->schema([
                    TextInput::make('sumber')
                    ->label('Sumber')
                    ->url()
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->required(),
                ]),
                Grid::make(1)->schema([
                    RichEditor::make('content')
                        ->label('Content')
                        ->required()
                ]),
                Grid::make(1)->schema([    
                    FileUpload::make('image')
                        ->label('Image')
                        ->image()
                        ->disk('public')
                        ->directory('blog')
                        ->visibility('public'),
                ]),
               
            ])
        ];
    }
}
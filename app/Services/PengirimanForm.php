<?php

namespace App\Services;

use FIlament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Filament\Forms\Components\ToggleButtons;
// use App\Forms\Components\TakePicture;
use Filament\Forms\Get;
use emmanpbarrameda\FilamentTakePictureField\Forms\Components\TakePicture;

final class PengirimanForm{

    public static function schema(): array {
        return [
            Fieldset::make()->schema([
                TextInput::make('no_invoice')
                    ->label('Nomor Invoice')
                    ->default(function () {
                        $tanggal = now()->format('Ymd');
                        $acak = strtoupper(Str::random(5));
                        return "INV-$tanggal-$acak";
                    })
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Select::make('kredit_id')
                    ->label('Status Kredit')
                    ->options(function () {
                        return \App\Models\Kredit::with('pengajuanKredit.pelanggan')
                            ->get()
                            ->mapWithKeys(function ($kredit) {
                                $nama = $kredit->pengajuanKredit->pelanggan->nama_pelanggan ?? 'Tidak diketahui';
                                return [$kredit->id => $nama . ' - ' . $kredit->status_kredit];
                            });
                    })
                    ->searchable()
                    ->required(),
                DatePicker::make('tgl_kirim')
                    ->label('Tanggal Pengiriman')
                    ->native(false) 
                    ->displayFormat('d-m-Y') 
                    ->format('Y-m-d') 
                    ->required(),
                DatePicker::make('tgl_tiba')
                    ->label('Tanggal Tiba')
                    ->native(false) 
                    ->displayFormat('d-m-Y') 
                    ->format('Y-m-d'),
                    // ->required(),
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
                    
                // Ini take picture pake plugin
                TakePicture::make('bukti_foto')
                    ->label('Camera Test')
                    ->disk('public')
                    ->directory('kurir')
                    ->visibility('public')
                    ->useModal(true)
                    ->showCameraSelector(true)
                    ->aspect('16:9')
                    ->imageQuality(80)
                    ->shouldDeleteOnEdit(false)


                // Kalo ini pake take picture component
                // Grid::make(1)->schema([        
                //     ToggleButtons::make('image_mode')
                //         ->translateLabel()
                //         ->label('Change image')
                //         ->options([
                //             'UPLOAD' => __('Upload a image'),
                //             'TAKE' => __('Take a picture'),
                //         ])
                //         ->default('UPLOAD')
                //         ->afterStateHydrated(function (ToggleButtons $component) {
                //             $component->state('UPLOAD');
                //         })
                //         ->live()
                //         ->columnSpanFull()
                //         ->grouped(),
                //     FileUpload::make('bukti_foto')
                //         ->disk('kurir')
                //         ->translateLabel()
                //         ->avatar()
                //         ->label('Photo')
                //         ->image()
                //         ->imageEditor()
                //         ->imageEditorAspectRatios(['1:1'])
                //         ->reorderable()
                //         ->visible(function (Get $get) {
                //             return $get('image_mode') === 'UPLOAD';
                //         })
                //         ->columnSpanFull(),
                //     TakePicture::make('take_photo')
                //         ->translateLabel()
                //         ->label('Take a picture')
                //         ->visible(function (Get $get) {
                //             return $get('image_mode') === 'TAKE';
                //         })
                //         ->columnSpanFull()
              

                // ])
            ])
        ];
    }
}
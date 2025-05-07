<?php

namespace App\Services;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use App\Models\Motor;
use App\Models\JenisCicilan;
use App\Models\Asuransi;
use Illuminate\Support\Facades\Log;

use NumberFormatter;

final class PengajuanKreditForm {

    public static function schema(): array {
        return [
            Wizard::make([
                // Step 1: Informasi Dasar
                Step::make('Informasi Dasar')
                    ->description('Data dasar pengajuan kredit')
                    ->schema([
                        DatePicker::make('tgl_pengajuan_kredit')
                            ->label('Tanggal Pengajuan Kredit')
                            ->default(now())
                            ->required()
                            ->disabled()
                            // ->hidden()
                            ->dehydrated(),

                        Select::make('pelanggan_id')
                            ->label('Pelanggan')
                            ->relationship('Pelanggan', 'nama_pelanggan')
                            ->required()
                            ->live(),
                            // ->rule(function ($get) {
                            //     return function (string $attribute, $value, $fail) {
                            //         $existingPengajuan = \App\Models\PengajuanKredit::where('pelanggan_id', $value)
                            //             ->whereIn('status_pengajuan', ['Menunggu Konfirmasi', 'Diproses', 'Diterima'])
                            //             ->exists();
                                    
                            //         if ($existingPengajuan) {
                            //             $fail('Pelanggan ini sudah memiliki pengajuan kredit yang aktif.');
                            //         }
                            //     };
                            // }),
                        
                    

                        Select::make('motor_id')
                            ->label('Motor')
                            ->relationship('Motor', 'nama_motor')
                            ->live()
                            ->required()
                            ->afterStateUpdated(fn ($set, $state) => 
                                $set('harga_cash', Motor::find($state)?->harga_jual ?? 0)
                            ),

                        TextInput::make('harga_cash')
                            ->label('Harga Cash')
                            ->prefix('Rp ')
                            ->disabled()
                            ->live()
                            ->default(fn ($get) => Motor::find($get('motor_id'))?->harga_jual ?? 0)
                            ->dehydrated(),
                    ]),

                // Step 2: Detail Keuangan
                Step::make('Detail Keuangan')
                    ->description('Informasi cicilan dan asuransi')
                    ->schema([
                        Select::make('asuransi_id')
                            ->label('Asuransi')
                            ->relationship('Asuransi', 'nama_asuransi')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($set, $get) => 
                                $set('biaya_asuransi_perbulan', self::hitungBiayaAsuransi($get))
                            ),

                        TextInput::make('biaya_asuransi_perbulan')
                            ->label('Biaya Asuransi Per Bulan')
                            ->prefix('Rp ')
                            ->disabled()
                            ->live()
                            ->dehydrated()
                            ->afterStateUpdated(fn ($set, $get) => 
                                $set('harga_kredit', self::hitungHargaKredit($get))
                            ),

                        Select::make('jenis_cicilan_id')
                            ->label('Jenis Cicilan')
                            ->relationship('JenisCicilan', 'jenis_cicilan')
                            ->live()
                            ->required()
                            ->afterStateUpdated(fn ($set, $get, $state) =>
                                $set('dp', self::hitungDP($get))
                            )
                            ->afterStateUpdated(fn ($set, $get) => 
                                $set('harga_kredit', self::hitungHargaKredit($get))
                            )
                            ->afterStateUpdated(fn ($set, $get) => 
                                $set('cicilan_perbulan', self::hitungCicilanPerBulan($get))
                            ),

                        TextInput::make('dp')
                            ->label('Uang Muka (DP)')
                            ->prefix('Rp ')
                            ->live()
                            ->dehydrated()
                            // ->disabled(),
                    ]),

                // Step 3: Ringkasan Pembayaran
                Step::make('Ringkasan Pembayaran')
                    ->description('Detail cicilan dan total kredit')
                    ->schema([
                        TextInput::make('harga_kredit')
                            ->label('Harga Kredit')
                            ->prefix('Rp ')
                            ->disabled()
                            ->live()
                            ->dehydrated()
                            ->default(fn ($get) => number_format(self::hitungHargaKredit($get), 0, ',', '.'))
                            ->afterStateUpdated(fn ($set, $get) =>
                                $set('cicilan_perbulan', self::hitungCicilanPerBulan($get))
                            ),
                        
                        TextInput::make('cicilan_perbulan')
                            ->label('Cicilan Per Bulan')
                            ->numeric()
                            ->prefix('Rp ')
                            ->disabled()
                            ->live()
                            ->dehydrated()
                            ->default(fn ($get) => number_format(self::hitungCicilanPerBulan($get), 0, ',', '.')),
                        
                        Select::make('status_pengajuan')
                            ->label('Status Pengajuan')
                            ->options([
                                'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
                                'Diproses' => 'Diproses',
                                'Dibatalkan Pembeli' => 'Dibatalkan Pembeli',
                                'Dibatalkan Penjual' => 'Dibatalkan Penjual',
                                'Bermasalah' => 'Bermasalah',
                                'Diterima' => 'Diterima',
                                'Menunggu Pembayaran' => 'Menunggu Pembayaran'
                            ])
                            ->required(),

                        TextInput::make('keterangan_status_pengajuan')
                            ->label('Keterangan Status')
                            ->required(),
                    ]),

                // Step 4: Dokumen Pendukung
                Step::make('Dokumen Pendukung')
                    ->description('Unggah dokumen yang dibutuhkan')
                    ->schema([
                        FileUpload::make('url_kk')
                            ->label('Kartu Keluarga')
                            ->image()
                            ->disk('public')
                            ->directory('dokumen')
                            ->visibility('public')
                            ->required(),

                        FileUpload::make('url_ktp')
                            ->label('KTP')
                            ->required()
                            ->image()
                            ->disk('public')
                            ->directory('dokumen')
                            ->visibility('public'),

                        FileUpload::make('url_npwp')
                            ->label('NPWP')
                            ->required()
                            ->image()
                            ->disk('public')
                            ->directory('dokumen')
                            ->visibility('public'),
                    ]),

                // Step 5: Dokumen Tambahan
                Step::make('Dokumen Tambahan')
                    ->description('Unggah dokumen pelengkap')
                    ->schema([
                        FileUpload::make('url_slip_gaji')
                            ->label('Slip Gaji')
                            ->required()
                            ->image()
                            ->disk('public')
                            ->directory('dokumen')
                            ->visibility('public'),

                        Grid::make(1)->schema([
                            FileUpload::make('url_foto')
                                ->label('Foto Diri')
                                ->required()
                                ->image()
                                ->disk('public')
                                ->directory('dokumen')
                                ->visibility('public')
                        ])
                    ]),
            ])
            ->skippable() // Opsional: Memungkinkan pengguna melewati langkah
            // ->persistStepInQueryString() // Menyimpan posisi langkah di URL
            // ->submitAction(view: 'buttons.submit-wizard') // Opsional: Tombol submit kustom
        ];
    }

    private static function hitungDP($get) {
        $hargaCash = floatval($get('harga_cash') ?? 0);
        $jenisCicilanId = $get('jenis_cicilan_id');
    
        $jenisCicilan = $jenisCicilanId ? JenisCicilan::find($jenisCicilanId) : null;
        $dpPersen = $jenisCicilan?->dp ?? 0; 
    
        $dp = ($hargaCash * $dpPersen) / 100;
    
        return $dp;
    }
    
    private static function hitungBiayaAsuransi($get) {
        $hargaCash = floatval($get('harga_cash') ?? 0);
        $asuransiId = $get('asuransi_id');
    
        $asuransi = $asuransiId ? Asuransi::find($asuransiId) : null;
        $marginAsuransi = $asuransi?->margin_asuransi ?? 0;
    
        $biayaAsuransi = ($hargaCash * $marginAsuransi) / 100;
    
        return $biayaAsuransi;
    }
    
    private static function hitungHargaKredit($get) {
        $hargaCash = floatval($get('harga_cash') ?? 0);
        $jenisCicilanId = $get('jenis_cicilan_id');
        $biayaAsuransi = floatval($get('biaya_asuransi_perbulan') ?? 0);
    
        $jenisCicilan = $jenisCicilanId ? JenisCicilan::find($jenisCicilanId) : null;
        $marginKredit = $jenisCicilan?->margin_kredit ?? 0;
    
        $hargaKredit = $hargaCash + ($hargaCash * $marginKredit / 100) + $biayaAsuransi;
    
        return $hargaKredit;
    }
    
    private static function hitungCicilanPerBulan($get) {
        $hargaKredit = floatval($get('harga_kredit') ?? 0);
        $dp = floatval($get('dp') ?? 0);
        $jenisCicilanId = $get('jenis_cicilan_id');
    
        $jenisCicilan = $jenisCicilanId ? JenisCicilan::find($jenisCicilanId) : null;
        $lamaCicilan = $jenisCicilan?->lama_cicilan ?? 1; 
    
        if ($lamaCicilan == 0) {
            return 0;
        }
    
        $cicilanPerBulan = ($hargaKredit - $dp) / $lamaCicilan;
    
        return $cicilanPerBulan;
    }
}
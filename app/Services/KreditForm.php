<?php

namespace App\Services;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use App\Models\PengajuanKredit;
use Illuminate\Support\Facades\Log;

final class KreditForm
{
    public static function schema(): array
    {
        return [
            Fieldset::make()->schema([
                Select::make('pengajuan_kredit_id')
                    ->label('Pengajuan Kredit')
                    ->relationship('pengajuanKredit', 'id')
                    ->options(function () {
                        return PengajuanKredit::whereIn('status_pengajuan', ['Menunggu Pembayaran'])
                            ->with(['pelanggan', 'motor', 'jenisCicilan']) // Muat relasi JenisCicilan
                            ->get()
                            ->mapWithKeys(function ($pengajuan) {
                                return [
                                    $pengajuan->id => "{$pengajuan->pelanggan->nama_pelanggan} - {$pengajuan->motor->nama_motor}"
                                ];
                            });
                    })
                    ->required()
                    ->preload()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Ketika pengajuan_kredit_id berubah, ambil data terkait
                        $pengajuan = PengajuanKredit::with('jenisCicilan')->find($state);
                        if ($pengajuan) {
                            // Hitung tanggal mulai dan selesai
                            $lamaCicilan = $pengajuan->jenisCicilan->lama_cicilan ?? 0;
                            $tglMulai = now();
                            $set('tgl_mulai_kredit', $tglMulai->format('Y-m-d'));
                            $set('tgl_selesai_kredit', $tglMulai->addMonths($lamaCicilan)->format('Y-m-d'));

                            // Hitung sisa kredit: harga_kredit - dp
                            $sisaKredit = $pengajuan->harga_kredit - $pengajuan->dp;
                            $set('sisa_kredit', number_format($sisaKredit, 2, '.', '')); // Format 2 desimal
                        }
                    }),

                Select::make('metode_pembayaran_id')
                    ->relationship('metodePembayaran', 'metode_pembayaran')
                    ->required()
                    ->label('Metode Pembayaran'),

                DatePicker::make('tgl_mulai_kredit')
                    ->label('Tanggal Mulai Kredit')
                    ->native(false)
                    ->displayFormat('d-m-Y')
                    ->format('Y-m-d')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $pengajuanId = $get('pengajuan_kredit_id');
                        if ($pengajuanId) {
                            $pengajuan = PengajuanKredit::with('jenisCicilan')->find($pengajuanId);
                            if ($pengajuan && $pengajuan->jenisCicilan) {
                                $lamaCicilan = $pengajuan->jenisCicilan->lama_cicilan;
                                $tglMulai = \Carbon\Carbon::parse($state);
                                $set('tgl_selesai_kredit', $tglMulai->addMonths($lamaCicilan)->format('Y-m-d'));
                            }
                        }
                    }),

                DatePicker::make('tgl_selesai_kredit')
                    ->label('Tanggal Selesai Kredit')
                    ->native(false)
                    ->displayFormat('d-m-Y')
                    ->format('Y-m-d')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                FileUpload::make('url_bukti_bayar')
                    ->label('Bukti Bayar DP')
                    ->image()
                    ->disk('public')
                    ->directory('dp')
                    ->visibility('public'),

                TextInput::make('sisa_kredit')
                    ->label('Sisa Kredit')
                    ->numeric()
                    ->step('0.01')
                    ->required()
                    ->disabled() // Nonaktifkan agar tidak bisa diedit
                    ->dehydrated(), // Pastikan nilai disimpan ke database

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
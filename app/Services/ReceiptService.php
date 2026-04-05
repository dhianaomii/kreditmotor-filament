<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReceiptService
{
    /**
     * Generate Receipt PDF for DP (Kredit)
     */
    public static function generateDPReceipt($kredit)
    {
        $data = [
            'type' => 'Pembayaran DP (Uang Muka)',
            'order_id' => $kredit->order_id,
            'transaction_id' => $kredit->transaction_id ?? '-',
            'nama_pelanggan' => $kredit->PengajuanKredit->Pelanggan->nama_pelanggan,
            'motor' => $kredit->PengajuanKredit->Motor->nama_motor,
            'nominal' => $kredit->PengajuanKredit->dp,
            'tanggal' => now()->format('d F Y H:i'),
            'status' => 'LUNAS'
        ];

        $pdf = Pdf::loadView('pdf.receipt', $data);
        $filename = 'receipt_dp_' . $kredit->id . '_' . time() . '.pdf';
        $path = 'receipts/' . $filename;
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return $path;
    }

    /**
     * Generate Receipt PDF for Angsuran
     */
    public static function generateAngsuranReceipt($angsuran)
    {
        $data = [
            'type' => 'Pembayaran Angsuran ke-' . $angsuran->angsuran_ke,
            'order_id' => $angsuran->order_id,
            'transaction_id' => $angsuran->transaction_id ?? '-',
            'nama_pelanggan' => $angsuran->Kredit->PengajuanKredit->Pelanggan->nama_pelanggan,
            'motor' => $angsuran->Kredit->PengajuanKredit->Motor->nama_motor,
            'nominal' => $angsuran->total_bayar,
            'tanggal' => now()->format('d F Y H:i'),
            'status' => 'LUNAS'
        ];

        $pdf = Pdf::loadView('pdf.receipt', $data);
        $filename = 'receipt_ang_' . $angsuran->id . '_' . time() . '.pdf';
        $path = 'receipts/' . $filename;
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return $path;
    }
}

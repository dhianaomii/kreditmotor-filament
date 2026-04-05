<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\PengajuanKredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Angsuran;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        try {
            $notification = new Notification();
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status;

            Log::info("Midtrans Webhook Received: $orderId - $transactionStatus");

            if (str_starts_with($orderId, 'ANG-')) {
                $angsuran = Angsuran::where('order_id', $orderId)->first();
                if (!$angsuran) return response()->json(['message' => 'Angsuran not found'], 404);

                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    $angsuran->keterangan = 'Lunas (Midtrans)';
                    $angsuran->save();

                    // Cek jika sudah lunas semua
                    $kredit = $angsuran->Kredit;
                    $pengajuan = $kredit->PengajuanKredit;
                    if ($kredit->angsuran()->where('keterangan', 'Lunas (Midtrans)')->count() >= $pengajuan->JenisCicilan->lama_cicilan) {
                        $kredit->status_kredit = 'Lunas';
                        $kredit->save();
                    }
                } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $angsuran->delete(); // Hapus record angsuran jika gagal
                }
                return response()->json(['message' => 'Success']);
            }

            $kredit = Kredit::where('order_id', $orderId)->first();

            if (!$kredit) {
                return response()->json(['message' => 'Kredit not found'], 404);
            }

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $kredit->payment_status = 'pending';
                } else {
                    $kredit->payment_status = 'paid';
                    $this->markAsAccepted($kredit);
                }
            } else if ($transactionStatus == 'settlement') {
                $kredit->payment_status = 'paid';
                $this->markAsAccepted($kredit);
            } else if ($transactionStatus == 'pending') {
                $kredit->payment_status = 'pending';
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $kredit->payment_status = 'failed';
            }

            $kredit->save();

            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    private function markAsAccepted($kredit)
    {
        $pengajuan = $kredit->PengajuanKredit;
        if ($pengajuan) {
            $pengajuan->status_pengajuan = 'Diterima';
            $pengajuan->save();
        }
    }
}

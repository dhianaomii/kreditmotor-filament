<?php

namespace App\Listeners;

use App\Events\KreditDibatalkan;
use Illuminate\Support\Facades\Log;

class KreditDibatalkanNotification
{
    /**
     * Handle the event.
     */
    public function handle(KreditDibatalkan $event): void
    {
        Log::info('Notification: Pengajuan Kredit Dibatalkan oleh: ' . $event->pengajuanKredit->Pelanggan->nama_pelanggan);
    }
}

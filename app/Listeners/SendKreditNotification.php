<?php

namespace App\Listeners;

use App\Events\KreditBaruDiajukan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendKreditNotification
{
    /**
     * Handle the event.
     */
    public function handle(KreditBaruDiajukan $event): void
    {
        Log::info('Notification: Pengajuan Kredit Baru Diajukan oleh Pelanggan: ' . $event->pengajuanKredit->Pelanggan->nama_pelanggan);
        // Implementasi nyata: Mail::to(admin)->send(...)
    }
}

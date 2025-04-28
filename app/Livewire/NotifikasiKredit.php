<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PengajuanKredit;

class NotifikasiKredit extends Component
{
    public $jumlahNotifikasi = 0;

    protected $listeners = [
        'kreditDiajukan' => 'updateNotifikasi',
        'kreditDibatalkan' => 'updateNotifikasi'
    ];

    public function mount()
    {
        $this->updateNotifikasi();
    }

    public function updateNotifikasi()
    {
        $this->jumlahNotifikasi = PengajuanKredit::where('status_pengajuan', 'Menunggu Konfirmasi')
        ->orWhere(function ($query) {
            $query->where('status_pengajuan', 'Dibatalkan Pembeli')
                  ->where('updated_at', '>=', now()->subHours(12));
        })
        ->count();
    }

    public function render()
    {
        return view('livewire.notifikasi-kredit');
    }
}
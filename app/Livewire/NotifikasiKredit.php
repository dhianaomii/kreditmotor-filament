<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PengajuanKredit;

class NotifikasiKredit extends Component
{
    public $jumlahNotifikasi = 0;

    protected $listeners = ['kreditDiajukan' => 'updateNotifikasi'];

    public function mount()
    {
        $this->updateNotifikasi();
    }

    public function updateNotifikasi()
    {
        $this->jumlahNotifikasi = PengajuanKredit::where('status_pengajuan', 'Menunggu Konfirmasi')->count();
    }

    public function render()
    {
        return view('livewire.notifikasi-kredit');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class CameraCapture extends Component
{
    
    public function savePhoto($imageData)
    {
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $imageName = uniqid() . '.png';
        Storage::disk('public')->put('kurir/' . $imageName, base64_decode($image));
        // Simpan path atau lakukan tindakan lain sesuai kebutuhan
    }
    public function render()
    {
        return view('livewire.camera-capture');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebcamController extends Controller
{
    public function index()
    {
        return view('webcam-capture.index')->with([
            'title' => 'Webcam Capture'
        ]);
    }

    public function store(Request $request)
    {
        $image = $request->input('image'); // Data gambar dalam format base64

        // Menghapus prefix base64
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10) . '.jpg';

        // Menyimpan gambar ke disk 'public' di folder 'uploads'
        Storage::disk('public')->put('uploads/' . $imageName, base64_decode($image));

        // Mengembalikan path atau URL gambar
        return response()->json(['url' => Storage::url('uploads/' . $imageName)]);
    }
}

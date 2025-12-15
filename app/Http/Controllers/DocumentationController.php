<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\Documentation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DocumentationController extends Controller
{
    public function store(Request $request, Schedule $activity)
    {
        // 1. Validasi Ukuran File (MAX 2MB = 2048 KB)
        $request->validate([
            'document_file' => 'required|image|max:2048', 
            'caption' => 'nullable|string|max:255',
        ], [
            'document_file.max' => 'Ukuran file tidak boleh melebihi 2MB.'
        ]);

        $file = $request->file('document_file');
        $path = $file->store('documentation', 'public');
        // $fileName = time() . '_' . $file->getClientOriginalName();
        // $filePath = 'documentation/' . $fileName;

        // 2. Logika Kompresi dan Penyimpanan
        // Karena kompresi harus dilakukan di Server (PHP/Library Image Intervention) 
        // atau di Browser (JavaScript), kita hanya dapat menyimulasikan kompresi di sini.

        // Jika Anda menggunakan library kompresi (misal: Image Intervention):
        // $compressedImage = Image::make($file)->limitSize(2048)->encode('jpg', 80); 
        // Storage::put($filePath, $compressedImage);
        
        // Untuk saat ini, kita simpan file asli (asumsi file sudah lolos validasi max:2048)
        // $file->storeAs('public', $filePath); 

        // 3. Simpan Entri ke Database
        Documentation::create([
            'activity_id' => $activity->id,
            'file_path' => $path,
            'caption' => $request->caption,
            'uploaded_by' => Auth::id(),
        ]);
        

        return redirect()->back()->with('success', 'Dokumentasi berhasil diunggah dan disimpan.');
    }

    public function index()
    {
        // Mengambil data dokumentasi, diurutkan terbaru, 9 foto per halaman
        // 'uploader' di-load agar query lebih cepat (eager loading)
        $dokumentasi = Documentation::with('uploader')->latest()->paginate(9);

        // Arahkan ke file view kamu (sesuaikan namanya, misal: documentation.index)
        return view('documentation.index', compact('dokumentasi'));
    }
}

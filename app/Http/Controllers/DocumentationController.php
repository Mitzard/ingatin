<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\Documentation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        $path = null;

        // 3. Cek apakah user upload file (gunakan nama input HTML)
        if ($request->hasFile('document_file')) {

            // A. Upload file ke Cloudinary lewat Disk 'cloudinary'
            // Ini akan menyimpan file dan mengembalikan ID-nya (misal: ingatin_flyers/abcde.jpg)
            $savedPath = $request->file('document_file')->store('ingatin_documentations', 'cloudinary');

            // B. Ambil URL Lengkapnya (HTTPS)
            // Penting agar di database tersimpan link lengkap, bukan cuma nama file
            $path = Storage::disk('cloudinary')->url($savedPath);
        }

        // dd($path);
        
        // $path = $request->file('document_file')
        //             ->storeOnCloudinary('ingatin_documentation')
        //             ->getSecurePath();
        // $fileName = time() . '_' . $file->getClientOriginalName();
        // $filePath = 'documentation/' . $fileName;

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

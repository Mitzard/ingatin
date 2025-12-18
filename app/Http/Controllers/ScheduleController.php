<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('kalender.index');
    }

    public function create(Request $request)
    {
        // 1. Validasi input dari HTML (name="image_flyer")
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'start_time' => 'required',
            'end' => 'nullable|date|after_or_equal:start',
            'end_time' => 'nullable',
            'image_flyer' => 'nullable|image|max:2048', // Gunakan nama input HTML
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $startDateTime = $request->start . ' ' . $request->start_time;

        $endDateTime = null;
        if ($request->end && $request->end_time) {
            $endDateTime = $request->end . ' ' . $request->end_time;
        } elseif ($request->end) {
            // Jika cuma isi tanggal selesai tanpa jam, set jam default (misal 23:59)
            $endDateTime = $request->end . ' 23:59:00';
        }


        // 2. Siapkan variabel path default null
        $path = null;

        // 3. Cek apakah user upload file (gunakan nama input HTML)
        if ($request->hasFile('image_flyer')) {

            // A. Upload file ke Cloudinary lewat Disk 'cloudinary'
            // Ini akan menyimpan file dan mengembalikan ID-nya (misal: ingatin_flyers/abcde.jpg)
            $savedPath = $request->file('image_flyer')->store('ingatin_flyers', 'cloudinary');

            // B. Ambil URL Lengkapnya (HTTPS)
            // Penting agar di database tersimpan link lengkap, bukan cuma nama file
            $path = Storage::disk('cloudinary')->url($savedPath);
        }

        // dd($path);

        // kode lama
        // if ($request->hasFile('image_flyer')) {
        //     $path = $request->file('image_flyer')->store('flyers', 'public');
        // }

        // 4. Simpan ke Database
        Schedule::create([
            'title' => $request->title,
            'start' => $startDateTime,
            'end' => $endDateTime,
            'description' => $request->description,
            'color' => $request->color,
            'lokasi' => $request->lokasi,
            'created_by' => Auth::id(),
            'status' => 'upcoming',

            // KUNCI PERBAIKAN:
            // Kiri: Nama Kolom Database. Kanan: Variabel path yang kita buat di atas.
            'image_flyer_path' => $path,
        ]);

        if (Auth::user()->isPengurus()) {
            return redirect()->route('dashboard')->with('success', 'Kegiatan berhasil ditambahkan!');
        } else {
            return redirect()->route('home')->with('success', 'Kegiatan berhasil ditambahkan!');
        }
    }

    public function getEvents()
    {
        $schedules = Schedule::all();
        return response()->json($schedules);
    }

    public function deleteEvent(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        if ($request->ajax()) {
            return response()->json(['message' => 'Kegiatan Berhasil dihapus']);
        }

        // Jika dari Tabel Admin, kembalikan ke halaman sebelumnya
        return back()->with('success', 'Kegiatan berhasil dihapus!');
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $request->validate([
            'title'       => 'required|string|max:255',
            'start'       => 'required|date',
            'end'         => 'nullable|date|after_or_equal:start',
            'start_time'  => 'required', // Ganti 'end' jadi 'end_date'
            'end_time'    => 'nullable',
            'lokasi'      => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_flyer' => 'nullable|image|max:2048', // Validasi foto (maks 2MB)
        ]);

        $startDateTime = $request->start . ' ' . $request->start_time;

        $endDateTime = null;
        if ($request->end) {
            // Jika ada jam selesai, gabungkan. Jika tidak, set 23:59:00
            $time = $request->end_time ? $request->end_time : '23:59:00';
            $endDateTime = $request->end . ' ' . $time;
        }

        $path = $schedule->image_flyer_path; // Default: Pakai foto lama

        if ($request->hasFile('image_flyer')) {
            // Kita langsung upload yang baru saja. 
            // Menghapus file lama di Cloudinary agak rumit butuh Public ID,
            // untuk pemula cukup timpa URL-nya saja di database.

            $uploadedFileUrl = $request->file('image_flyer')
                ->storeOnCloudinary('ingatin_flyers')
                ->getSecurePath();

            // Update variabel $path dengan URL gambar baru
            $path = $uploadedFileUrl;
        }

        // if ($request->hasFile('image_flyer')) {
        //     // Hapus foto lama jika ada
        //     if ($schedule->image_flyer_path) {
        //         Storage::disk('public')->delete($schedule->image_flyer_path);
        //     }
        //     // Upload baru dan timpa variabel $path
        //     $path = $request->file('image_flyer')->store('image-flyers', 'public');
        // }

        // --- 2. Update Data Diri ---
        $schedule->update([
            'title' => $request->title,
            'start' => $startDateTime,
            'end' => $endDateTime,
            'lokasi' => $request->lokasi,
            'description' => $request->description,
            'color' => $request->color,
            'image_flyer_path' => $path,
            // ... field lain ...
        ]);

        return redirect()->route('pengurus.kelola_kegiatan')->with('success', 'Kegiatan berhasil diperbarui!');
    }

    public function resize(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $newEndDate = Carbon::parse($request->input('end_date'))->setTimezone('UTC');
        $schedule->update(['end' => $newEndDate]);
        return response()->json(['message' => 'Kegiatan Berhasil Melakukan Perubahan Waktu']);
    }

    // public function search(Request $request)
    // {
    //     $searchKeywords = $request->input('title');
    //     $matchingEvents = Schedule::where('title', 'like', '%' . $searchKeywords . '%')->get();
    //     return response()->json($matchingEvents);
    // }
}

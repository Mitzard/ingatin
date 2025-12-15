<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\UpdateScheduleRequest;

class PengurusController extends Controller
{
    public function __construct()
    {
        // Gunakan middleware yang sudah ada untuk memproteksi semua fungsi di controller ini
        $this->middleware(['role:pengurus']);
    }

    public function pengaturan()
    {
        $user = Auth::user();

        // Pastikan Anda membuat file view ini: resources/views/user/profile.blade.php
        return view('pengurus.pengaturan', compact('user'));
    }

    public function dashboard()
    {
        // Di sini Anda bisa mengambil statistik penting untuk Dashboard
        // $totalUpcoming = Schedule::where('status', 'upcoming')->count();
        // $totalFinished = Schedule::where('status', 'finished')->count();
        $total_kegiatan = Schedule::count();
        $akan_datang = Schedule::where('start', '>=', now())->count();
        $selesai = Schedule::where('end', '<', now())->count();

        return view('pengurus.dashboard', compact('total_kegiatan', 'akan_datang', 'selesai'));
        // compact('totalUpcoming', 'totalFinished')
    }

    public function showCreateForm()
    {
        // Memanggil view resources/views/pengurus/tambah.blade.php
        return view('pengurus.tambah');
    }

    public function kelolaKegiatan(Request $request)
    {
        // 1. Logika Pencarian
        $search = $request->get('search');

        $activities = Schedule::query()
            ->when($search, function ($query, $search) {
                // Filter berdasarkan Judul, Lokasi, atau Status
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->orderBy('start', 'desc')
            ->paginate(10) // Pagination 10 baris
            // Eager load created_by untuk menampilkan siapa yang buat
            ->withQueryString();

        return view('pengurus.kelola_kegiatan', compact('activities', 'search'));
    }

    public function editKegiatan(Schedule $activity)
    {
        return view('pengurus.edit_kegiatan', compact('activity'));
    }

    public function updateKegiatan(UpdateScheduleRequest $request, Schedule $activity)
    {
        // 1. Data sudah divalidasi oleh UpdateScheduleRequest

        // 2. Lakukan update data
        $validated = $request->validated();

        // 2. Gabungkan Tanggal & Jam (MANUAL MERGE)
        // Kita timpa key 'start' agar berisi datetime lengkap
        $validated['start'] = $request->start . ' ' . $request->start_time;

        // Logika End Date
        if ($request->end) {
            $time = $request->end_time ?? '23:59:00';
            $validated['end'] = $request->end . ' ' . $time;
        } else {
            $validated['end'] = null;
        }

        // 3. Logika File (Sama seperti sebelumnya)
        if ($request->hasFile('image_flyer')) {
            if ($activity->image_flyer_path && Storage::disk('public')->exists($activity->image_flyer_path)) {
                Storage::disk('public')->delete($activity->image_flyer_path);
            }
            $path = $request->file('image_flyer')->store('image-flyers', 'public');

            // Masukkan path ke array validated
            $validated['image_flyer_path'] = $path;
        }

        // 4. BERSIH-BERSIH (PENTING!)
        // Hapus field bantuan yang tidak ada di kolom database agar tidak Error SQL
        unset($validated['start_time']); // Hapus start_time
        unset($validated['end_time']);   // Hapus end_time
        unset($validated['image_flyer']); // Hapus object file mentah (karena sudah kita ambil path-nya)

        // 5. Update (Sekarang $validated sudah bersih dan sesuai kolom DB)
        $activity->update($validated);

        // 6. Redirect
        return redirect()->route('kelola.kegiatan')
            ->with('success', 'Kegiatan "' . $activity->title . '" berhasil diperbarui!');
    }

    public function updatePengurusPassword(PasswordUpdateRequest $request)
    {
        $user = Auth::user();

        // Validasi: Pastikan password lama cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi lama yang Anda masukkan salah.']);
        }

        // Update Password baru
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('pengaturan')->with('success', 'Kata sandi berhasil diperbarui!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ScheduleRegistration;
use Illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpFoundation\Response;

class ScheduleRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Anda mungkin ingin menambahkan middleware khusus warga di sini
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $search = $request->input('search');

        // 2. Mulai Query
        $query = Schedule::query();

        // 3. Logika Pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('lokasi', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')        // Cari Status (ex: upcoming, finished)
                    ->orWhere('start', 'like', '%' . $search . '%');
            });
        }

        // 4. Urutkan dari yang terbaru
        $query->orderBy('start', 'desc');

        // 5. Eager Loading & Pagination
        $activities = $query->with(['registrations' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])->paginate(9);

        // 6. PENTING: Tambahkan parameter pencarian ke link pagination
        // Agar saat klik "Halaman 2", hasil pencarian tidak hilang/reset
        $activities->appends(['search' => $search]);

        // 7. Kirim data ke view (sertakan variabel $search agar input text tidak kosong setelah search)
        return view('aktivitas.index', compact('activities', 'search'));
    }

    public function store(Request $request)
    {
        // Validasi sederhana (pastikan activity_id ada)
        $request->validate(['activity_id' => 'required|exists:schedules,id']);

        $activityId = $request->activity_id;
        $user = Auth::user();

        try {
            // 1. Buat entri pendaftaran baru
            ScheduleRegistration::create([
                'user_id' => $user->id,
                'activity_id' => $activityId,
                'status' => 'registered', // Status default
            ]);

            $activity = Schedule::findOrFail($activityId);

            // 2. Susun Pesan
            $pesan = "Halo *" . $user->nama_lengkap . "*! 👋\n\n";
            $pesan .= "Terima kasih telah mendaftar di kegiatan:\n";
            $pesan .= "📅 *" . $activity->title . "*\n";
            $pesan .= "📍 Lokasi: " . $activity->lokasi . "\n";
            // Pastikan format tanggal sesuai keinginan
            $pesan .= "🕒 Waktu: " . $activity->start->translatedFormat('d F Y, H:i') . " WIB\n\n";
            $pesan .= "Harap hadir tepat waktu ya. Terima kasih!";

            // 3. Cek nomor HP user & Kirim
            // GANTI 'no_hp' dengan nama kolom hp di tabel users kamu (misal: 'whatsapp' atau 'phone')
            $targetNomor = $user->nomor_telepon; 

            if ($targetNomor) {
                // Panggil fungsi kirim yang ada di bawah
                $this->sendFonnte($targetNomor, $pesan);
            }

            // 2. (Simulasi WA Gateway)
            // Logika mengirim notifikasi WA ke nomor $user->nomor_telepon akan diletakkan di sini.

            return redirect()->back()->with('success', 'Pendaftaran kegiatan berhasil! Notifikasi WA telah dikirim.');
        } catch (\Throwable $e) {
            // Jika terjadi error (misal: user sudah terdaftar karena unique constraint)
            return redirect()->back()->with('error', 'Gagal mendaftar. Anda mungkin sudah terdaftar pada kegiatan ini.');
        }
    }

    private function sendFonnte($target, $message)
    {
        $token = env('FONNTE_TOKEN'); 

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public function destroy($id)
    {
        $userId = Auth::id();

        // 1. Cari pendaftaran milik user untuk kegiatan ini
        $registration = ScheduleRegistration::where('user_id', $userId)
            ->where('activity_id', $id)
            ->first();

        if ($registration) {
            $registration->delete(); // Hapus entri pendaftaran
            return redirect()->back()->with('success', 'Pembatalan pendaftaran berhasil.');
        }

        return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan.');
    }

    public function show(Schedule $activity)
    {
        // 1. Ambil status pendaftaran user saat ini
        // Menggunakan with('registrations') dari Model Schedule akan lebih efisien jika sudah di-eager load
        $isRegistered = ScheduleRegistration::where('user_id', Auth::id())
            ->where('activity_id', $activity->id)
            ->exists();

        // 2. Kirim data ke view
        // Pastikan view resources/views/activities/show.blade.php sudah ada
        return view('aktivitas.detail', compact('activity', 'isRegistered'));
    }

    public function showPendaftar(Schedule $activity, Request $request)
    {
        $search = $request->get('search');

        $activity->load('documentation.uploader');

        // Query dasar untuk mengambil semua pendaftar kegiatan ini
        $registrations = ScheduleRegistration::where('activity_id', $activity->id)
            ->with('user') // Eager load data user

            // --- Logika Pencarian ---
            ->whereHas('user', function ($query) use ($search) {
                if ($search) {
                    $query->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('nomor_telepon', 'like', "%{$search}%");
                }
            })
            // --- Akhir Logika Pencarian ---

            ->paginate(10) // Pagination 10 baris
            ->withQueryString();

        $documentations = $activity->documentation()
            ->with('uploader')
            ->latest()
            ->paginate(9, ['*'], 'doc_page');

        return view('pengurus.list_pendaftar', compact('activity', 'registrations', 'documentations'));
    }
}

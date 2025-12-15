<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengurusId = User::where('role', 'pengurus')->first()->id ?? 1; // Ambil ID Pengurus pertama
        $activities = [];

        // Tanggal dasar untuk seeder (25 November 2025)
        $baseDate = Carbon::create(2025, 11, 25, 8, 0, 0);

        // --- Data Kegiatan Bervariasi (Total 15) ---

        // 1. KEGIATAN SELESAI (FINISHED) - Sebelum 27 Nov 2025
        $activities[] = $this->createActivity($pengurusId, 'Kerja Bakti Pembersihan Parit', $baseDate->copy(), $baseDate->copy()->addHours(4), 'Sepanjang Jl. Mawar', 'finished');
        $activities[] = $this->createActivity($pengurusId, 'Rapat Evaluasi Bulan Oktober', $baseDate->copy()->addDays(1), $baseDate->copy()->addDays(1)->addHours(2), 'Balai Warga', 'finished');
        $activities[] = $this->createActivity($pengurusId, 'Siskamling Bersama (Sesi I)', $baseDate->copy()->addDays(2), $baseDate->copy()->addDays(2)->addHours(6), 'Pos Keamanan', 'finished');
        $activities[] = $this->createActivity($pengurusId, 'Pengumpulan Iuran Bulanan', $baseDate->copy()->addDays(2), $baseDate->copy()->addDays(4)->addHours(2), 'Pintu ke Pintu', 'finished'); // Berhari-hari

        // 2. KEGIATAN SEDANG BERLANGSUNG (ONGOING) - Sekitar 27 Nov 2025 (Asumsi baseDate + 2 hari masih ongoing)
        // Kita set start=27 Nov dan end=27 Nov
        $activities[] = $this->createActivity($pengurusId, 'Gotong Royong Perbaikan Lapangan', Carbon::now()->subHours(2), Carbon::now()->addHours(3), 'Lapangan Utama RT', 'ongoing');
        $activities[] = $this->createActivity($pengurusId, 'Pembersihan Fasilitas Umum', Carbon::now()->subMinutes(30), Carbon::now()->addHours(2), 'Area Taman RT', 'ongoing');

        // 3. KEGIATAN AKAN DATANG (UPCOMING) - Setelah 27 Nov 2025
        $upcomingDate = Carbon::now()->addDays(3);
        $activities[] = $this->createActivity($pengurusId, 'Lomba Mewarnai Anak (Desember)', $upcomingDate->copy()->addDays(10), $upcomingDate->copy()->addDays(10)->addHours(3), 'Balai Warga', 'upcoming');
        $activities[] = $this->createActivity($pengurusId, 'Rapat Pembentukan Panitia Tahun Baru', $upcomingDate->copy()->addDays(15), $upcomingDate->copy()->addDays(15)->addHours(3), 'Balai Warga', 'upcoming');
        $activities[] = $this->createActivity($pengurusId, 'Kerja Bakti Rutin (Minggu Pertama Des)', $upcomingDate->copy()->addDays(20), $upcomingDate->copy()->addDays(20)->addHours(4), 'Sepanjang Jalan Melati', 'upcoming');
        $activities[] = $this->createActivity($pengurusId, 'Siskamling Bersama (Sesi II)', $upcomingDate->copy()->addDays(25), $upcomingDate->copy()->addDays(25)->addHours(6), 'Pos Keamanan', 'upcoming');
        $activities[] = $this->createActivity($pengurusId, 'Donor Darah Komunitas', $upcomingDate->copy()->addDays(30), $upcomingDate->copy()->addDays(30)->addHours(5), 'Balai Warga', 'upcoming');
        $activities[] = $this->createActivity($pengurusId, 'Lomba Masak Ibu-Ibu', $upcomingDate->copy()->addDays(35), $upcomingDate->copy()->addDays(35)->addHours(6), 'Lapangan Utama RT', 'upcoming');
        $activities[] = $this->createActivity($pengurusId, 'Pemeriksaan Kesehatan Gratis', $upcomingDate->copy()->addDays(40), $upcomingDate->copy()->addDays(40)->addHours(4), 'Balai Warga', 'upcoming');
        $activities[] = $this->createActivity($pengurusId, 'Gotong Royong Bersama (Akhir Tahun)', $upcomingDate->copy()->addDays(45), $upcomingDate->copy()->addDays(45)->addHours(5), 'Seluruh Area RT', 'upcoming');
        $activities[] = $this->createActivity($pengurusId, 'Penghijauan Area RT', $upcomingDate->copy()->addDays(50), $upcomingDate->copy()->addDays(50)->addHours(3), 'Area Kosong Belakang RT', 'upcoming');

        // Memasukkan data ke database
        foreach ($activities as $activity) {
            Schedule::create($activity);
        }
        echo "Total 15 kegiatan telah ditambahkan.\n";
    }

    private function createActivity(int $createdBy, string $title, Carbon $start, Carbon $end, string $lokasi, string $status): array
    {
        $title = match ($lokasi) {
            'Balai Warga' => 'Rapat atau Acara Internal',
            'Lapangan Utama RT' => 'Kegiatan Olahraga atau Lomba',
            'Pos Keamanan' => 'Jadwal Ronda Malam',
            default => 'Aksi Lingkungan',
        };

        return [
            'title' => $title, // Akan diganti di loop run()
            'start' => $start->format('Y-m-d'), // Format Date sesuai migrasi
            'end' => $end->format('Y-m-d'),     // Format Date sesuai migrasi
            'lokasi' => $lokasi,
            'image_flyer_path' => null, // Dikosongkan
            'status' => $status,
            'description' => "Detail kegiatan ini adalah: " . $title . " pada tanggal " . $start->format('d/m') . ".",
            'created_by' => $createdBy,
            'color' => '#C1203A', // Warna merah untuk konsistensi FullCalendar
        ];
    }
}

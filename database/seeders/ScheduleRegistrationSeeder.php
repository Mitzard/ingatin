<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ScheduleRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = Schedule::all();
        // Ambil semua user yang memiliki role 'warga'
        $warga = User::where('role', 'warga')->pluck('id'); 
        
        if ($warga->isEmpty() || $activities->isEmpty()) {
            echo "SKIPPING: User Warga atau Kegiatan belum tersedia.\n";
            return;
        }

        foreach ($activities as $activity) {
            // Tentukan jumlah pendaftar acak per kegiatan (antara 5 hingga 15 pendaftar)
            $numberOfRegistrations = rand(5, 15);
            
            // Ambil ID Warga secara acak (gunakan shuffle dan slice)
            $selectedWargaIds = $warga->shuffle()->slice(0, $numberOfRegistrations)->all();

            $registrations = [];
            
            foreach ($selectedWargaIds as $userId) {
                // Tentukan status acak untuk simulasi
                $status = $this->getRandomStatus($activity->status);

                $registrations[] = [
                    'user_id' => $userId,
                    'activity_id' => $activity->id,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Masukkan data ke database, mengabaikan duplikasi (kunci unik)
            DB::table('schedule_registrations')->insertOrIgnore($registrations);
            echo "Kegiatan '{$activity->title}' didaftar oleh {$numberOfRegistrations} warga.\n";
        }
    }

    private function getRandomStatus(string $activityStatus): string
    {
        // Jika kegiatan sudah selesai, maka status pendaftaran cenderung 'attended'
        if ($activityStatus === 'finished') {
            return rand(0, 1) === 1 ? 'attended' : 'registered';
        }
        
        // Jika kegiatan akan datang/sedang berlangsung, statusnya adalah 'registered'
        // Kita juga bisa simulasi 'cancelled'
        if (rand(0, 5) === 1) {
             return 'cancelled'; // 1/6 kemungkinan batal
        }
        
        return 'registered';
    }
}

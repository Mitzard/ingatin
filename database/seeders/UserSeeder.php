<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalWarga = 25;
        $totalPengurus = 3;

        // --- DATA PENGURUS (3 Orang) ---
        $this->seedPengurus($totalPengurus);

        // --- DATA WARGA (25 Orang) ---
        $this->seedWarga($totalWarga);
    }

    private function seedPengurus(int $count): void
    {
        echo "Menambahkan $count data Pengurus...\n";
        $pekerjaan = ['Ketua RT', 'Sekretaris RT', 'Bendahara RT'];

        for ($i = 1; $i <= $count; $i++) {
            $baseName = "Pengurus {$i}";
            $phoneSuffix = 81100000 + $i; // Contoh nomor HP

            DB::table('users')->insert([
                'nama_lengkap' => $baseName . " Utama",
                'email' => "pengurus{$i}@gmail.com",
                'password' => Hash::make("pengurus{$i}"), // password: pengurus1, pengurus2, etc.
                'nik' => '367101' . str_pad($i, 10, '0', STR_PAD_LEFT), // NIK dummy
                'nomor_telepon' => '08' . $phoneSuffix,

                'alamat' => "Jl. Raya Mayang Blok A No. {$i}, RT.19 Kel. Mayang Mangurai",
                'usia' => 35 + $i,
                'jenis_kelamin' => ($i % 2 == 0) ? 'Perempuan' : 'Laki-laki',
                'pekerjaan' => $pekerjaan[$i - 1], // Menggunakan array pekerjaan spesifik
                'role' => 'pengurus', // <<< ROLE PENGURUS

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedWarga(int $count): void
    {
        echo "Menambahkan $count data Warga...\n";
        $firstNamesLaki = ['Budi', 'Rizky', 'Andi', 'Ahmad', 'Dimas', 'Eko', 'Fajar', 'Galih', 'Indra', 'Joko'];
        $firstNamesPerempuan = ['Dewi', 'Siti', 'Ayu', 'Rini', 'Wulan', 'Fitri', 'Nia', 'Laras', 'Maya', 'Tari'];
        $lastNames = ['Pratama', 'Nugraha', 'Wijaya', 'Santoso', 'Hidayat', 'Kusuma', 'Putra', 'Sari', 'Gunawan'];
        $pekerjaanList = ['Karyawan Swasta', 'Wiraswasta', 'PNS', 'Ibu Rumah Tangga', 'Mahasiswa'];
        $jalanList = ['Anggrek', 'Mawar', 'Melati', 'Kenanga', 'Dahlia'];

        for ($i = 1; $i <= $count; $i++) {
            $randomJob = $pekerjaanList[array_rand($pekerjaanList)];
            $randomJalan = $jalanList[array_rand($jalanList)];
            $gender = ($i % 3 == 0) ? 'Perempuan' : 'Laki-laki';

            // 1. Pilih Nama Depan berdasarkan Jenis Kelamin
            if ($gender === 'Laki-laki') {
                $firstName = $firstNamesLaki[array_rand($firstNamesLaki)];
            } else {
                $firstName = $firstNamesPerempuan[array_rand($firstNamesPerempuan)];
            }

            // 2. Pilih Nama Belakang
            $lastName = $lastNames[array_rand($lastNames)];

            // 3. Gabungkan
            $fullName = "{$firstName} {$lastName}"; // Variasi gender

            DB::table('users')->insert([
                'nama_lengkap' => $fullName,
                'email' => "warga{$i}@gmail.com",
                'password' => Hash::make("warga111"), // password: warga111 (sederhana untuk semua)
                'nik' => '367102' . str_pad($i, 10, '0', STR_PAD_LEFT), // NIK dummy
                'nomor_telepon' => '08221234' . str_pad($i, 4, '0', STR_PAD_LEFT), // Nomor HP dummy

                'alamat' => "Jl. {$randomJalan} No. {$i}, RT.19 Kel. Mayang Mangurai",
                'usia' => rand(20, 50),
                'jenis_kelamin' => $gender,
                'pekerjaan' => $randomJob,
                'role' => 'warga', // <<< ROLE WARGA

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

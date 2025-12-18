<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';
    protected $fillable = [
        'title',
        'start', // Add this line
        'end',
        'description',
        'lokasi',
        'image_flyer_path',
        'status',
        'created_by'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function registrations()
    {
        return $this->hasMany(ScheduleRegistration::class, 'activity_id');
    }

    public function getStatusAttribute($value)
    {
        $now = Carbon::now();

        // 1. Cek Manual dari Database
        // Jika admin menekan tombol "Selesai" (manual override), langsung return Selesai.
        if ($value === 'finished') {
            return 'Selesai';
        }

        // 2. Cek Waktu Selesai
        // Jika waktu selesai ada DAN waktu sekarang sudah melewatinya -> Selesai
        if ($this->end && $now->greaterThan($this->end)) {
            return 'Selesai';
        }

        // 3. Cek Waktu Mulai (Berlangsung)
        // Jika sudah melewati jam mulai (dan lolos dari cek no.2), berarti sedang jalan.
        if ($now->greaterThanOrEqualTo($this->start)) {
            return 'Berlangsung';
        }

        // 4. Default: Belum mulai
        return 'Segera';
    }

    public function documentations()
    {
        // Parameter 2 ('activity_id') adalah nama kolom foreign key di tabel documentations
        return $this->hasMany(Documentation::class, 'activity_id');
    }
}

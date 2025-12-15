<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'nik',
        'nomor_telepon',
        'alamat',
        'usia',
        'jenis_kelamin',
        'pekerjaan',
        'profile_photo_path',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isPengurus(): bool
    {
        // Langsung cek apakah role adalah 'pengurus'
        return $this->role === 'pengurus';
    }

    public function isWarga(): bool
    {
        // Cek kebalikannya
        return $this->role === 'warga';
        // ATAU return !$this->isPengurus();
    }

    public function registrations()
    {
        // User memiliki banyak pendaftaran kegiatan
        return $this->hasMany(ScheduleRegistration::class);
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        // Menggunakan path yang sudah disimpan (atau default jika null)
        return $this->profile_photo_path
            ? Storage::url($this->profile_photo_path)
            : asset('images/profile-default.png');
    }
}

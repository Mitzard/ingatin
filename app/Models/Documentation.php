<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documentation extends Model
{
    use HasFactory;
    
    protected $table = 'documentations';
    protected $fillable = [
        'activity_id',
        'file_path',
        'caption',
        'uploaded_by',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'activity_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getPhotoUrlAttribute(): string
    {
        // Fungsi ini mengambil nilai dari kolom 'file_path' dan 
        // mengubahnya menjadi URL yang dapat diakses publik oleh browser.
        return Storage::url($this->file_path);
    }
}

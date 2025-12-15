<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('schedules')->onDelete('cascade');
            
            // File Path dan Keterangan
            $table->string('file_path'); // Path penyimpanan file (storage/app/public)
            $table->string('caption')->nullable();
            
            // Siapa yang mengunggah
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentations');
    }
};

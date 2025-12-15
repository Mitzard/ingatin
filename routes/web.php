<?php

use App\Models\Warga;
use App\Models\ScheduleRegistration;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\ScheduleRegistrationController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about', 'about')->name('about');
});

Route::middleware('auth')->group(function () {
    Route::get('/kalender', [ScheduleController::class, 'index'])->name('warga.calendar');
    Route::get('/events', [ScheduleController::class, 'getEvents'])->name('events.get');

    Route::post('/registrasi-kegiatan/daftar', [ScheduleRegistrationController::class, 'store'])->name('schedules.register.store');
    Route::post('/registrasi_kegiatan/batal/{activityId}', [ScheduleRegistrationController::class, 'destroy'])->name('schedules.register.destroy');
    Route::get('/daftar-kegiatan', [ScheduleRegistrationController::class, 'index'])->name('daftar');
    Route::get('/daftar-kegiatan/{activity}', [ScheduleRegistrationController::class, 'show'])->name('activity.show');

    Route::get('/profil', [WargaController::class, 'profile'])->name('profile');
    Route::post('/profil/update', [WargaController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profil/password', [WargaController::class, 'updatePassword'])->name('password.update');
});

Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/dashboard', [PengurusController::class, 'dashboard'])->name('dashboard');
    // Route::get('/kalender', [ScheduleController::class, 'index'])->name('pengurus.calendar');
    Route::get('/kalender/tambah-kegiatan', [PengurusController::class, 'showCreateForm'])->name('schedules.create');
    Route::get('/kalender/kelola-kegiatan', [PengurusController::class, 'kelolaKegiatan'])->name('kelola.kegiatan');
    Route::get('/kalender/edit/{activity}', [PengurusController::class, 'editKegiatan'])->name('schedules.edit');
    Route::put('/kalender/{activity}', [PengurusController::class, 'updateKegiatan'])->name('schedules.update');
    Route::get('/pengaturan', [PengurusController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan/password', [PengurusController::class, 'updatePengurusPassword'])->name('password.pengurus.update');

    Route::get('/kegiatan/{activity}/pendaftar', [ScheduleRegistrationController::class, 'showPendaftar'])->name('participation.show');

    Route::post('/kegiatan/{activity}/dokumentasi', [DocumentationController::class, 'store'])->name('schedules.documentation.store');

    Route::post('create-schedule', [ScheduleController::class, 'create'])->name('schedules.store');
    // Route::get('/schedule/delete/{id}', [ScheduleController::class, 'deleteEvent']);
    Route::delete('/schedule/delete/{id}', [ScheduleController::class, 'deleteEvent'])->name('schedules.delete');
    Route::post('/schedule/{id}', [ScheduleController::class, 'update']);
    Route::post('/schedule/{id}/resize', [ScheduleController::class, 'resize']);

});

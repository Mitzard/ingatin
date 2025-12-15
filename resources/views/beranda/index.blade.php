@extends('layouts.app')

@section('title', 'Beranda')
@section('content')
    <style>
        .hero-section {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            height: 100vh;
            margin-top: -96px;
            background-image: url({{ asset('images/hero-page.png') }});
        }

        .hero-section::before {
            background-color: rgba(0, 0, 0, 0.5);
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    </style>
    <section class="hero-section w-full position-relative" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.7) 100%);
            z-index: 1;
        ">
        </div>

        <div class="container d-flex align-items-center justify-content-center text-white flex-column position-relative pt-5"
            style="
            height: 120%;
            text-align: center;
            z-index: 2;
        ">
            <h1 class="display-3 fw-semibold mb-3 text-outline-info"
                style="
                font-family: 'Poppins', sans-serif; 
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
                color: #952638;
            ">
                INGAT.IN!
            </h1>

            <p class="lead mb-5"
                style="
                /* Tailwind: text-xl md:text-2xl, Custom Font Clarity */
                font-family: 'Inter', sans-serif; 
                max-width: 700px;
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
                font-weight: 500;
            ">
                Merupakan aplikasi jadwal pengingat, yang akan memandu warga RT. 19 dalam mengelola jadwal, sehingga tidak
                melewatkan jadwal penting yang akan datang di kemudian hari
            </p>

            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="{{route('warga.calendar')}}" class="btn btn-lg px-4 me-sm-3"
                    style="
                    background-color: #952638;
                    border-color: #090a0a;
                    color: white;
                    font-weight: 600;
                    transition: all 0.3s;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
                ">
                    LIHAT KALENDER KEGIATAN
                </a>

                <a href="{{route('about')}}" class="btn btn-outline-light btn-lg px-4"
                    style="
                    /* Tailwind: border-white hover:bg-white/10 */
                    border-width: 2px;
                    font-weight: 600;
                    transition: all 0.3s;
                ">
                    TENTANG KAMI
                </a>
            </div>

        </div>

    </section>

    <section style="background-color: #ffffff;">
        <div class="container py-md-5">

            <div class="text-center mb-5">
                <h2 class="display-5 fw-semibold mb-3" style="color: #952638; font-family: 'Poppins', sans-serif;">
                    FITUR INGAT.IN
                </h2>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4">

                <div class="col">
                    <div class="card h-100 shadow-lg border-0 transition-hover" style="border-radius: 1rem;">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-calendar2-check-fill mb-3" style="font-size: 3rem; color: #952638;"></i>
                            <h4 class="card-title fw-semibold" style="color: #343A40;">Kalender Real-Time</h4>
                            <p class="card-text text-muted">
                                Jangan pernah ketinggalan informasi. Pantau jadwal kegiatan terkini dengan pembaruan instan.
                            </p>
                            {{-- <a href="#kalender" class="btn btn-outline-danger mt-3 fw-semibold">
                                <i class="bi bi-eye me-2"></i> Lihat Kalender
                            </a> --}}
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-lg border-0 transition-hover" style="border-radius: 1rem;">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-person-workspace mb-3" style="font-size: 3rem; color: #952638;"></i>
                            <h4 class="card-title fw-semibold" style="color: #343A40;">Registrasi Online Sekarang</h4>
                            <p class="card-text text-muted">
                                Daftar acara dan kerja bakti semudah beberapa klik. Kelola kehadiran secara digital.
                            </p>
                            <a href="{{route('daftar')}}" class="btn mt-3 fw-semibold text-white" style="background-color: #952638">
                                <i class="bi bi-pencil-square me-2"></i> Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 shadow-lg border-0 transition-hover" style="border-radius: 1rem;">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-archive-fill mb-3" style="font-size: 3rem; color: #952638;"></i>
                            <h4 class="card-title fw-semibold" style="color: #343A40;">Arsip Kegiatan</h4>
                            <p class="card-text text-muted">
                                Telusuri kembali memori kegiatan yang telah terlaksana. Dokumentasi terstruktur dan rapi.
                            </p>
                            {{-- <a href="#arsip" class="btn btn-outline-danger mt-3 fw-bold">
                                <i class="bi bi-folder-fill me-2"></i> Kunjungi Arsip
                            </a> --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    {{-- <div class="container">
        <h2 class="text-3xl md:text-4xl font-bold mb-8 text-gray-800 border-b-4 border-primary pb-3 tracking-tight">
            Statistik Kegiatan <span class="text-primary">RT 19</span>
        </h2>

        <div class="row g-5">

            <div class="col-md-4">
                <div class="card shadow-xl border-0 rounded-3xl transform hover:scale-[1.02] transition duration-300 overflow-hidden"
                    style="background: linear-gradient(135deg, #3B82F6, #1E40AF);">
                    <div class="card-body p-6 text-white">
                        <div class="flex flex-col">
                            <p class="card-text text-5xl font-extrabold tracking-wider mb-2">4</p>

                            <h5 class="card-title text-xl font-semibold mt-1">Kegiatan Bulan Ini</h5>
                            <p class="text-sm opacity-85 mt-2">Jumlah acara yang terjadwal dalam 30 hari ke depan.</p>
                        </div>
                        <i class="fas fa-calendar-alt text-7xl absolute bottom-2.5 right-2.5 opacity-20"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-xl border-0 rounded-3xl transform hover:scale-[1.02] transition duration-300 overflow-hidden"
                    style="background: linear-gradient(135deg, #F59E0B, #B45309);">
                    <div class="card-body p-6 text-white">
                        <div class="flex flex-col">
                            <p class="card-text text-5xl font-extrabold tracking-wider mb-2">2</p>
                            <h5 class="card-title text-xl font-semibold mt-1">Menunggu Pelaksanaan</h5>
                            <p class="text-sm opacity-85 mt-2">Kegiatan yang akan datang dalam waktu dekat.</p>
                        </div>
                        <i class="fas fa-clock text-7xl absolute bottom-2.5 right-2.5 opacity-20"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-xl border-0 rounded-3xl transform hover:scale-[1.02] transition duration-300 overflow-hidden"
                    style="background: linear-gradient(135deg, #10B981, #059669);">
                    <div class="card-body p-6 text-white">
                        <div class="flex flex-col">
                            <p class="card-text text-5xl font-extrabold tracking-wider mb-2">20</p>
                            <h5 class="card-title text-xl font-semibold mt-1">Telah Dilaksanakan</h5>
                            <p class="text-sm opacity-85 mt-2">Total kegiatan yang sukses diarsipkan.</p>
                        </div>
                        <i class="fas fa-check-circle text-7xl absolute bottom-2.5 right-2.5 opacity-20"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

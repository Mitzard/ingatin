@extends('layouts.app')
@section('title', 'Tentang Kami')
@section('content')
    <a href="{{ url('/') }}" class="btn-back">← Kembali</a>

    <div class="hero-section" id="hero">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>

        <h1 class="hero-title">INGAT.IN</h1>
        <p class="hero-desc">
            Website rukun tetangga yang dibuat khusus untuk warga <strong>RT.19</strong><br>
            dengan tujuan memudahkan warga dalam mengatur jadwal, mengingat jadwal,<br>
            hingga melihat riwayat kegiatan.
        </p>
    </div>

    <div class="dev-section" id="developers">
        <h2 class="section-title">Tim Pengembang</h2>

        <div class="dev-container">
            <div class="dev-card">
                <div class="dev-photo-wrapper">
                    <div class="blob-bg"></div> <img src="{{ asset('images/qonita.png') }}" class="dev-photo"
                        alt="Dev 1">
                </div>
                <div class="dev-info">
                    <h3 class="dev-name">Qonita Ghina Anbarputri</h3>
                    <span class="dev-role">UI/UX Designer & Frontend Developer</span>
                </div>
            </div>

            <div class="dev-card">
                <div class="dev-photo-wrapper">
                    <div class="blob-bg" style="animation-delay: -2s;"></div>
                    <img src="{{ asset('images/damara.png') }}"
                        class="dev-photo" alt="Dev 2">
                </div>
                <div class="dev-info">
                    <h3 class="dev-name">Damara Rafiandriza Putra</h3>
                    <span class="dev-role">Fullstack Developer</span>
                </div>
            </div>

            <div class="dev-card">
                <div class="dev-photo-wrapper">
                    <div class="blob-bg" style="animation-delay: -5s;"></div>
                    <img src="{{ asset('images/juliyando.png') }}" class="dev-photo" alt="Dev 3">
                </div>
                <div class="dev-info">
                    <h3 class="dev-name">Juliyando Akbar<br><br></h3>
                    <span class="dev-role">Business Analyst & Frontend Developer</span>
                </div>
            </div>
        </div>
    </div>

    <div class="tutorial-section" id="tutorial">
        <div class="tutorial-bg-animation">
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>
        </div>
        <h2 class="section-title">Panduan Penggunaan</h2>

        <div class="timeline-container">
            <div class="timeline-line-bg"></div>
            <div class="timeline-line-progress"></div>

            <div class="tutorial-step step-anim">
                <div class="step-content">
                    <h3>1. Registrasi Akun</h3>
                    <p>Masukkan nomor handphone, NIK, nama, dan alamat untuk mulai bergabung.</p>
                </div>
                <div class="step-number">1</div>
                <div class="spacer"></div>
            </div>

            <div class="tutorial-step step-anim">
                <div class="spacer"></div>
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>2. Akses Beranda</h3>
                    <p>Lihat jadwal terkini dalam bentuk kalender dan pantau kegiatan RT.</p>
                </div>
            </div>

            <div class="tutorial-step step-anim">
                <div class="step-content">
                    <h3>3. Lihat Pengaturan</h3>
                    <p>Mengganti profil, ubah password demi keamanan, dan logout sistem.</p>
                </div>
                <div class="step-number">3</div>
                <div class="spacer"></div>
            </div>

            <div class="tutorial-step step-anim">
                <div class="spacer"></div>
                <div class="step-number">✓</div>
                <div class="step-content">
                    <h3>Selesai</h3>
                    <p>Selamat menggunakan INGAT.IN!</p>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // 1. HERO ANIMATION (Masuk pelan)
        const tl = gsap.timeline();
        tl.to(".hero-title", {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power3.out"
            })
            .to(".hero-desc", {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power3.out"
            }, "-=0.5");

        // Parallax Effect Background
        gsap.to(".hero-bg", {
            yPercent: 30,
            ease: "none",
            scrollTrigger: {
                trigger: "#hero",
                start: "top top",
                end: "bottom top",
                scrub: true
            }
        });

        // 2. DEVELOPER CARDS (POP UP ELASTIS)
        gsap.utils.toArray(".dev-card").forEach((card, i) => {
            gsap.to(card, {
                opacity: 1,
                scale: 1,
                /* Membesar ke ukuran normal */
                duration: 1,
                ease: "elastic.out(1, 0.5)",
                /* Efek membal (pop up) */
                scrollTrigger: {
                    trigger: "#developers",
                    start: "top 75%",
                    /* Mulai saat area dev masuk layar */
                    delay: i * 0.2 /* Muncul bergantian */
                }
            });
        });

        // 3. TUTORIAL LINE (Menggambar garis)
        gsap.to(".timeline-line-progress", {
            height: "100%",
            ease: "none",
            scrollTrigger: {
                trigger: ".timeline-container",
                start: "top 60%",
                end: "bottom 80%",
                scrub: 1.5
            }
        });

        // Animasi Kotak Tutorial (Muncul dari bawah)
        gsap.utils.toArray(".step-anim").forEach(step => {
            gsap.to(step.querySelector(".step-content"), {
                opacity: 1,
                y: 0,
                duration: 0.6,
                scrollTrigger: {
                    trigger: step,
                    start: "top 80%",
                    onEnter: () => step.querySelector(".step-number").classList.add("active"),
                    onLeaveBack: () => step.querySelector(".step-number").classList.remove("active")
                }
            });
        });
    </script>
@endsection
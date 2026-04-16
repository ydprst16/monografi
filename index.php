<?php
session_start();
include 'conn.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Desa Cantik Kota Dumai</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --primary: #27548a;
            --primary-dark: #1b3b63;
            --soft: #f4f7fb;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        /* Card */
        .card {
            border-radius: 12px;
            transition: .3s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        /* Fade */
        .fade {
            opacity: 0;
            transform: translateY(25px);
            transition: .6s;
        }

        .fade.show {
            opacity: 1;
            transform: none;
        }

        /* ===== SLIDER BUTTON STYLE (UPDATED) ===== */
        .slider-wrapper:hover .slider-btn {
            opacity: 1;
            pointer-events: auto;
        }

        .slider-btn {
            opacity: 0;
            pointer-events: none;

            background: rgba(0, 0, 0, 0.25);
            color: white;
            backdrop-filter: blur(4px);

            /* PERFECT CIRCLE */
            width: 44px;
            height: 44px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 1.5rem;

            border-radius: 50%;
            transition: all 0.25s ease;

            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .slider-btn:hover {
            background: rgba(0, 0, 0, 0.5);
            transform: scale(1.15);
        }
    </style>

</head>

<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    <header class="navbar fixed top-0 w-full shadow-sm z-50">

        <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">

            <div class="flex items-center gap-3">
                <img src="images/logo.png" class="h-9">
                <img src="images/logo2.png" class="h-9">
                <h1 class="font-semibold text-lg text-[var(--primary-dark)]">
                    Desa Cantik
                </h1>
            </div>

            <nav class="hidden md:flex gap-8 text-sm font-medium">
                <a href="#beranda">Beranda</a>
                <a href="#tentang">Tentang</a>
                <a href="#statistik">Statistik</a>
                <a href="#galeri">Galeri</a>
            </nav>

            <button id="menuBtn" class="md:hidden text-xl">☰</button>

        </div>

        <div id="mobileMenu" class="hidden px-6 pb-4 space-y-2">
            <a href="#beranda" class="block">Beranda</a>
            <a href="#tentang" class="block">Tentang</a>
            <a href="#statistik" class="block">Statistik</a>
            <a href="#galeri" class="block">Galeri</a>
            <a href="login.php" class="block font-semibold">Login</a>
        </div>

    </header>


    <!-- HERO -->
    <section id="beranda" class="pt-32 pb-24 bg-white text-center">

        <div class="max-w-4xl mx-auto px-6 fade">

            <h2 class="text-3xl md:text-5xl font-bold text-[var(--primary-dark)] mb-6">
                Sistem Informasi Monografi Desa
            </h2>

            <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                Platform resmi untuk pengelolaan data desa secara terstruktur,
                akurat, dan berkelanjutan oleh Badan Pusat Statistik Kota Dumai.
            </p>

            <a href="login.php"
                class="bg-[var(--primary)] text-white px-6 py-3 rounded-md hover:bg-[var(--primary-dark)] transition">
                Masuk ke Sistem
            </a>

        </div>

    </section>


    <!-- TENTANG -->
    <section id="tentang" class="py-20 bg-[var(--soft)]">

        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center fade">

            <div>
                <h3 class="text-2xl font-bold text-[var(--primary-dark)] mb-4">
                    Tentang Desa Cantik
                </h3>

                <p class="text-gray-600 leading-relaxed">
                    Desa Cantik (Desa Cinta Statistik) merupakan program Badan
                    Pusat Statistik untuk meningkatkan kualitas pengelolaan data
                    desa sehingga pembangunan dapat dilakukan secara tepat sasaran
                    berbasis data.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-lg shadow-sm card text-sm">✔ Data terintegrasi</div>
                <div class="bg-white p-5 rounded-lg shadow-sm card text-sm">✔ Mudah digunakan</div>
                <div class="bg-white p-5 rounded-lg shadow-sm card text-sm">✔ Mendukung analisis</div>
                <div class="bg-white p-5 rounded-lg shadow-sm card text-sm">✔ Berbasis statistik</div>
            </div>

        </div>

    </section>


    <!-- STATISTIK -->
    <section id="statistik" class="py-20 bg-white">

        <div class="max-w-6xl mx-auto px-6 text-center fade">

            <h3 class="text-2xl font-bold text-[var(--primary-dark)] mb-10">
                Statistik Sistem
            </h3>

            <div class="grid md:grid-cols-3 gap-8">

                <div>
                    <p class="text-4xl font-bold text-[var(--primary)]">3</p>
                    <p class="text-gray-600">Kelurahan</p>
                </div>

                <div>
                    <p class="text-4xl font-bold text-[var(--primary)]">120+</p>
                    <p class="text-gray-600">Data Monografi</p>
                </div>

                <div>
                    <p class="text-4xl font-bold text-[var(--primary)]">2026</p>
                    <p class="text-gray-600">Tahun Berjalan</p>
                </div>

            </div>

        </div>

    </section>


    <!-- GALERI SLIDER -->
    <section id="galeri" class="py-20 bg-[var(--soft)]">

        <div class="max-w-6xl mx-auto px-6 fade">

            <h3 class="text-2xl font-bold text-center text-[var(--primary-dark)] mb-10">
                Galeri Kegiatan
            </h3>

            <div class="slider-wrapper relative overflow-hidden rounded-xl">

                <!-- SLIDER -->
                <div id="slider" class="flex transition-transform duration-700">

                    <?php
                    $images = [
                        "images/foto1.jpeg",
                        "images/foto1.jpeg",
                        "images/foto1.jpeg"
                    ];

                    foreach ($images as $img): ?>
                        <div class="min-w-full">
                            <img src="<?= $img ?>" class="w-full h-[300px] md:h-[400px] object-cover">
                        </div>
                    <?php endforeach; ?>

                </div>

                <!-- PREV -->
                <button id="prevBtn" class="slider-btn absolute left-4 top-1/2 -translate-y-1/2">
                    ‹
                </button>

                <!-- NEXT -->
                <button id="nextBtn" class="slider-btn absolute right-4 top-1/2 -translate-y-1/2">
                    ›
                </button>

                <!-- DOT -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    <?php foreach ($images as $i => $img): ?>
                        <span class="dot w-2.5 h-2.5 bg-white/50 rounded-full"></span>
                    <?php endforeach; ?>
                </div>

            </div>

        </div>

    </section>


    <!-- FOOTER -->
    <footer class="relative bg-[#0f172a] text-gray-300 mt-20 pt-14 pb-6 overflow-hidden">

        <!-- subtle pattern -->
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none"
            style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 20px 20px;">
        </div>

        <!-- top accent -->
        <div class="relative max-w-6xl mx-auto px-6 mb-10">
            <div class="h-[2px] w-16 bg-[#27548a]"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-10">

            <!-- BRAND -->
            <div>
                <h4 class="text-white text-lg font-semibold mb-3">
                    BPS Kota Dumai
                </h4>

                <div class="text-sm leading-relaxed space-y-1 opacity-80">
                    <p>Jl. Tuanku Tambusai Dumai - Riau</p>
                    <p>Telp (0765)4300005</p>
                    <p>Email: bps1473@bps.go.id</p>
                </div>
            </div>

            <!-- NAV -->
            <div>
                <h4 class="text-white font-semibold mb-3">
                    Navigasi
                </h4>

                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="#" class="hover:text-white transition">Tentang</a></li>
                    <li><a href="#" class="hover:text-white transition">Statistik</a></li>
                    <li><a href="#" class="hover:text-white transition">Galeri</a></li>
                </ul>
            </div>

            <!-- SOCIAL -->
            <div>
                <h4 class="text-white font-semibold mb-3">
                    Terhubung
                </h4>

                <div class="flex gap-3">

                    <!-- IG -->
                    <a href="#" target="_blank" class="group flex items-center gap-2 px-3 py-2 rounded-md border border-white/10 
            hover:border-pink-500 transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-gray-300 group-hover:text-pink-400 transition" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M7 2C4.24 2 2 4.24 2 7v10c0 2.76 
                2.24 5 5 5h10c2.76 0 5-2.24 
                5-5V7c0-2.76-2.24-5-5-5H7zm5 
                4a6 6 0 110 12 6 6 0 010-12zm0 
                2a4 4 0 100 8 4 4 0 000-8zm4.5-2.5a1 
                1 0 110 2 1 1 0 010-2z" />
                        </svg>

                        <span class="text-sm">Instagram</span>
                    </a>

                    <!-- YT -->
                    <a href="#" target="_blank" class="group flex items-center gap-2 px-3 py-2 rounded-md border border-white/10 
            hover:border-red-500 transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-gray-300 group-hover:text-red-400 transition" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M23 7s-.2-1.5-.8-2.2c-.8-.9-1.7-.9-2.1-1C17.3 
                3.5 12 3.5 12 3.5h0s-5.3 
                0-8.1.3c-.4.1-1.3.1-2.1 
                1C1.2 5.5 1 7 1 7S.8 
                8.8.8 10.6v1.7C.8 14.2 
                1 16 1 16s.2 1.5.8 
                2.2c.8.9 1.9.9 2.4 
                1 1.7.2 7.8.3 7.8.3s5.3 
                0 8.1-.3c.4-.1 1.3-.1 
                2.1-1 .6-.7.8-2.2.8-2.2s.2-1.8.2-3.6v-1.7C23.2 
                8.8 23 7 23 7zM9.75 
                14.5v-5l4.5 2.5-4.5 2.5z" />
                        </svg>

                        <span class="text-sm">YouTube</span>
                    </a>

                    <!-- FB -->
                    <a href="#" target="_blank" class="group flex items-center gap-2 px-3 py-2 rounded-md border border-white/10 
            hover:border-blue-500 transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-gray-300 group-hover:text-blue-400 transition" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M22 12a10 10 0 10-11.5 
                9.9v-7H8v-3h2.5V9.5c0-2.5 
                1.5-3.9 3.8-3.9 1.1 0 
                2.2.2 2.2.2v2.5h-1.2c-1.2 
                0-1.6.8-1.6 1.5V12H17l-.5 
                3h-2.8v7A10 10 0 0022 
                12z" />
                        </svg>

                        <span class="text-sm">Facebook</span>
                    </a>

                </div>
            </div>

        </div>

        <!-- bottom -->
        <div class="relative max-w-6xl mx-auto px-6 mt-12 pt-4 border-t border-white/10 
                flex flex-col md:flex-row justify-between text-sm opacity-70">

            <span>© <?php echo date('Y'); ?> BPS Kota Dumai</span>
            <span>Sistem Informasi Monografi Desa</span>

        </div>

    </footer>


    <script>
        const menuBtn = document.getElementById('menuBtn')
        const mobileMenu = document.getElementById('mobileMenu')

        menuBtn.onclick = () => mobileMenu.classList.toggle('hidden')

        // fade animation
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) e.target.classList.add('show')
            })
        })
        document.querySelectorAll('.fade').forEach(el => observer.observe(el))

        // SLIDER
        let index = 0
        const slider = document.getElementById('slider')
        const total = slider.children.length
        const dots = document.querySelectorAll('.dot')

        const prevBtn = document.getElementById('prevBtn')
        const nextBtn = document.getElementById('nextBtn')

        function updateSlider() {
            slider.style.transform = `translateX(-${index * 100}%)`

            dots.forEach((d, i) => {
                d.classList.remove('bg-white')
                d.classList.add('bg-white/50')
                if (i === index) d.classList.add('bg-white')
            })
        }

        nextBtn.onclick = () => {
            index = (index + 1) % total
            updateSlider()
        }

        prevBtn.onclick = () => {
            index = (index - 1 + total) % total
            updateSlider()
        }

        setInterval(() => {
            index = (index + 1) % total
            updateSlider()
        }, 3000)

        updateSlider()
    </script>

</body>

</html>
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
            --biru: #27548a;
            --biru-tua: #1b3b63;
            --biru-muda: #caf0f8;
        }

        html {
            scroll-behavior: smooth;
        }

        .hero-overlay {
            background: linear-gradient(to bottom,
                    rgba(255, 255, 255, 0.75),
                    rgba(255, 255, 255, 0.95));
        }

        .gallery-img {
            transition: .4s;
        }

        .gallery-img:hover {
            transform: scale(1.05);
        }

        .fade {
            opacity: 0;
            transform: translateY(20px);
            transition: .6s;
        }

        .fade.show {
            opacity: 1;
            transform: none;
        }
    </style>

</head>

<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    <header class="fixed top-0 left-0 w-full bg-[var(--biru)] text-white shadow z-50">

        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">

            <div class="flex items-center gap-4">
                <img src="images/logo.png" class="h-9">
                <img src="images/logo2.png" class="h-9">
                <h1 class="font-semibold text-lg md:text-xl">
                    Desa Cantik Kota Dumai
                </h1>
            </div>

            <nav class="hidden md:flex gap-6 text-sm font-medium items-center">

                <a href="#beranda" class="hover:text-[var(--biru-muda)]">Beranda</a>
                <a href="#tentang" class="hover:text-[var(--biru-muda)]">Tentang</a>
                <a href="#statistik" class="hover:text-[var(--biru-muda)]">Statistik</a>
                <a href="#galeri" class="hover:text-[var(--biru-muda)]">Galeri</a>

                <a href="login.php"
                    class="bg-white text-[var(--biru)] px-4 py-1.5 rounded-md font-semibold hover:bg-[var(--biru-muda)] transition">
                    Login
                </a>

            </nav>

            <button id="menuBtn" class="md:hidden text-2xl">
                ☰
            </button>

        </div>

        <div id="mobileMenu" class="hidden md:hidden bg-[var(--biru)] px-6 pb-4">

            <a href="#beranda" class="block py-2">Beranda</a>
            <a href="#tentang" class="block py-2">Tentang</a>
            <a href="#statistik" class="block py-2">Statistik</a>
            <a href="#galeri" class="block py-2">Galeri</a>
            <a href="login.php" class="block py-2 font-semibold">Login</a>

        </div>

    </header>


    <!-- HERO -->
    <section id="beranda" class="relative min-h-screen flex items-center bg-cover bg-center"
        style="background-image:url('images/bg4.jpg')">

        <div class="absolute inset-0 hero-overlay"></div>

        <div class="relative max-w-3xl mx-auto text-center px-6 fade">

            <h2 class="text-4xl md:text-5xl font-bold text-[var(--biru-tua)] mb-6">
                Desa Cantik Kota Dumai
            </h2>

            <p class="text-lg text-gray-700 mb-8 leading-relaxed">
                Sistem Informasi Monografi Desa Cinta Statistik yang
                dikembangkan oleh <b>BPS Kota Dumai</b> untuk mendukung
                desa dan kelurahan berbasis data.
            </p>

            <a href="login.php"
                class="bg-[var(--biru)] text-white px-7 py-3 rounded-md shadow hover:bg-[var(--biru-tua)] transition">
                Masuk ke Sistem
            </a>

        </div>

    </section>


    <!-- TENTANG -->
    <section id="tentang" class="py-24 bg-white">

        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center fade">

            <div>

                <h3 class="text-3xl font-bold text-[var(--biru-tua)] mb-4">
                    Program Desa Cantik
                </h3>

                <p class="text-gray-700 leading-relaxed">
                    Desa Cantik (Desa Cinta Statistik) adalah program
                    Badan Pusat Statistik yang bertujuan meningkatkan
                    kapasitas desa dalam pengelolaan data pembangunan.

                    Program ini membantu desa dalam mengelola data
                    secara sistematis sehingga pembangunan dapat
                    lebih tepat sasaran.
                </p>

            </div>

            <div class="bg-gray-50 p-8 rounded-xl shadow-sm">

                <h4 class="font-semibold text-lg mb-4 text-[var(--biru)]">
                    Manfaat Program
                </h4>

                <ul class="space-y-3 text-gray-700">

                    <li>✔ Meningkatkan literasi statistik</li>
                    <li>✔ Pengelolaan data desa lebih baik</li>
                    <li>✔ Mendukung perencanaan pembangunan</li>
                    <li>✔ Desa berbasis data</li>

                </ul>

            </div>

        </div>

    </section>


    <!-- STATISTIK -->
    <section id="statistik" class="py-24 bg-gray-100">

        <div class="max-w-6xl mx-auto px-6 text-center fade">

            <h3 class="text-3xl font-bold text-[var(--biru-tua)] mb-12">
                Statistik Desa Cantik
            </h3>

            <div class="grid md:grid-cols-3 gap-8">

                <div class="bg-white p-8 rounded-xl shadow">

                    <p class="text-4xl font-bold text-[var(--biru)]">3</p>
                    <p class="text-gray-600 mt-2">
                        Kelurahan Desa Cantik
                    </p>

                </div>

                <div class="bg-white p-8 rounded-xl shadow">

                    <p class="text-4xl font-bold text-[var(--biru)]">120+</p>
                    <p class="text-gray-600 mt-2">
                        Data Monografi
                    </p>

                </div>

                <div class="bg-white p-8 rounded-xl shadow">

                    <p class="text-4xl font-bold text-[var(--biru)]">2026</p>
                    <p class="text-gray-600 mt-2">
                        Tahun Program
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- GALERI -->
    <section id="galeri" class="py-24 bg-white">

        <div class="max-w-6xl mx-auto px-6 fade">

            <h3 class="text-3xl font-bold text-center text-[var(--biru-tua)] mb-12">
                Galeri Kegiatan
            </h3>

            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">

                <div class="bg-white rounded-xl shadow hover:shadow-lg overflow-hidden">
                    <img src="images/foto1.jpeg" class="gallery-img w-full aspect-[16/9] object-cover">
                    <p class="p-4 font-medium">Sosialisasi Program</p>
                </div>

                <div class="bg-white rounded-xl shadow hover:shadow-lg overflow-hidden">
                    <img src="images/foto1.jpeg" class="gallery-img w-full aspect-[16/9] object-cover">
                    <p class="p-4 font-medium">Pelatihan Statistik</p>
                </div>

                <div class="bg-white rounded-xl shadow hover:shadow-lg overflow-hidden">
                    <img src="images/foto1.jpeg" class="gallery-img w-full aspect-[16/9] object-cover">
                    <p class="p-4 font-medium">Kegiatan Lapangan</p>
                </div>

            </div>

        </div>

    </section>


    <!-- FOOTER -->
    <footer class="bg-[var(--biru)] text-white py-10">

        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-6 items-center">

            <div>

                <h4 class="font-semibold text-lg">
                    BPS Kota Dumai
                </h4>

                <p class="text-sm opacity-90">
                    Sistem Informasi Monografi Desa Cantik
                </p>

            </div>

            <div class="text-right text-sm opacity-80">
                ©
                <?php echo date('Y'); ?> Badan Pusat Statistik Kota Dumai
            </div>

        </div>

    </footer>


    <script>

        const menuBtn = document.getElementById('menuBtn')
        const mobileMenu = document.getElementById('mobileMenu')

        menuBtn.onclick = () => {
            mobileMenu.classList.toggle('hidden')
        }


        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show')
                }
            })
        })

        document.querySelectorAll('.fade').forEach(el => {
            observer.observe(el)
        })

    </script>

</body>

</html>
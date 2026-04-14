<?php
session_start();
include 'conn.php';

/* contoh statistik dari database */
$jumlah_wilayah = 0;

$q1 = mysqli_query($conn, "SELECT COUNT(*) as jml FROM wilayah");
if ($q1) {
    $d1 = mysqli_fetch_assoc($q1);
    $jumlah_wilayah = $d1['jml'];
}
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

        body {
            background: #f8fafc;
        }

        /* hero overlay */
        .hero-overlay {
            background: linear-gradient(to bottom,
                    rgba(255, 255, 255, 0.75),
                    rgba(255, 255, 255, 0.95));
        }

        /* gallery hover */
        .gallery-img {
            transition: .4s;
        }

        .gallery-img:hover {
            transform: scale(1.05);
        }

        /* animation */
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

<body class="text-gray-800">

    <!-- LOADING -->
    <div id="loader" class="fixed inset-0 bg-white flex items-center justify-center z-[9999]">
        <div class="text-center">
            <div class="animate-spin rounded-full h-14 w-14 border-4 border-blue-400 border-t-transparent mx-auto">
            </div>
            <p class="mt-4 text-gray-600">Memuat halaman...</p>
        </div>
    </div>


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
    <section id="beranda" class="relative min-h-screen flex items-center">

        <div class="absolute inset-0">

            <img id="heroImage" src="images/bg1.jpg" class="w-full h-full object-cover">

        </div>

        <div class="absolute inset-0 hero-overlay"></div>

        <div class="relative max-w-3xl mx-auto text-center px-6 fade">

            <h2 class="text-5xl font-bold text-[var(--biru-tua)] mb-6">
                Desa Cantik Kota Dumai
            </h2>

            <p class="text-lg text-gray-700 mb-8 leading-relaxed">
                Sistem Informasi Monografi Desa Cinta Statistik oleh
                <b>BPS Kota Dumai</b>
            </p>

            <a href="login.php"
                class="bg-[var(--biru)] text-white px-8 py-3 rounded-md shadow hover:bg-[var(--biru-tua)] transition">
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
                    Desa Cantik merupakan program Badan Pusat Statistik yang bertujuan
                    meningkatkan kapasitas desa dalam pengelolaan data pembangunan berbasis statistik.
                </p>

            </div>

            <div class="bg-gray-50 p-8 rounded-xl shadow-sm">

                <h4 class="font-semibold text-lg mb-4 text-[var(--biru)]">
                    Manfaat Program
                </h4>

                <ul class="space-y-3 text-gray-700">
                    <li>✔ Literasi statistik desa</li>
                    <li>✔ Pengelolaan data desa</li>
                    <li>✔ Pembangunan berbasis data</li>
                    <li>✔ Perencanaan lebih tepat</li>
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
                    <p class="text-4xl font-bold text-[var(--biru)] counter">
                        <?php echo $jumlah_wilayah ?>
                    </p>
                    <p class="text-gray-600 mt-2">Kelurahan</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow">
                    <p class="text-4xl font-bold text-[var(--biru)] counter">120</p>
                    <p class="text-gray-600 mt-2">Data Monografi</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow">
                    <p class="text-4xl font-bold text-[var(--biru)] counter">2025</p>
                    <p class="text-gray-600 mt-2">Tahun Program</p>
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

                <img src="images/foto1.jpeg" onclick="openImage(this.src)"
                    class="gallery-img cursor-pointer w-full aspect-[16/9] object-cover rounded shadow">

                <img src="images/foto1.jpeg" onclick="openImage(this.src)"
                    class="gallery-img cursor-pointer w-full aspect-[16/9] object-cover rounded shadow">

                <img src="images/foto1.jpeg" onclick="openImage(this.src)"
                    class="gallery-img cursor-pointer w-full aspect-[16/9] object-cover rounded shadow">

            </div>

        </div>

    </section>


    <!-- DASHBOARD PREVIEW -->
    <section class="py-24 bg-gray-100">

        <div class="max-w-6xl mx-auto px-6 text-center fade">

            <h3 class="text-3xl font-bold text-[var(--biru-tua)] mb-12">
                Sistem Informasi Monografi
            </h3>

            <img src="images/dashboard-preview.png" class="rounded-xl shadow-lg mx-auto">

            <p class="mt-6 text-gray-600">
                Kelola data monografi desa secara digital dan terintegrasi.
            </p>

        </div>

    </section>


    <!-- FOOTER -->
    <footer class="bg-[var(--biru)] text-white py-10">

        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-6 items-center">

            <div>
                <h4 class="font-semibold text-lg">BPS Kota Dumai</h4>
                <p class="text-sm opacity-90">Sistem Informasi Monografi Desa Cantik</p>
            </div>

            <div class="text-right text-sm opacity-80">
                ©
                <?php echo date('Y'); ?> BPS Kota Dumai
            </div>

        </div>

    </footer>


    <!-- POPUP GALERI -->
    <div id="popup" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center z-50">

        <img id="popupImg" class="max-h-[80vh] rounded shadow">

    </div>


    <script>

        /* loading */
        window.addEventListener("load", function () {
            document.getElementById("loader").style.display = "none";
        });

        /* mobile menu */
        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        menuBtn.onclick = () => {
            mobileMenu.classList.toggle('hidden');
        }

        /* hero slider */
        const images = [
            "images/bg1.jpg",
            "images/bg2.jpg",
            "images/bg3.jpg"
        ];

        let i = 0;

        setInterval(() => {
            i = (i + 1) % images.length;
            document.getElementById("heroImage").src = images[i];
        }, 5000);

        /* counter */
        const counters = document.querySelectorAll('.counter');

        counters.forEach(counter => {
            const target = +counter.innerText;
            let count = 0;
            const step = Math.ceil(target / 100);

            function run() {
                count += step;

                if (count < target) {
                    counter.innerText = count;
                    setTimeout(run, 20);
                } else {
                    counter.innerText = target;
                }
            }

            run();
        });

        /* scroll animation */
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
            })
        });

        document.querySelectorAll('.fade').forEach(el => {
            observer.observe(el);
        });

        /* galeri popup */
        function openImage(src) {
            document.getElementById("popup").classList.remove("hidden");
            document.getElementById("popupImg").src = src;
        }

        document.getElementById("popup").onclick = function () {
            this.classList.add("hidden");
        }

    </script>

</body>

</html>
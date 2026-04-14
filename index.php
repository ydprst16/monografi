<?php
echo "PHP OK";
session_start();
include 'conn.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Desa Cantik Kota Dumai</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body {
      background-color: #fdfdfd;
    }

    :root {
      --biru-laut: #27548a;
      --biru-laut-tua: #457b9d;
      --biru-laut-muda: #caf0f8;
    }
  </style>
</head>

<body class="text-gray-800">
  <!-- Navbar -->
  <header class="fixed top-0 left-0 w-full z-50 bg-[var(--biru-laut)] text-white py-4 shadow-md">
    <div class="container mx-auto flex items-center px-2 justify-between">
      <div class="flex items-center space-x-4">
        <img src="images/logo.png" alt="Logo 1" class="h-8 w-auto" />
        <img src="images/logo2.png" alt="Logo 2" class="h-8 w-auto" />
        <h1 class="text-xl font-bold">Desa Cantik Kota Dumai</h1>
      </div>
      <nav class="space-x-4">
        <a href="#beranda" class="hover:underline">Beranda</a>
        <a href="#tentang" class="hover:underline">Tentang</a>
        <a href="#galeri" class="hover:underline">Galeri</a>
        <a href="login.php"
          class="bg-white text-[var(--biru-laut)] px-3 py-1 rounded hover:bg-[var(--biru-laut-muda)]">Login</a>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section id="beranda" class="relative min-h-screen flex items-center bg-cover bg-center"
    style="background-image: url('images/bg4.jpg')">
    <div class="absolute inset-0 bg-white bg-opacity-80"></div>
    <div class="relative z-10 max-w-3xl mx-auto text-center px-6">
      <h2 class="text-4xl font-bold mb-4 text-[var(--biru-laut-tua)]">
        Selamat Datang
      </h2>
      <p class="text-lg text-gray-700">
        Website ini merupakan Sistem Informasi Input Data Monografi Desa Cinta
        Statistik oleh <strong>BPS Kota Dumai</strong>. Mari bersama
        mewujudkan desa/kelurahan yang unggul berbasis data!
      </p>
    </div>
  </section>

  <!-- Tentang Section -->
  <section id="tentang" class="py-36 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
      <h3 class="text-2xl font-semibold mb-4 text-[var(--biru-laut-tua)]">
        Tentang Desa Cantik
      </h3>
      <p class="text-gray-700">
        Desa Cantik (Desa Cinta Statistik) adalah program dari Badan Pusat
        Statistik yang bertujuan meningkatkan kapasitas desa dalam pengelolaan
        data untuk pembangunan. Desa Cantik Kota Dumai merupakan bagian dari
        inisiatif ini.
      </p>
    </div>
  </section>

  <!-- Galeri Section -->
  <section id="galeri" class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
      <h3 class="text-2xl font-semibold mb-8 text-center text-[var(--biru-laut-tua)]">
        Galeri
      </h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div>
          <img src="images/foto1.jpeg" alt="Kegiatan 1" class="w-full aspect-[16/9] object-cover rounded shadow" />
          <p class="mt-2 text-gray-700">Sosialisasi Program</p>
        </div>
        <div>
          <img src="images/foto1.jpeg" alt="Kegiatan 2" class="w-full aspect-[16/9] object-cover rounded shadow" />
          <p class="mt-2 text-gray-700">Pelatihan Statistik</p>
        </div>
        <div>
          <img src="images/foto1.jpeg" alt="Kegiatan 3" class="w-full aspect-[16/9] object-cover rounded shadow" />
          <p class="mt-2 text-gray-700">Kegiatan Lapangan</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-[var(--biru-laut)] text-white py-6 text-center">
    <p>
      &copy;
      <?php echo date('Y'); ?>
      BPS Kota Dumai.
    </p>
  </footer>
</body>

</html>
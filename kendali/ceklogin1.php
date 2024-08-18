<?php
session_start();

// Cek apakah sesi pengguna ada
if (!isset($_SESSION['username'])) {
    // Jika tidak ada sesi pengguna, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Cek level pengguna, hanya level 1 yang boleh mengakses halaman ini
if ($_SESSION['level'] != 1) {
    // Jika level bukan 1, arahkan ke halaman akses ditolak
    header("Location: index.php"); // Ganti dengan halaman yang sesuai
    exit();
}


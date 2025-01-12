<?php
ob_start();
session_start();

// Cek apakah sesi pengguna ada
if (!isset($_SESSION['username'])) {
    // Jika tidak ada sesi pengguna, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
?>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistem Pendukung Keputusan Lahan Padi dengan kombinasi metode SAW dan TOPSIS" />
    <meta name="author" content="Syaffira" />
    <link rel="short icon" href="assets/img/padi.png" />

    <!-- Link ke CSS -->
    <base href="http://localhost/spklahanpadi/">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/admin.css" rel="stylesheet" />
    <!-- Skrip JavaScript -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous" defer></script>
    <script src="js/scripts.js" defer></script>
    <!-- Optional script for development purposes -->
    <script async data-id="five-server" src="http://localhost:5500/fiveserver.js"></script>

    <title>Admin | SPK Lahan Padi</title>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark" style="background-color: #2E5077 ;">
        <button class="btn btn-link btn-sm" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <div class="navbar-container">
            <a class="navbar-brand" href="admin.php">SPK Lahan Padi</a>
        </div>
        <!-- Navbar dropdown-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Admin <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="kendali/logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Awal Bar Samping -->
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" style="background-color: #2E5077 ;" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a id="admin-link" class="nav-link " href="admin.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                            Beranda
                        </a>
                        <a id="kriteria-link" class="nav-link " href="d_kriteria.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                            Data Kriteria
                        </a>
                        <a id="subkriteria-link" class="nav-link " href="d_subkriteria.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder-open"></i></div>
                            Data Sub Kriteria
                        </a>
                        <a id="alternatif-link" class="nav-link " href="d_alternatif.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder-tree"></i></div>
                            Data Alternatif
                        </a>
                        <a id="perhitungan-link" class="nav-link " href="d_perhitungan.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder-plus"></i> </div>
                            Data Perhitungan
                        </a>
                        <a id="hasil-link" class="nav-link " href="d_hasil.php">
                            <div class="sb-nav-link-icon"><i class="fa-regular fa-credit-card"></i></div>
                            Data Hasil
                        </a>
                    </div>
            </nav>
        </div>
        <!-- Akhir Bar Samping -->
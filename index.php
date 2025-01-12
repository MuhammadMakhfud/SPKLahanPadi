<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistem Pendukung Keputusan Lahan Padi dengan kombinasi metode SAW dan TOPSIS" />
    <meta name="author" content="Syaffira" />
    <title>SPK Lahan Padi</title>
    <link rel="short icon" href="assets/img/padi.png" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/user.css" rel="stylesheet" />
    <script async data-id="five-server" src="http://localhost:5500/fiveserver.js"></script>
</head>

<body>
    <nav class="navbar sticky-top navbar-dark" style="background-color: #2E5077;">
        <a class="navbar-brand no-hover" href="index.php" style="font-weight: bold; color: white;">SPK Lahan Padi</a>
        <div class="navbar-nav d-flex flex-row align-items-center">
            <!-- Navbar Links -->
            <a class="navbar-brand active" href="index.php">Beranda</a>
            <a class="navbar-brand" href="data.php">Data coba</a>
        </div>
    </nav>

    <!-- Akhir Navbar -->

    <!-- Awal Isi -->
    <main>
        <div class="container-fluid px-4">
            <h3 class="mt-4 mb-4">
                <i class="fa-solid fa-house"></i>
                Selamat Datang di SPK Lahan Padi
            </h3>
            <ol class="breadcrumb mb-2"></ol>
            <div class="row">
                <div class="col-md-6 ">
                    <ul class="list-unstyled text-justify">
                        <li>
                            Tanaman padi (<b>Oryza Sativa L.</b>) adalah komoditas pangan utama di Indonesia. Namun, rendahnya produksi padi sering kali disebabkan oleh <b>pengelolaan lahan</b> yang kurang tepat. Sebagai pengguna, sistem ini <b>membantu</b> Anda untuk:
                        </li>
                        <h6></h6>
                        <li>
                            <ul>
                                <li>Melihat <b>evaluasi</b> kesesuaian lahan untuk tanaman padi.</li>
                                <li>Menerima <b>rekomendasi</b> lahan yang cocok berdasarkan kondisi yang ada.</li>
                                <li>Membandingkan <b>kondisi</b> lahan dengan persyaratan tumbuh tanaman padi.</li>
                            </ul>
                        </li>
                        <h6></h6>
                        <li>
                            Sistem ini menggunakan kombinasi metode <b>SAW</b> dan <b>TOPSIS</b> dalam pengambilan keputusan.
                        </li>
                        <h6></h6>
                        <li>
                            Dengan menggunakan sistem ini, Anda dapat memilih jenis <b>tanaman padi</b> yang paling sesuai dengan kondisi <b>lahan</b>, sehingga meningkatkan <b>produktivitas</b> dan mengurangi risiko <b>kesalahan</b> dalam pemilihan lahan.
                        </li>

                        <h6></h6>
                        <li>
                            <b>Optimalkan</b> lahan Anda untuk hasil pertanian terbaik!
                        </li>
                    </ul>
                </div>
                <div class="col-md-5">
                    <img src="assets/img/sawah.jpg" alt="Deskripsi gambar" class="custom-img">
                </div>
            </div>
        </div>

    </main>
    <!-- Akhir Isi -->

    <!-- Awal Footer -->
    <footer class="py-4 mt-auto" style="background-color: #2E5077 ;">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-center small">
                <div class="text-muted">Copyright &copy; SPK Lahan Padi 2024</div>
            </div>
        </div>
    </footer>
    <!-- Akhir Footer -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple.js"></script>
</body>

</html>
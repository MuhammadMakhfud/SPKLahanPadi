<!DOCTYPE html>
<html lang="en">

<?php
require_once 'layout/head.php';
require_once 'kendali/fungsi.php';

setActiveNavLink('admin-link');

?>

<div id="layoutSidenav_content">
    <!-- Awal Konten -->
    <main>
        <div class="container-fluid px-4">
            <h3 class="mt-4">
                <i class="fa-solid fa-house"></i>
                Selamat Datang di SPK Lahan Padi - Admin
            </h3>
            <ol class="breadcrumb mb-4"></ol>
            <div class="row">
                <div class="col-md-6 ">
                    <ul class="list-unstyled text-justify">
                        <li>
                            Tanaman padi (<b>Oryza Sativa L.</b>) adalah komoditas pangan utama di Indonesia. Namun, rendahnya produksi padi sering kali disebabkan oleh <b>pengelolaan lahan</b> yang kurang tepat. Sebagai admin, Anda memiliki peran penting dalam <b>mengelola data lahan</b>, kriteria evaluasi, serta memastikan <b>sistem pendukung keputusan</b> berjalan dengan baik. Dengan sistem ini, Anda dapat:
                        </li>
                        <h6></h6>
                        <li>
                            <ul>
                                <li>Memantau dan mengelola <b>data lahan</b>.</li>
                                <li>Menyusun dan memperbarui <b>kriteria kesesuaian</b>.</li>
                                <li>Menyediakan <b>rekomendasi akurat</b> untuk petani.</li>
                            </ul>
                        </li>
                        <h6></h6>
                        <li>
                            Sistem ini menggunakan kombinasi metode <b>SAW</b> dan <b>TOPSIS</b> dalam pengambilan keputusan. <b>Meningkatkan produktivitas</b> dengan membandingkan kondisi lahan dengan kebutuhan tumbuh tanaman padi.
                        </li>
                        <h6></h6>
                        <li>
                            <b>Mari bersama-sama</b> mengelola sistem ini untuk hasil yang lebih baik bagi petani!
                        </li>
                    </ul>
                </div>
                <div class="col-md-5">
                    <img src="assets/img/sawah.jpg" alt="Deskripsi gambar" class="custom-img">
                </div>
            </div>
        </div>
    </main>
    <!-- Akhir Konten -->
    <?php include 'layout/footer.php'; ?>

</div>

</html>
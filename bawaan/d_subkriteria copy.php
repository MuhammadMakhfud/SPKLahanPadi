<!DOCTYPE html>
<html lang="en">

<?php
include 'layout/head.php';
include 'kendali/koneksi.php'; 

?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var navLink = document.getElementById('subkriteria-link');
        if (navLink) {
            navLink.classList.add('active');
        }
    });
</script>

<div id="layoutSidenav_content">

    <!-- Awal Konten -->
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">
                <i class="fa-solid fa-folder-open"></i>
                Data Sub Kriteria
            </h1>
            <ol class="breadcrumb mb-4"></ol>

            <div class="row">
                <!-- Tabel 1 -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div id="headtabel">
                                <i class="fas fa-table me-1"></i>
                                Jenis Tanah | C1
                            </div>
                            <a href="tambah-url" title="Tambah">
                                <i class="fa-solid fa-square-plus" alt="Tambah" style="width: 24px; height: 24px; color: #000000"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Tanah</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Tanah Gambut</td>
                                        <td>2</td>
                                        <td>
                                            <a href="edit-url" title="Edit">
                                                <i class="fa-solid fa-pen-to-square" alt="Edit" style="width: 18px; height: 18px;"></i>
                                            </a>
                                            <a href="#" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="fa-solid fa-trash" alt="Delete" style="width: 18px; height: 18px; color: #ff0000;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Tabel 2 -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-table me-1"></i>
                                pH Tanah | C2
                            </div>
                            <a href="tambah-url" title="Tambah">
                                <i class="fa-solid fa-square-plus" alt="Tambah" style="width: 24px; height: 24px; color: #000000"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>pH Tanah</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            < 4.5</td>
                                        <td>1</td>
                                        <td>
                                            <a href="edit-url" title="Edit">
                                                <i class="fa-solid fa-pen-to-square" alt="Edit" style="width: 18px; height: 18px;"></i>
                                            </a>
                                            <a href="#" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="fa-solid fa-trash" alt="Delete" style="width: 18px; height: 18px; color: #ff0000;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Tabel 3 -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-table me-1"></i>
                                Curah Hujan | C3
                            </div>
                            <a href="tambah-url" title="Tambah">
                                <i class="fa-solid fa-square-plus" alt="Tambah" style="width: 24px; height: 24px; color: #000000"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple3">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Curah Hujan</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            < 200mm</td>
                                        <td>1</td>
                                        <td>
                                            <a href="edit-url" title="Edit">
                                                <i class="fa-solid fa-pen-to-square" alt="Edit" style="width: 18px; height: 18px;"></i>
                                            </a>
                                            <a href="#" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="fa-solid fa-trash" alt="Delete" style="width: 18px; height: 18px; color: #ff0000;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Tabel 4 -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-table me-1"></i>
                                Suhu | C4
                            </div>
                            <a href="tambah-url" title="Tambah">
                                <i class="fa-solid fa-square-plus" alt="Tambah" style="width: 24px; height: 24px; color: #000000"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple4">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Suhu</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            < 16c</td>
                                        <td>5</td>
                                        <td>
                                            <a href="edit-url" title="Edit">
                                                <i class="fa-solid fa-pen-to-square" alt="Edit" style="width: 18px; height: 18px;"></i>
                                            </a>
                                            <a href="#" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="fa-solid fa-trash" alt="Delete" style="width: 18px; height: 18px; color: #ff0000;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Tabel 5 -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-table me-1"></i>
                                Irigasi & Perairan | C5
                            </div>
                            <a href="tambah-url" title="Tambah">
                                <i class="fa-solid fa-square-plus" alt="Tambah" style="width: 24px; height: 24px; color: #000000"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple5">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Irigasi & Perairan</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Irigasi Permukaan</td>
                                        <td>1</td>
                                        <td>
                                            <a href="edit-url" title="Edit">
                                                <i class="fa-solid fa-pen-to-square" alt="Edit" style="width: 18px; height: 18px;"></i>
                                            </a>
                                            <a href="#" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="fa-solid fa-trash" alt="Delete" style="width: 18px; height: 18px; color: #ff0000;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <!-- Akhir Konten -->
    <?php include 'layout\footer.php'; ?>
</div>

</html>
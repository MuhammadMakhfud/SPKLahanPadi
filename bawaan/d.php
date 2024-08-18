<!DOCTYPE html>
<html lang="en">

<?php include 'layout\head.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var navLink = document.getElementById('alternatif-link');
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
                <i class="fa-solid fa-folder-tree "></i>
                Data Alternatiff
            </h1>
            <ol class="breadcrumb mb-4"></ol>
            <div id="tabel1" class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-table me-1"></i>
                        Alternatif
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
                                <th>Kode Alternatif</th>
                                <th>Nama Alternatif</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>A1</td>
                                <td>Sungai Kunjang</td>
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
    </main>
    <!-- Akhir Konten -->
    <?php include 'layout\footer.php'; ?>
</div>

</html>
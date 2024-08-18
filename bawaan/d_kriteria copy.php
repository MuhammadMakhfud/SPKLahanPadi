<!DOCTYPE html>
<html lang="en">

<?php
include 'layout/head.php';
include '../kendali/koneksi.php';

// Menghitung total bobot
$query       = "SELECT SUM(bobot) AS total_bobot FROM kriteria";
$result      = $db->query($query);
$row         = $result->fetch_assoc();
$total_bobot = $row['total_bobot'];
$total_bobot = round($total_bobot * 100, 2); // Mengubah total bobot ke skala 100% dan membulatkan ke 2 desimal

// Menentukan jenis alert
$alert_message = '';
if ($total_bobot < 100) {
    $alert_message = '<div class="alert alert-warning" role="alert">Total bobot kurang dari 100%. Mohon periksa kembali data kriteria Anda.</div>';
} elseif ($total_bobot > 100) {
    $alert_message = '<div class="alert alert-danger" role="alert">Total bobot lebih dari 100%. Mohon periksa kembali data kriteria Anda.</div>';
} 

?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var navLink = document.getElementById('kriteria-link');
        if (navLink) {
            navLink.classList.add('active');
        }
    });
</script>


<body>
    <div id="layoutSidenav_content">
        <!-- Awal Konten -->
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">
                    <i class="fa-solid fa-folder"></i>
                    Data Kriteria
                </h1>
                <ol class="breadcrumb mb-4"></ol>
                <div id="tabel1" class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i>
                            Kriteria
                        </div>
                        <a class="fa-solid fa-square-plus" type="button" title="Tambah" data-bs-toggle="modal" data-bs-target="#tambahdata">
                        </a>
                        <?php
                        $existing_names_query = "SELECT nama FROM kriteria";
                        $existing_names_result = $db->query($existing_names_query);
                        $existing_names = [];
                        while ($row = $existing_names_result->fetch_assoc()) {
                            $existing_names[] = $row['nama'];
                        }  ?>
                    </div>
                    <div class="card-body">
                        <!-- Menampilkan alert jika total bobot tidak sama dengan 100 -->
                        <?php if ($alert_message): ?>
                            <?= $alert_message; ?>
                        <?php endif; ?>
                        <table id="datatablesSimple1" class="table table-striped">

                            <thead>
                                <tr>
                                    <th>Kode Kriteria</th>
                                    <th>Nama Kriteria</th>
                                    <th>Atribut</th>
                                    <th>Bobot</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM kriteria ORDER BY kode ASC";
                                $result = $db->query($query);
                                while ($row = $result->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td><?= $row['kode']; ?></td>
                                        <td><?= $row['nama']; ?></td>
                                        <td><?= $row['atribut']; ?></td>
                                        <td><?= $row['bobot'] * 100; ?>%</td>
                                        <td>
                                            <a class="btn-icon-primary" type="button" title="Edit" data-bs-toggle="modal" data-bs-target="#editdata<?= $row['id']; ?>">
                                                <i class="fa-solid fa-pen-to-square" alt="Edit" style="width: 18px; height: 18px;"></i>
                                            </a>
                                            <a class="btn-icon" type="button" title="Delete" data-bs-toggle="modal" data-bs-target="#hapusnotif<?= $row['id']; ?>">
                                                <i class="fa-solid fa-trash" alt="Delete" style="width: 18px; height: 18px; color: red"></i>
                                            </a>
                                            <?php include 'modal/ktr.php'; ?>
                                        </td>
                                    </tr>
                                <?php endwhile;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </main>

        <!-- Footer -->
        <?php include 'layout/footer.php'; ?>
    </div>
</body>

</html>
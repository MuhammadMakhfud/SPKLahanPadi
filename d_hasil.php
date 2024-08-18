<!DOCTYPE html>
<html lang="en">

</html>

<?php
require_once 'layout/head.php';
require_once 'kendali/koneksi.php';
require_once 'kendali/fungsi.php';

setActiveNavLink('hasil-link')
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">
                <i class="fa-regular fa-credit-card"></i>
                Data Hasil
            </h1>
            <ol class="breadcrumb mb-4"></ol>
            <div id="tabel1" class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    DataTable Example
                </div>
                <div class="card-body">
                    <table class="datatabel">
                        <thead>
                            <tr>
                                <th>Kode Alternatif</th>
                                <th>Nama Alternatif</th>
                                <th>Rangking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            updateHasilTable($db);
                            $dataHasil = getTableData($db, 'hasil', 'rangking');
                            while ($row = $dataHasil->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['kode']; ?></td>
                                    <td><?= $row['nama']; ?></td>
                                    <td><?= $row['rangking']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php include 'layout\footer.php'; ?>
</div>
</html>
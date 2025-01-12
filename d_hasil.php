<!DOCTYPE html>
<html lang="en">

</html>

<?php
require_once 'layout/head.php';
require_once 'kendali/koneksi.php';
require_once 'kendali/fungsi.php';

setActiveNavLink('hasil-link');
hitung($db);
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
                    Hasil Perankingan
                </div>
                <div class="card-body">
                    <table class="datatabel">
                        <thead>
                            <tr>
                                <th>Kode Alternatif</th>
                                <th>Nama Alternatif</th>
                                <th>Nilai</th>
                                <th>Ranking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            gabungPreferensiSawTopsis($db, 'saw_preferensi', 'topsis_preferensi', 'hasil');
                            $dataHasil = getTableData($db, 'hasil', 'ranking');
                            while ($row = $dataHasil->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['kode']); ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars(number_format($row['nilai'], 4)); ?></td>
                                    <td><?= htmlspecialchars($row['ranking']); ?></td>
                                </tr>
                            <?php endwhile; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php include 'layout/footer.php'; ?>
</div>

</html>
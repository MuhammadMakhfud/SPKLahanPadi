<!DOCTYPE html>
<html lang="en">

<?php
require_once 'layout/head.php';
require_once 'kendali/koneksi.php';
require_once 'kendali/fungsi.php';

setActiveNavLink('perhitungan-link');


// Panggil fungsi hitung langsung
hitung($db);
?>

<div id="layoutSidenav_content">
    <main>

        <div class="container-fluid px-4">
            <h1 class="mt-4">
                <i class="fa-solid fa-folder-plus"></i>
                Data Perhitungan
            </h1>
            <ol class="breadcrumb mb-4"></ol>
            <!-- <div class="row"> -->
            <!-- Tabel 1 -->
            <!-- <div class="col-md-6"> -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Matriks Keputusan (X)
                </div>
                <div class="card-body">
                    <table class="datatabel">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <?php
                                $columnsKeputusan = getTableColumns($db, 'keputusan');
                                $dataKeputusan = getTableData($db, 'keputusan', 'kode');
                                foreach ($columnsKeputusan as $col) : ?>
                                    <th><?= htmlspecialchars($col); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $dataKeputusan->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['kode']); ?></td>
                                    <?php foreach ($columnsKeputusan as $col) : ?>
                                        <td><?= htmlspecialchars($row[$col]); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endwhile; ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- </div> -->
            <!-- </div> -->
            <div class="row">
                <!-- Tabel 2  -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            SAW - Matriks Keputusan Ternormalisasi (R)
                        </div>
                        <div class="card-body">
                            <table class="datatabel">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <?php
                                        // Kolom untuk tabel 'keputusan_r'
                                        $columnsKeputusanR = getTableColumns($db, 'saw_keputusan_r');
                                        $dataKeputusanR = getTableData($db, 'saw_keputusan_r', 'kode');
                                        foreach ($columnsKeputusanR as $col) : ?>
                                            <th><?= htmlspecialchars($col); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $dataKeputusanR->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['kode']); ?></td>
                                            <?php foreach ($columnsKeputusanR as $col) : ?>
                                                <td><?= number_format(htmlspecialchars($row[$col]), 4); ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tabel 3  -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            SAW - Nilai Preferensi
                        </div>
                        <div class="card-body">
                            <table class="datatabel table ">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Kode</th>
                                        <th style="width: 50%;">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $dataPreferensi = getTableData($db, 'saw_preferensi', 'kode');
                                    while ($row = $dataPreferensi->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['kode']); ?></td>
                                            <td><?= number_format(htmlspecialchars($row['nilai']), 4); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Tabel 4  -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            TOPSIS - Matriks Keputusan Ternormalisasi (R)
                        </div>
                        <div class="card-body">
                            <table class="datatabel">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <?php
                                        // Kolom untuk tabel 'keputusan_y'
                                        $columnsKeputusanY = getTableColumns($db, 'topsis_keputusan_r');
                                        $dataKeputusanY = getTableData($db, 'topsis_keputusan_r', 'kode');
                                        foreach ($columnsKeputusanY as $col) : ?>
                                            <th><?= htmlspecialchars($col); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $dataKeputusanY->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['kode']); ?></td>
                                            <?php foreach ($columnsKeputusanY as $col) : ?>
                                                <td><?= number_format(htmlspecialchars($row[$col]), 4); ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Tabel 4  -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            TOPSIS - Matriks Keputusan Ternormalisasi Terbobot (Y)
                        </div>
                        <div class="card-body">
                            <table class="datatabel">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <?php
                                        // Kolom untuk tabel 'keputusan_y'
                                        $columnsKeputusanY = getTableColumns($db, 'topsis_keputusan_y');
                                        $dataKeputusanY = getTableData($db, 'topsis_keputusan_y', 'kode');
                                        foreach ($columnsKeputusanY as $col) : ?>
                                            <th><?= htmlspecialchars($col); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $dataKeputusanY->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['kode']); ?></td>
                                            <?php foreach ($columnsKeputusanY as $col) : ?>
                                                <td><?= number_format(htmlspecialchars($row[$col]), 4); ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Tabel 4 -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            TOPSIS - Matriks Solusi Ideal
                        </div>
                        <div class="card-body">
                            <table class="datatabel">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <?php
                                        // Mengambil data kode sebagai header kolom
                                        $dataMsolusi = getTableData($db, 'topsis_matrikssolusi', 'kode');
                                        $kodes = [];
                                        while ($row = $dataMsolusi->fetch_assoc()) {
                                            $kodes[] = $row['kode'];
                                        }
                                        foreach ($kodes as $kode): ?>
                                            <th><?= htmlspecialchars($kode); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Mengambil semua kolom
                                    $columnsMsolusi = getTableColumns($db, 'topsis_matrikssolusi');
                                    foreach ($columnsMsolusi as $col): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($col); ?></td>
                                            <?php
                                            // Menampilkan nilai untuk setiap kolom berdasarkan kode
                                            foreach ($kodes as $kode):
                                                $data = $db->query("SELECT $col FROM topsis_matrikssolusi WHERE kode = '$kode'")->fetch_assoc();
                                            ?>
                                                <td><?= number_format(htmlspecialchars($data[$col]), 4); ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tabel 7  -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            TOPSIS - Jarak Solusi Ideal
                        </div>
                        <div class="card-body">
                            <table class="datatabel">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <?php
                                        // Kolom untuk tabel 'keputusan_r'
                                        $columnsJsolusi = getTableColumns($db, 'topsis_jarakmatriks');
                                        $dataJsolusi = getTableData($db, 'topsis_jarakmatriks', 'kode');
                                        foreach ($columnsJsolusi as $col) : ?>
                                            <th><?= htmlspecialchars($col); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $dataJsolusi->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['kode']); ?></td>
                                            <?php foreach ($columnsJsolusi as $col) : ?>
                                                <td><?= number_format(htmlspecialchars($row[$col]), 4); ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Tabel 6  -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            TOPSIS - Nilai Preferensi
                        </div>
                        <div class="card-body">
                            <table class="datatabel table ">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Kode</th>
                                        <th style="width: 50%;">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $dataPreferensi = getTableData($db, 'topsis_preferensi', 'kode');
                                    while ($row = $dataPreferensi->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['kode']); ?></td>
                                            <td><?= number_format(htmlspecialchars($row['nilai']), 4); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </main>
    <?php include 'layout/footer.php'; ?>
</div>

</html>
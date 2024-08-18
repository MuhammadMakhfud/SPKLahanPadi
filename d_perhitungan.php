<!DOCTYPE html>
<html lang="en">

<?php
require_once 'layout\head.php';
require_once 'kendali/koneksi.php';
require_once 'kendali/fungsi.php';

setActiveNavLink('perhitungan-link');

?>

<div id="layoutSidenav_content">
    <main>
        <?php

        ?>
        <div class="container-fluid px-4">
            <h1 class="mt-4">
                <i class="fa-solid fa-folder-plus"></i>
                Data Perhitunganmm
            </h1>
            <ol class="breadcrumb mb-4"></ol>
            <div class="row">
                <!-- Tabel 1 -->
                <div class="col-md-6">
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
                </div>
                <!-- Tabel 2  -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Matriks Keputusan Ternormalisasi (R)
                        </div>
                        <div class="card-body">
                            <table class="datatabel">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <?php
                                        // Kolom untuk tabel 'keputusan_r'
                                        $columnsKeputusanR = getTableColumns($db, 'keputusan_r');
                                        $dataKeputusanR = getTableData($db,'keputusan_r', 'kode');
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
                                                <td><?= number_format(htmlspecialchars($row[$col]), 2); ?></td>
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
                            Matriks Keputusan Ternormalisasi Terbobot (Y)
                        </div>
                        <div class="card-body">
                            <table class="datatabel">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <?php
                                        // Kolom untuk tabel 'keputusan_y'
                                        $columnsKeputusanY = getTableColumns($db, 'keputusan_y');
                                        $dataKeputusanY = getTableData($db,'keputusan_y', 'kode');
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
                                                <td><?= number_format(htmlspecialchars($row[$col]), 2); ?></td>
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
                            Matriks Solusi Ideal
                        </div>
                        <div class="card-body">
                            <table class="datatabel">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <?php
                                        // Kolom untuk tabel 'keputusan_r'
                                        $columnsMsolusi = getTableColumns($db, 'm_solusi');
                                        $dataMsolusi = getTableData($db,'m_solusi', 'kode');
                                        foreach ($columnsMsolusi as $col) : ?>
                                            <th><?= htmlspecialchars($col); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $dataMsolusi->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['kode']); ?></td>
                                            <?php foreach ($columnsMsolusi as $col) : ?>
                                                <td><?= number_format(htmlspecialchars($row[$col]), 2); ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endwhile; ?>
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
                            Jarak Solusi Ideal
                        </div>
                        <div class="card-body">
                            <table class="datatabel">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <?php
                                        // Kolom untuk tabel 'keputusan_r'
                                        $columnsJsolusi = getTableColumns($db, 'j_solusi');
                                        $dataJsolusi = getTableData($db,'j_solusi', 'kode');
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
                                                <td><?= number_format(htmlspecialchars($row[$col]), 3); ?></td>
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
                            Nilai Preferensi
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
                                    $dataPreferensi = getTableData($db,'preferensi', 'kode');
                                    while ($row = $dataPreferensi->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['kode']); ?></td>
                                            <td><?= number_format(htmlspecialchars($row['nilai']), 3); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </main>
    <?php include 'layout\footer.php'; ?>
</div>

</html>
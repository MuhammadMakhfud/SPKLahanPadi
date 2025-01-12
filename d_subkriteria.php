<!DOCTYPE html>
<html lang="en">

<?php
require_once 'layout/head.php';
require_once 'kendali/koneksi.php';
require_once 'kendali/fungsi.php';

setActiveNavLink('subkriteria-link');

require_once 'kendali/proses_subkriteria.php';

?>

<body>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">
                    <i class="fa-solid fa-folder-open"></i>
                    Data Sub Kriteria
                </h1>
                <ol class="breadcrumb mb-4"></ol>
                <div class="row">
                    <?php
                    // Ambil semua tabel dengan nama 'sub_%'
                    $sub_kriteria_tables = [];
                    $query = "SHOW TABLES LIKE 'sub_%'";
                    $result = $db->query($query);

                    while ($row = $result->fetch_row()) {
                        $sub_kriteria_tables[] = $row[0];
                    }
                    foreach ($sub_kriteria_tables as $table_name):
                        // Extract the kriteria code from the table name
                        $kode = str_replace('sub_', '', $table_name);

                        // Ambil data kriteria berdasarkan kode
                        $query_kriteria = "SELECT kode, nama FROM kriteria WHERE kode = '$kode'";
                        $result_kriteria = $db->query($query_kriteria);
                        $kriteria = $result_kriteria->fetch_assoc();

                        $query = "SELECT tipe FROM kriteria WHERE kode = '$kode'";
                        $result = $db->query($query);
                        $row = $result->fetch_assoc();
                        $tipe = $row['tipe']; // Menyimpan tipe kriteria
                    ?>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div id="headtabel">
                                        <i class="fas fa-table me-1"></i>
                                        <?= $kriteria['kode'] . ' - ' . $kriteria['nama']; ?>
                                    </div>
                                    <a class="fa-solid fa-square-plus" style="color: #2E5077;" type="button" title="Tambah" data-bs-toggle="modal" data-bs-target="#tambahsub<?= $kode; ?>"></a>

                                </div>
                                <!-- Bagian Tabel  -->
                                <div class="card-body">
                                    <table class="datatabel">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th><?= $kriteria['nama']; ?> </th>
                                                <th>Nilai</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM `$table_name`";
                                            $result = $db->query($query);
                                            $no = 1;
                                            while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <?php if ($tipe == 'nilai_rentang'): ?>
                                                        <td>
                                                            <?php
                                                            $min = htmlspecialchars($row['min']);
                                                            $max = htmlspecialchars($row['max']);
                                                            $satuan = htmlspecialchars($row['satuan']);

                                                            // Cek apakah `min` dan `max` adalah angka
                                                            $min = is_numeric($min) ? formatAngka($min) : $min;
                                                            $max = is_numeric($max) ? formatAngka($max) : $max;

                                                            // Logika untuk format tampilan
                                                            if ($min === '' || $min === 'NULL') {
                                                                echo "≤ $max $satuan";
                                                            } elseif ($max === '' || $max === 'NULL') {
                                                                echo "≥ $min $satuan";
                                                            } else {
                                                                echo "$min - $max $satuan";
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php else: ?>
                                                        <td><?= htmlspecialchars($row['nama']); ?></td>
                                                    <?php endif; ?>
                                                    <td><?= htmlspecialchars($row['nilai']); ?></td>
                                                    <td>
                                                        <a href="#" title="Edit" data-bs-toggle="modal" data-bs-target="#editdata<?= $kode . '_' . $row['id']; ?>">
                                                            <i class="fa-solid fa-pen-to-square" alt="Edit" style="width: 18px; height: 18px;"></i>
                                                        </a>
                                                        <a href="#" title="Delete" data-bs-toggle="modal" data-bs-target="#hapusnotif<?= $kode . '_' . $row['id']; ?>">
                                                            <i class="fa-solid fa-trash" alt="Delete" style="width: 18px; height: 18px; color: #ff0000;"></i>
                                                        </a>

                                                        <!-- Modal Edit Data -->
                                                        <div class="modal fade" id="editdata<?= $kode . '_' . $row['id']; ?>" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5" id="editDataModalLabel">Edit Data Sub <strong><?= strtoupper($kode); ?></strong></h1>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="post" action="d_subkriteria.php">
                                                                            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
                                                                            <input type="hidden" name="kode" value="<?= htmlspecialchars($kode); ?>">

                                                                            <?php if ($tipe == 'nilai_rentang'): ?>
                                                                                <div class="row mb-3">
                                                                                    <label class="form-label">
                                                                                        <?= $kriteria['nama']; ?>
                                                                                        <small style="font-size: 0.6rem; color: #6c757d;">
                                                                                            Rentang Nilai (Kosongkan <strong>Min</strong> untuk ≤Nilai, atau kosongkan <strong>Max</strong> untuk ≥Nilai)
                                                                                        </small> </label>
                                                                                    <div class="d-flex align-items-center">
                                                                                        <div class="col-md-5">
                                                                                            <label for="min" class="form-label">Min:</label>
                                                                                            <input type="number" class="form-control" id="min" name="min" step="any" value="<?= htmlspecialchars($row['min']); ?>">
                                                                                        </div>
                                                                                        <div class="col-md-1 text-center ">
                                                                                            <span>_</span>
                                                                                        </div>
                                                                                        <div class="col-md-5">
                                                                                            <label for="max" class="form-label">Max:</label>
                                                                                            <input type="number" class="form-control" id="max" name="max" step="any" value="<?= htmlspecialchars($row['max']); ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label for="satuan" class="col-form-label">Satuan:</label>
                                                                                    <input type="text" class="form-control" id="satuan" name="satuan" value="<?= htmlspecialchars($row['satuan']); ?>">
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="nilai" class="col-form-label">Nilai:</label>
                                                                                    <input type="number" class="form-control" id="nilai" name="nilai" required min="0" step="any" value="<?= htmlspecialchars($row['nilai']); ?>">
                                                                                </div>

                                                                            <?php else: ?>
                                                                                <div class="mb-3">
                                                                                    <label for="nama" class="col-form-label"><?= $kriteria['nama']; ?>:</label>
                                                                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" required>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label for="nilai" class="col-form-label">Nilai:</label>
                                                                                    <input type="number" class="form-control" id="nilai" name="nilai" required min="0" step="any" value="<?= htmlspecialchars($row['nilai']); ?>">
                                                                                </div>
                                                                            <?php endif; ?>

                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                                <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <!-- Modal Hapus Data -->
                                                        <div class="modal fade" id="hapusnotif<?= $kode . '_' . $row['id']; ?>" tabindex="-1" aria-labelledby="hapusNotifModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="hapusNotifModalLabel">Hapus Data</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="modal-body">
                                                                            Apakah Anda yakin ingin menghapus data
                                                                            <strong><?= htmlspecialchars($row['nama']); ?></strong>
                                                                            pada tabel
                                                                            <strong><?= strtoupper($kode); ?></strong>
                                                                            ?
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form method="post" action="d_subkriteria.php">
                                                                            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
                                                                            <input type="hidden" name="kode" value="<?= htmlspecialchars($kode); ?>">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                            <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Modal Tambah Data -->
                                <div class="modal fade" id="tambahsub<?= $kode; ?>" tabindex="-1" aria-labelledby="tambahSubModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="tambahSubModalLabel">Tambah Data Sub <strong><?= strtoupper($kode); ?></strong></h1>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="d_subkriteria.php">
                                                    <input type="hidden" name="kode" value="<?= htmlspecialchars($kode); ?>">
                                                    <?php if ($tipe == 'nilai_rentang'): ?>
                                                        <div class="row mb-3">
                                                            <label class="form-label">
                                                                <?= $kriteria['nama']; ?>
                                                                <small style="font-size: 0.6rem; color: #6c757d;">
                                                                    Rentang Nilai (Kosongkan <strong>Min</strong> untuk ≤Nilai, atau kosongkan <strong>Max</strong> untuk ≥Nilai)
                                                                </small> </label>
                                                            <div class="d-flex align-items-center">
                                                                <div class="col-md-5">
                                                                    <label for="min" class="form-label">Min:</label>
                                                                    <input type="number" class="form-control" id="min" name="min" step="any">
                                                                </div>
                                                                <div class="col-md-1 text-center ">
                                                                    <span>_</span>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label for="max" class="form-label">Max:</label>
                                                                    <input type="number" class="form-control" id="max" name="max" step="any">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="satuan" class="col-form-label">Satuan:</label>
                                                            <input type="text" class="form-control" id="satuan" name="satuan">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nilai" class="col-form-label">Nilai:</label>
                                                            <input type="number" class="form-control" id="nilai" name="nilai" required min="0" step="any">
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="mb-3">
                                                            <label for="nama" class="col-form-label"><?= $kriteria['nama']; ?>:</label>
                                                            <input type="text" class="form-control" id="nama" name="nama" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nilai" class="col-form-label">Nilai:</label>
                                                            <input type="number" class="form-control" id="nilai" name="nilai" required min="0" step="any">
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="tambah" class="btn btn-primary">Tambah Data</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
        <?php include 'layout/footer.php'; ?>
    </div>
</body>

</html>
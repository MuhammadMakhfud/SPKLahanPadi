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
                    $sub_kriteria_tables = getSubKriteriaTables($db);
                    foreach ($sub_kriteria_tables as $table_name):
                        // Extract the kriteria code from the table name
                        $kode = str_replace('sub_', '', $table_name);
                        $kriteria = getKriteriaData($db, $kode); ?>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div id="headtabel">
                                        <i class="fas fa-table me-1"></i>
                                        <?= $kriteria['kode'] . ' - ' . $kriteria['nama']; ?>
                                    </div>
                                    <a class="fa-solid fa-square-plus" type="button" title="Tambah" data-bs-toggle="modal" data-bs-target="#tambahsub<?= $kode; ?>"></a>
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
                                                        <div class="mb-3">
                                                            <label for="nama" class="col-form-label">Nama:</label>
                                                            <input type="text" class="form-control" id="nama" name="nama" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nilai" class="col-form-label">Nilai:</label>
                                                            <input type="number" class="form-control" id="nilai" name="nilai" required min="0" step="any">
                                                        </div>
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
                                                    <td><?= htmlspecialchars($row['nama']); ?></td>
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
                                                                            <div class="mb-3">
                                                                                <label for="nama" class="col-form-label">Nama:</label>
                                                                                <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" required>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="nilai" class="col-form-label">Nilai:</label>
                                                                                <input type="number" class="form-control" id="nilai" name="nilai" value="<?= htmlspecialchars($row['nilai']); ?>" required min="0" step="any">
                                                                            </div>
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
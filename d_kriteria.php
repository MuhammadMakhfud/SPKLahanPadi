<!DOCTYPE html>
<html lang="en">

<?php
require_once 'layout/head.php';
require_once 'kendali/koneksi.php';
require_once 'kendali/fungsi.php';

setActiveNavLink('kriteria-link');

require_once 'kendali/proses_kriteria.php';
?>

<body>
    <div id="layoutSidenav_content">
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
                        <a class="fa-solid fa-square-plus" style="color: #2E5077;" type="button" title="Tambah" data-bs-toggle="modal" data-bs-target="#tambahdata"></a>
                    </div>
                    <!-- Tabel -->
                    <div class="card-body">
                        <!-- Menampilkan alert jika total bobot tidak sama dengan 100 -->
                        <?php
                        $alert_message = generateAlerts($db);
                        if ($alert_message): ?>
                            <div class="alert alert-danger top-0 end-0 p-2 custom-alert " role="alert">
                                <?= $alert_message; ?>
                            </div>
                        <?php endif;
                        if ($alert_modal): ?>
                            <div id="alert-modal" class="alert alert-danger p-0" role="alert">
                                <?= $alert_modal; ?>
                            </div>
                        <?php endif; ?>
                        <table class="datatabel ">
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
                                $dataKriteria = getTableData($db, 'kriteria', 'kode');
                                while ($row = $dataKriteria->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['kode']; ?></td>
                                        <td><?= $row['nama']; ?></td>
                                        <td><?= ucfirst($row['atribut']); ?></td>
                                        <td><?= $row['bobot'] * 100; ?>%</td>
                                        <td>
                                            <a class="btn-icon-primary" type="button" title="Edit" data-bs-toggle="modal" data-bs-target="#editdata<?= $row['id']; ?>">
                                                <i class="fa-solid fa-pen-to-square" alt="Edit" style="width: 18px; height: 18px;"></i>
                                            </a>
                                            <a class="btn-icon" type="button" title="Delete" data-bs-toggle="modal" data-bs-target="#hapusnotif<?= $row['id']; ?>">
                                                <i class="fa-solid fa-trash" alt="Delete" style="width: 18px; height: 18px; color: red"></i>
                                            </a>

                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="editdata<?= $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Kriteria</h1>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post">
                                                                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
                                                                <div class="mb-3">
                                                                    <label for="kode" class="col-form-label">Kode:</label>
                                                                    <input type="text" class="form-control" id="kode" name="kode" value="<?= htmlspecialchars($row['kode']); ?>" readonly>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="nama" class="col-form-label">Nama:</label>
                                                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="tipe" class="col-form-label">Tipe Data Kategori:</label>
                                                                    <select class="form-select" id="tipe" name="tipe" required>
                                                                        <option value="" disabled selected>Pilih Tipe Data</option>
                                                                        <option value="jenis" <?= $row['tipe'] == 'jenis' ? 'selected' : ''; ?>>Jenis</option>
                                                                        <option value="nilai_rentang" <?= $row['tipe'] == 'nilai_rentang' ? 'selected' : ''; ?>>Nilai Rentang</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="atribut" class="col-form-label">Atribut:</label>
                                                                    <select class="form-select" id="atribut" name="atribut" required>
                                                                        <option value="" disabled selected>Pilih Atribut</option>
                                                                        <option value="benefit" <?= $row['atribut'] == 'benefit' ? 'selected' : ''; ?>>Benefit</option>
                                                                        <option value="cost" <?= $row['atribut'] == 'cost' ? 'selected' : ''; ?>>Cost</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="bobot" class="col-form-label">Bobot:</label>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" id="bobot" name="bobot" value="<?= htmlspecialchars($row['bobot'] * 100); ?>" required min="1" max="100">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
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

                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="hapusnotif<?= $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus data <strong><?= htmlspecialchars($row['nama']); ?></strong> dengan kode <strong><?= htmlspecialchars($row['kode']); ?></strong>?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form method="post">
                                                                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
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

                    <!-- Modal Tambah -->
                    <div class="modal fade" id="tambahdata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Kriteria</h1>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="d_kriteria.php">
                                        <div class="mb-3">
                                            <label for="kode" class="col-form-label">Kode:</label>
                                            <input type="text" class="form-control" id="kode" value="<?= $kodebaru = getNextKode($db, 'kriteria', 'C'); ?>" name="kode" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama" class="col-form-label">Nama:</label>
                                            <input type="text" class="form-control" id="nama" name="nama" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="tipe" class="col-form-label">Tipe Data Kategori:</label>
                                            <select class="form-select" id="tipe" name="tipe" required>
                                                <option value="" disabled selected>Pilih Tipe Data</option>
                                                <option value="jenis">Jenis</option>
                                                <option value="nilai_rentang">Nilai Rentang</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="atribut" class="col-form-label">Atribut:</label>
                                            <select class="form-select" id="atribut" name="atribut" required>
                                                <option value="" disabled selected>Pilih Atribut</option>
                                                <option value="benefit">Benefit</option>
                                                <option value="cost">Cost</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="bobot" class="col-form-label">Bobot:</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="bobot" name="bobot" required min="1" max="100">
                                                <span class="input-group-text">%</span>
                                            </div>
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
            </div>
        </main>
        <script>

        </script>
        <!-- Footer -->
        <?php include 'layout/footer.php'; ?>
    </div>
</body>

</html>
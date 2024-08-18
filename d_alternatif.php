<!DOCTYPE html>
<html lang="en">

<?php
require_once 'layout/head.php';
require_once 'kendali/koneksi.php';
require_once 'kendali/fungsi.php';

setActiveNavLink('alternatif-link');

require_once 'kendali/proses_alternatif.php';

// Ambil semua kolom dari tabel 'alternatif'
$query = "SHOW COLUMNS FROM alternatif";
$result = $db->query($query);

// Array untuk menyimpan nama kolom
$columns = [];
while ($row = $result->fetch_assoc()) {
    if (!in_array($row['Field'], ['kode', 'nama', 'id'])) {
        $columns[] = $row['Field'];
    }
}
sort($columns);



?>

<script>

</script>

<div id="layoutSidenav_content">
    <!-- Awal Konten -->
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">
                <i class="fa-solid fa-pager"></i>
                Data Alternatif
            </h1>
            <ol class="breadcrumb mb-4"></ol>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-table me-1"></i>

                    </div>

                    <a class="fa-solid fa-square-plus" type="button" title="Tambah" data-bs-toggle="modal" data-bs-target="#tambahDataModal"></a>

                    <!-- Modal Tambah Data -->
                    <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="tambahDataLabel">Tambah Data Alternatif</h1>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <div class="mb-3">
                                            <label for="kode" class="col-form-label">Kode Alternatif:</label>
                                            <input type="text" class="form-control" id="kode" name="kode" value="<?= $kodebaru = getNextKode($db, 'alternatif', 'A');  ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama" class="col-form-label">Nama Alternatif:</label>
                                            <input type="text" class="form-control" id="nama" name="nama" required>
                                        </div>

                                        <!-- Urutkan kolom secara abjad dan buat dropdown berdasarkan subkriteria -->
                                        <?php

                                        foreach ($columns as $col) :
                                            $options = getSubKriteriaOptions($db, $col); // Ambil options sesuai subkriteria
                                        ?>
                                            <div class="mb-3">
                                                <label for="<?= htmlspecialchars($col) ?>" class="col-form-label"><?= htmlspecialchars($col) ?>:</label>
                                                <select class="form-control" id="<?= htmlspecialchars($col) ?>" name="<?= htmlspecialchars($col) ?>" required>
                                                    <option value="">Pilih</option>
                                                    <?php foreach ($options as $value => $name) : ?>
                                                        <option value="<?= htmlspecialchars($value . '|' . $name) ?>">
                                                            <?= htmlspecialchars($name) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                        <?php endforeach; ?>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    <table class="datatabel" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <!-- Tambahkan kolom dinamis sesuai tabel 'alternatif' -->
                                <?php foreach ($columns as $col) : ?>
                                    <th><?php echo htmlspecialchars($col); ?></th>
                                <?php endforeach; ?>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Ambil data dari tabel alternatif
                            $query = "SELECT * FROM alternatif ORDER BY kode ASC";
                            $result = $db->query($query);

                            while ($row = $result->fetch_assoc()) :
                                $id = $row['id'];
                                $kode = htmlspecialchars($row['kode']);
                                $nama = htmlspecialchars($row['nama']);
                            ?>
                                <tr>
                                    <td><?= $kode ?></td>
                                    <td><?= $nama ?></td>
                                    <?php foreach ($columns as $col) : ?>
                                        <td><?= htmlspecialchars($row[$col]) ?></td>
                                    <?php endforeach; ?>
                                    <td>
                                        <a class="btn-icon-primary" type="button" title="Edit" data-bs-toggle="modal" data-bs-target="#editdata<?= $id ?>">
                                            <i class="fa-solid fa-pen-to-square" alt="Edit" style="width: 18px; height: 18px;"></i>
                                        </a>
                                        <a class="btn-icon" type="button" title="Delete" data-bs-toggle="modal" data-bs-target="#hapusnotif<?= $id ?>">
                                            <i class="fa-solid fa-trash" alt="Delete" style="width: 18px; height: 18px; color: red;"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editdata<?= $id ?>" tabindex="-1" aria-labelledby="editDataLabel<?= $id ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editDataLabel<?= $id ?>">Edit Data Alternatif</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="d_alternatif.php">
                                                    <input type="hidden" name="id" value="<?= $id ?>">

                                                    <div class="mb-3">
                                                        <label for="kode" class="col-form-label">Kode Alternatif:</label>
                                                        <input type="text" class="form-control" id="kode" name="kode" value="<?= htmlspecialchars($kode) ?>" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nama" class="col-form-label">Nama Alternatif:</label>
                                                        <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>" required>
                                                    </div>

                                                    <!-- Urutkan kolom secara abjad dan buat dropdown berdasarkan subkriteria -->
                                                    <?php
                                                    // $terpilih =  ;
                                                    sort($columns);
                                                    foreach ($columns as $col) :
                                                        $options = getSubKriteriaOptions($db, $col); // ambil options sesuai subkriteria
                                                    ?>
                                                        <div class="mb-3">
                                                            <label for="<?= htmlspecialchars($col) ?>" class="col-form-label"><?= htmlspecialchars($col) ?>:</label>
                                                            <select class="form-control" id="<?= htmlspecialchars($col) ?>" name="<?= htmlspecialchars($col) ?>" required>
                                                                <option value="">Pilih</option>
                                                                <?php foreach ($options as $value => $name) : ?>
                                                                    <option value="<?= htmlspecialchars($value . '|' . $name) ?>" <?= $name == $row[$col] ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($name) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    <?php endforeach; ?>

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
                                <div class="modal fade" id="hapusnotif<?= $id ?>" tabindex="-1" aria-labelledby="hapusNotifLabel<?= $id ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="hapusNotifLabel<?= $id ?>">Hapus Data</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus data <strong><?= htmlspecialchars($nama) ?></strong> dengan kode <strong><?= htmlspecialchars($kode) ?></strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="d_alternatif.php" method="post">
                                                    <input type="hidden" name="id" value="<?= $id ?>">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
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
                                                Anda yakin ingin menghapus data ini?
                                            </div>
                                            <div class="modal-footer">
                                                <form action="d_alternatif.php" method="post">
                                                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                                    <button type="submit" name="hapus" class="btn btn-primary">Ya Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile; ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <!-- Akhir Konten -->

    <?php
    require_once 'layout/footer.php'; ?>
</div>



</html>
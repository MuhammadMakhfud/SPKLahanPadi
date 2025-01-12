<!DOCTYPE html>
<html lang="en">

<?php
require_once 'layout/head.php';
require_once 'kendali/koneksi.php';
require_once 'kendali/fungsi.php';

setActiveNavLink('alternatif-link');

require_once 'kendali/proses_alternatif.php';
?>

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
                        Alternatif
                    </div>
                    <a class="fa-solid fa-square-plus" style="color: #2E5077;" type="button" title="Tambah" data-bs-toggle="modal" data-bs-target="#tambahDataModal"></a>
                </div>
                <!-- Tabel -->
                <div class="card-body">
                    <table class="datatabel" class="table table-bordered table-hover">
                        <?php
                        //ambil kolom dinamis
                        $query = "SHOW COLUMNS FROM alternatif";
                        $result = $db->query($query);

                        $columns = [];
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                if (!in_array($row['Field'], ['kode', 'nama', 'id'])) {
                                    $columns[] = $row['Field'];
                                }
                            }
                            sort($columns); // Urutkan kolom secara alfabetis
                        } else {
                            die("Gagal mengambil kolom dari tabel: " . $db->error);
                        }

                        //untuk nama & kode sub
                        // Pisahkan kolom statis dan dinamis
                        $staticColumns = ['id', 'kode', 'nama'];
                        $dynamicColumns = array_diff($columns, $staticColumns);
                        // Ambil nama untuk setiap kolom dinamis
                        $kriteriaNames = [];
                        foreach ($dynamicColumns as $col) {
                            $query_kriteria = "SELECT kode, nama FROM kriteria WHERE kode = '$col'";
                            $result_kriteria = $db->query($query_kriteria);
                            $kriteria = $result_kriteria->fetch_assoc();
                            $kriteriaNames[$col] = $kriteria['kode'] . ' - ' . $kriteria['nama'];
                        }
                        ?>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <!-- Tambahkan kolom dinamis sesuai tabel 'alternatif' -->
                                <?php foreach ($columns as $col) : ?>
                                    <th><?php echo htmlspecialchars($kriteriaNames[$col]); ?></th>
                                <?php endforeach; ?>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Ambil data dari tabel alternatif
                            $query = "SELECT * FROM alternatif ORDER BY kode ASC";
                            $result = $db->query($query);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id = $row['id'];
                                    $kode = htmlspecialchars($row['kode']);
                                    $nama = htmlspecialchars($row['nama']);
                            ?>
                                    <tr>
                                        <td><?= $kode ?></td>
                                        <td><?= $nama ?></td>
                                        <?php foreach ($columns as $col): ?>
                                            <?php
                                            // Cek tipe dari kriteria
                                            $queryTipe = "SELECT tipe FROM kriteria WHERE kode = '$col'";
                                            $resultTipe = $db->query($queryTipe);
                                            $tipeRow = $resultTipe->fetch_assoc();
                                            $tipe = $tipeRow['tipe'];

                                            if ($tipe === 'nilai_rentang') {
                                                // Ambil satuan dari tabel sub_$col
                                                $subTable = 'sub_' . strtolower($col);
                                                $querySatuan = "SELECT satuan FROM $subTable LIMIT 1"; // Row pertama
                                                $resultSatuan = $db->query($querySatuan);
                                                $satuanRow = $resultSatuan->fetch_assoc();
                                                $satuan = $satuanRow['satuan'] ?? ''; // Gunakan default kosong jika tidak ada
                                            } else {
                                                $satuan = ''; // Tidak ada satuan untuk tipe selain nilai_rentang
                                            }
                                            ?>
                                            <td>
                                                <?= htmlspecialchars($row[$col] ?? '-') ?>
                                                <?php if (!empty($satuan)): ?>
                                                    <?= " " . htmlspecialchars($satuan); ?>
                                                <?php endif; ?>
                                            </td>
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
                                                    <h1 class="modal-title fs-5" id="editDataLabel<?= $id ?>">Edit Data Alternatif</h1>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post">
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
                                                        foreach ($columns as $col) :
                                                            $tipe_kriteria = getKriteriaTipe($db, $col); // Ambil tipe kriteria
                                                            if ($tipe_kriteria === 'jenis') {
                                                                $options = getSubKriteriaOptions($db, $col);
                                                        ?>
                                                                <div class="mb-3">
                                                                    <label for="<?= htmlspecialchars($col) ?>" class="col-form-label"><?= htmlspecialchars($kriteriaNames[$col]); ?>:</label>
                                                                    <select class="form-control" id="<?= htmlspecialchars($col) ?>" name="<?= htmlspecialchars($col) ?>" required>
                                                                        <option value="">Pilih</option>
                                                                        <?php
                                                                        foreach ($options as $value => $name) :
                                                                            // Ambil nilai yang tersimpan untuk kolom ini
                                                                            $selected = ($name == $row[$col]) ? 'selected' : ''; // Jika nama yang dipilih sama dengan nilai yang ada di database, set selected
                                                                        ?>
                                                                            <option value="<?= htmlspecialchars($value . '|' . $name) ?>" <?= $selected ?>>
                                                                                <?= htmlspecialchars($name) ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            <?php
                                                            } elseif ($tipe_kriteria === 'nilai_rentang') {
                                                            ?>
                                                                <div class="mb-3">
                                                                    <label for="<?= htmlspecialchars($col) ?>" class="col-form-label"><?= htmlspecialchars($kriteriaNames[$col]); ?>:</label>
                                                                    <input type="number" class="form-control" id="<?= htmlspecialchars($col) ?>" name="<?= htmlspecialchars($col) ?>" value="<?= htmlspecialchars($row[$col]) ?>" step="any" required>
                                                                </div>
                                                        <?php
                                                            }
                                                        endforeach;
                                                        ?>

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
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

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
                                    <?php
                                    foreach ($columns as $col) :
                                        $tipe_kriteria = getKriteriaTipe($db, $col); // Ambil tipe kriteria
                                        if ($tipe_kriteria === 'jenis') {
                                            $options = getSubKriteriaOptions($db, $col); // Dropdown untuk jenis
                                    ?>
                                            <div class="mb-3">
                                                <label for="<?= htmlspecialchars($col) ?>" class="col-form-label"><?= htmlspecialchars($kriteriaNames[$col]);  ?>:</label>
                                                <select class="form-control" id="<?= htmlspecialchars($col) ?>" name="<?= htmlspecialchars($col) ?>" required>
                                                    <option value="">Pilih</option>
                                                    <?php foreach ($options as $value => $name) : ?>
                                                        <option value="<?= htmlspecialchars($value . '|' . $name) ?>">
                                                            <?= htmlspecialchars($name) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        <?php
                                        } elseif ($tipe_kriteria === 'nilai_rentang') {
                                        ?>
                                            <div class="mb-3">
                                                <label for="<?= htmlspecialchars($col) ?>" class="col-form-label"><?= htmlspecialchars($kriteriaNames[$col]); ?>:</label>
                                                <input type="number" class="form-control" id="<?= htmlspecialchars($col) ?>" name="<?= htmlspecialchars($col) ?>" step="any" required>
                                            </div>
                                    <?php
                                        }
                                    endforeach;
                                    ?>
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
        </div>
    </main>
    <!-- Akhir Konten -->
    <?php
    require_once 'layout/footer.php'; ?>
</div>



</html>
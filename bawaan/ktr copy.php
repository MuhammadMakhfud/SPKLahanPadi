<!-- Modal dan fungsi Tambah -->
<?php

// Tambah data
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $atribut = $_POST['atribut'];
    $bobot = $_POST['bobot'] / 100; // Konversi bobot ke format desimal
    // Query untuk menambah data
    $query = "INSERT INTO kriteria (kode, nama, atribut, bobot) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "sssd", $kode, $nama, $atribut, $bobot);
    mysqli_stmt_execute($stmt);
    // Redirect setelah tambah
    header("Location: d_kriteria.php");
    exit;
}

// Edit data
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $atribut = $_POST['atribut'];
    $bobot = $_POST['bobot'] / 100;

    $query = "UPDATE kriteria SET kode = ?, nama = ?, atribut = ?, bobot = ? WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $kode, $nama, $atribut, $bobot, $id);
    mysqli_stmt_execute($stmt);

    header("Location: d_kriteria.php", true, 303);
    exit();
}

// Hapus data
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    // Query untuk menghapus data
    $query = "DELETE FROM kriteria WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    // Redirect setelah hapus
    header("Location: d_kriteria.php"); // Ganti dengan path yang sesuai
    exit;
}
?>

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
                        <input type="text" class="form-control" id="kode" name="kode" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="col-form-label">Nama:</label>
                        <select class="form-control" id="nama" name="nama" onchange="updateKode()" required>
                            <option value="" disabled selected>Pilih Kriteria</option>
                            <?php
                            $options = [
                                'Jenis Tanah',
                                'pH Tanah',
                                'Curah Hujan',
                                'Suhu',
                                'Irigasi dan Perairan'
                            ];
                            foreach ($options as $option) {
                                if (!in_array($option, $existing_names)) {
                                    echo "<option value=\"$option\">$option</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="atribut" class="col-form-label">Atribut:</label>
                        <select class="form-control" id="atribut" name="atribut" required>
                            <option value="" disabled selected>Pilih Atribut</option>
                            <option value="benefit">Benefit</option>
                            <option value="cost">Cost</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bobot" class="col-form-label">Bobot (%):</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="bobot" name="bobot" min="1" max="100" required>
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
<!-- Modal Edit -->
<div class="modal fade" id="editdata<?= $row['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Kriteria</h1>
            </div>
            <div class="modal-body">
                <!-- Form for editing data -->
                <form method="post"> <!-- Pastikan action mengarah ke edit.php -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
                    <div class="mb-3">
                        <label for="kode" class="col-form-label">Kode:</label>
                        <input type="text" class="form-control" id="kode" name="kode" value="<?= htmlspecialchars($row['kode']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="col-form-label">Nama:</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" readonly>
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

<!-- modal hapus -->
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
                <form method="post"> <!-- Ganti dengan path yang sesuai untuk penghapusan -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" name="hapus" class="btn btn-primary">Ya Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
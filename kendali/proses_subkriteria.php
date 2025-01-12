<?php

$alert_sub = '';


if (isset($_POST['tambah'])) {
    // Handle tambah data
    $kode = $_POST['kode'];

    // Ambil tipe kriteria dari database berdasarkan kode
    $query = "SELECT tipe FROM kriteria WHERE kode = '$kode'";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
    $tipe = $row['tipe']; // Tipe kriteria

    $kode = $db->real_escape_string($kode); // Sanitasi input

    if ($tipe == 'nilai_rentang') {
        // Untuk tipe 'nilai_rentang', gunakan min, max, dan satuan
        $min = isset($_POST['min']) && $_POST['min'] !== '' ? $_POST['min'] : 'NULL';
        $max = isset($_POST['max']) && $_POST['max'] !== '' ? $_POST['max'] : 'NULL';
        $satuan = $_POST['satuan'];
        $nilai = floatval($_POST['nilai']); // Pastikan nilai adalah float

        // Validasi nilai rentang -  Nilai Min harus lebih kecil dari Max
        if ($min !== 'NULL' && $max !== 'NULL' && $min >= $max) {
            $alert_sub = '<div class="alert alert-danger" role="alert">Nilai Min harus lebih kecil dari Max</div>';
            exit();
        }

        // Validasi: Min dan Max tidak boleh sama dengan data yang sudah ada
        $checkQuery = "SELECT * FROM `sub_$kode` WHERE (min = $min AND max = $max)";
        $checkResult = $db->query($checkQuery);

        if ($checkResult && $checkResult->num_rows > 0) {
            echo '<div class="alert alert-danger" role="alert">Nilai Min dan Max sudah ada di database</div>';
            exit();
        }

        $query = "INSERT INTO `sub_$kode` (min, max, satuan, nilai) VALUES ($min, $max, '$satuan', $nilai)";
    } else {
        // Untuk tipe 'jenis', hanya ada nama dan nilai
        $nama = $_POST['nama'];
        $nama = $db->real_escape_string($nama); // Sanitasi input
        $nilai = floatval($_POST['nilai']); // Pastikan nilai adalah float

        // Validasi: Nama tidak boleh duplikat
        $checkQuery = "SELECT * FROM `sub_$kode` WHERE nama = '$nama'";
        $checkResult = $db->query($checkQuery);

        if ($checkResult && $checkResult->num_rows > 0) {
            echo '<div class="alert alert-danger" role="alert">Nama subkriteria sudah ada di database</div>';
            exit();
        }

        $query = "INSERT INTO `sub_$kode` (nama, nilai) VALUES ('$nama', $nilai)";
    }

    // Eksekusi query untuk menambah data
    if ($db->query($query)) {
        header("Location: d_subkriteria.php?kode=$kode");
        exit();
    } else {
        echo "Error: " . $db->error;
    }
}



if (isset($_POST['edit'])) {
    // Handle edit data
    $id = $_POST['id'];
    $kode = $_POST['kode'];

    // Ambil tipe kriteria dari database berdasarkan kode
    $query = "SELECT tipe FROM kriteria WHERE kode = '$kode'";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
    $tipe = $row['tipe']; // Tipe kriteria

    // Sanitasi input
    $id = intval($id); // Sanitasi input
    $kode = $db->real_escape_string($kode); // Sanitasi input

    if ($tipe == 'nilai_rentang') {
        // Untuk tipe 'nilai_rentang', gunakan min, max, satuan
        $min = floatval($_POST['min']);
        $max = floatval($_POST['max']);
        $satuan = $_POST['satuan'];
        $nilai = floatval($_POST['nilai']); // Pastikan nilai adalah float

        // Query untuk update data
        $query = "UPDATE `sub_$kode` SET min = $min, max = $max, satuan = '$satuan', nilai = $nilai WHERE id = $id";
    } else {
        // Untuk tipe 'jenis', hanya ada nama dan nilai
        $nama = $_POST['nama'];
        $nama = $db->real_escape_string($nama); // Sanitasi input
        $nilai = floatval($_POST['nilai']); // Pastikan nilai adalah float

        // Query untuk update data
        $query = "UPDATE `sub_$kode` SET nama = '$nama', nilai = $nilai WHERE id = $id";
    }

    // Eksekusi query untuk update data
    if ($db->query($query)) {
        header("Location: d_subkriteria.php?kode=$kode");
        exit();
    } else {
        echo "Error: " . $db->error;
    }
}


if (isset($_POST['hapus'])) {
    // Handle hapus data
    $id = $_POST['id'];
    $kode = $_POST['kode'];

    $id = intval($id); // Sanitasi input
    $kode = $db->real_escape_string($kode); // Sanitasi input

    $query = "DELETE FROM `sub_$kode` WHERE id = $id";
    if ($db->query($query)) {
        header("Location: d_subkriteria.php");
        exit();
    } else {
        echo "Error: " . $db->error;
    }
}

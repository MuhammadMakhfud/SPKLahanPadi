<?php

if (isset($_POST['tambah'])) {
    // Handle tambah data
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $nilai = $_POST['nilai'];

    $kode = $db->real_escape_string($kode); // Sanitasi input
    $nama = $db->real_escape_string($nama); // Sanitasi input
    $nilai = floatval($nilai); // Pastikan nilai adalah float

    $query = "INSERT INTO `sub_$kode` (nama, nilai) VALUES ('$nama', $nilai)";
    if ($db->query($query)) {
        header("Location: d_subkriteria.php");
        exit();
    } else {
        echo "Error: " . $db->error;
    }
}

if (isset($_POST['edit'])) {
    // Handle edit data
    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $nilai = $_POST['nilai'];

    $id = intval($id); // Sanitasi input
    $kode = $db->real_escape_string($kode); // Sanitasi input
    $nama = $db->real_escape_string($nama); // Sanitasi input
    $nilai = floatval($nilai); // Pastikan nilai adalah float

    $query = "UPDATE `sub_$kode` SET nama = '$nama', nilai = $nilai WHERE id = $id";
    if ($db->query($query)) {
        header("Location: d_subkriteria.php");
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
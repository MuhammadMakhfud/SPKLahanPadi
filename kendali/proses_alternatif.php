<?php

// Logika untuk proses penambahan data
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];

    // Ambil semua kolom dari tabel 'alternatif'
    $query = "SHOW COLUMNS FROM alternatif";
    $result = $db->query($query);

    // Bangun query untuk menyisipkan data baru ke tabel alternatif
    $alternatif_columns = ['kode', 'nama'];
    $alternatif_values = ["'$kode'", "'$nama'"];

    // Bangun query untuk menyisipkan data baru ke tabel keputusan
    $keputusan_columns = ['kode'];
    $keputusan_values = ["'$kode'"];

    while ($row = $result->fetch_assoc()) {
        if (!in_array($row['Field'], ['kode', 'nama', 'id'])) {
            // Pisahkan nilai dan nama sub
            list($nilai, $nama_sub) = explode('|', $_POST[$row['Field']]);

            // Tambahkan kolom dan nilai ke tabel alternatif
            $alternatif_columns[] = $row['Field'];
            $alternatif_values[] = "'" . $nama_sub . "'";

            // Tambahkan kolom dan nilai ke tabel keputusan
            $keputusan_columns[] = $row['Field'];
            $keputusan_values[] = $nilai; // Tanpa kutip, karena tipe data int
        }
    }

    // Sisipkan data baru ke tabel alternatif
    $query = "INSERT INTO alternatif (" . implode(',', $alternatif_columns) . ") VALUES (" . implode(',', $alternatif_values) . ")";


    if ($db->query($query)) {
        // Sisipkan data baru ke tabel keputusan
        $query = "INSERT INTO keputusan (" . implode(',', $keputusan_columns) . ") VALUES (" . implode(',', $keputusan_values) . ")";

        if ($db->query($query)) {
            // Sisipkan data ke tabel tambahan
            $tables = ['keputusan_r', 'keputusan_y', 'preferensi', 'j_solusi'];

            foreach ($tables as $table) {
                $insert_query = "INSERT INTO $table (kode) VALUES ('$kode')";
                if (!$db->query($insert_query)) {
                    echo "Error: " . $db->error;
                }
            }

            $query = "INSERT INTO hasil (kode, nama) VALUES ('$kode', '$nama')";
            $db->query($query);

            hitung($db);

            header("Location: d_alternatif.php");
            exit;
        } else {
            // Handle error
            echo "Error: " . $db->error;
        }
    } else {
        echo "Error: " . $db->error;
    }
}

// Logika untuk proses pengeditan data
if (isset($_POST['edit'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];

    // Ambil semua kolom dari tabel 'alternatif'
    $query = "SHOW COLUMNS FROM alternatif";
    $result = $db->query($query);

    // Bangun query untuk memperbarui data di tabel alternatif
    $alternatif_updates = [];
    $keputusan_updates = [];

    while ($row = $result->fetch_assoc()) {
        if (!in_array($row['Field'], ['kode', 'nama', 'id'])) {
            // Pisahkan nilai dan nama sub
            list($nilai, $nama_sub) = explode('|', $_POST[$row['Field']]);

            // Tambahkan update untuk tabel alternatif
            $alternatif_updates[] = $row['Field'] . "='" . $nama_sub . "'";

            // Tambahkan update untuk tabel keputusan
            $keputusan_updates[] = $row['Field'] . "='" . $nilai . "'";
        }
    }

    // Perbarui data di tabel alternatif
    $query = "UPDATE alternatif SET nama='$nama', " . implode(', ', $alternatif_updates) . " WHERE kode='$kode'";

    if ($db->query($query)) {
        // Perbarui data di tabel keputusan
        $query = "UPDATE keputusan SET " . implode(', ', $keputusan_updates) . " WHERE kode='$kode'";

        if ($db->query($query)) {
            hitung($db);

            header("Location: d_alternatif.php");
            exit;
        } else {
            // Handle error untuk keputusan
            echo "Error: " . $db->error;
        }
    } else {
        // Handle error untuk alternatif
        echo "Error: " . $db->error;
    }
}


// Logika untuk proses penghapusan data
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    // Ambil kode alternatif berdasarkan ID yang akan dihapus
    $query = "SELECT kode FROM alternatif WHERE id='$id'";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
    $kode = $row['kode'];

    // Daftar tabel yang harus dihapus
    $tables = ['keputusan', 'keputusan_r', 'keputusan_y', 'j_solusi', 'preferensi', 'hasil'];

    // Hapus data dari setiap tabel berdasarkan kode
    foreach ($tables as $table) {
        $query = "DELETE FROM $table WHERE kode='$kode'";
        if (!$db->query($query)) {
            die("Error deleting from $table: " . $db->error);
        }
    }

    // Hapus data dari tabel alternatif
    $query = "DELETE FROM alternatif WHERE id='$id'";
    $db->query($query);

    if ($db->query($query)) {
        hitung($db);

        header("Location: d_alternatif.php");
        exit;
    } else {
        // Handle error untuk keputusan
        echo "Error: " . $db->error;
    }
}

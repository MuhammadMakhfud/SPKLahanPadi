<?php

// Logika untuk proses penambahan data
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];

    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

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
        // Cek jika kolom bukan 'kode', 'nama', atau 'id'
        if (!in_array($row['Field'], ['kode', 'nama', 'id'])) {
            $tipe_kriteria = getKriteriaTipe($db, $row['Field']); // Cek tipe kolom
            $input_value = $_POST[$row['Field']] ?? ''; // Ambil nilai dari $_POST, pastikan ada

            // Cek tipe kriteria
            if ($tipe_kriteria === 'jenis') {
                list($nilai, $nama_sub) = explode('|', $input_value);
                $alternatif_columns[] = $row['Field'];
                $alternatif_values[] = "'" . $nama_sub . "'";

                $keputusan_columns[] = $row['Field'];
                $keputusan_values[] = $nilai;
            } elseif ($tipe_kriteria === 'nilai_rentang') {
                $tabel_sub = "sub_" . $row['Field']; // Nama tabel dinamis berdasarkan kode kolom
                $query = "SELECT nilai FROM $tabel_sub 
                            WHERE (min IS NULL OR $input_value >= min) 
                            AND (max IS NULL OR $input_value <= max)";
                $sub_result = $db->query($query);

                if ($sub_result && $row_sub = $sub_result->fetch_assoc()) {
                    $alternatif_columns[] = $row['Field'];
                    $alternatif_values[] = "'" . $input_value . "'";

                    $keputusan_columns[] = $row['Field'];
                    $keputusan_values[] = $row_sub['nilai']; // Ambil nilai sesuai baris rentang
                }
            }
        }
    }

    // Sisipkan data baru ke tabel alternatif
    $query = "INSERT INTO alternatif (" . implode(',', $alternatif_columns) . ") VALUES (" . implode(',', $alternatif_values) . ")";
    echo "Query Hasil: $query<br>";

    if ($db->query($query)) {
        // Sisipkan data baru ke tabel keputusan
        $query = "INSERT INTO keputusan (" . implode(',', $keputusan_columns) . ") VALUES (" . implode(',', $keputusan_values) . ")";
        echo "Query Hasil: $query<br>";

        if ($db->query($query)) {
            // Sisipkan data ke tabel tambahan
            $tables = ['saw_keputusan_r', 'saw_preferensi', 'topsis_keputusan_r', 'topsis_keputusan_y', 'topsis_jarakmatriks', 'topsis_preferensi'];

            foreach ($tables as $table) {
                $insert_query = "INSERT INTO $table (kode) VALUES ('$kode')";
                if (!$db->query($insert_query)) {
                    echo "Error: " . $db->error;
                }
            }

            $query = "INSERT INTO hasil (kode, nama) VALUES ('$kode', '$nama')";
            $db->query($query);

            hitung($db);
            echo "Query Hasil: $query<br>";

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

    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    // Ambil semua kolom dari tabel 'alternatif'
    $query = "SHOW COLUMNS FROM alternatif";
    $result = $db->query($query);

    $alternatif_updates = [];
    $keputusan_updates = [];

    while ($row = $result->fetch_assoc()) {
        // Cek jika kolom bukan 'kode', 'nama', atau 'id'
        if (!in_array($row['Field'], ['kode', 'nama', 'id'])) {
            $tipe_kriteria = getKriteriaTipe($db, $row['Field']); // Cek tipe kolom
            $input_value = $_POST[$row['Field']] ?? ''; // Ambil nilai dari $_POST, pastikan ada

            // Cek tipe kriteria
            if ($tipe_kriteria === 'jenis') {
                list($nilai, $nama_sub) = explode('|', $input_value);
                $alternatif_updates[] = $row['Field'] . " = '" . $nama_sub . "'";
                $keputusan_updates[] = $row['Field'] . " = " . $nilai;
            } elseif ($tipe_kriteria === 'nilai_rentang') {
                $tabel_sub = "sub_" . strtolower($row['Field']); // Nama tabel dinamis berdasarkan kode kolom
                $query = "SELECT nilai FROM $tabel_sub 
                            WHERE (min IS NULL OR $input_value >= min) 
                            AND (max IS NULL OR $input_value <= max)";
                $sub_result = $db->query($query);

                if ($sub_result && $row_sub = $sub_result->fetch_assoc()) {
                    $alternatif_updates[] = $row['Field'] . " = '" . $input_value . "'";
                    $keputusan_updates[] = $row['Field'] . " = " . $row_sub['nilai'];
                }
            }
        }
    }

    // Update data di tabel alternatif
    $query = "UPDATE alternatif SET nama = '$nama', " . implode(',', $alternatif_updates) . " WHERE kode = '$kode'";
    echo "Query Update Alternatif: $query<br>";

    if ($db->query($query)) {
        // Update data di tabel keputusan
        $query = "UPDATE keputusan SET " . implode(',', $keputusan_updates) . " WHERE kode = '$kode'";
        echo "Query Update Keputusan: $query<br>";

        if ($db->query($query)) {
            hitung($db); // Hitung ulang jika ada perubahan
            echo "Data berhasil diperbarui.";
            header("Location: d_alternatif.php");
            exit;
        } else {
            echo "Error: " . $db->error;
        }
    } else {
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
    $tables = ['keputusan', 'saw_keputusan_r', 'saw_preferensi', 'topsis_keputusan_r', 'topsis_keputusan_y', 'topsis_jarakmatriks', 'topsis_preferensi', 'hasil'];

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

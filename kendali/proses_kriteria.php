<?php

$alert_modal = '';

// Handle adding new criteria
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $tipe = $_POST['tipe'];
    $atribut = $_POST['atribut'];
    $bobot = $_POST['bobot'] / 100;

    // Check if the nama already exists
    $nama = $db->real_escape_string($nama); // Sanitasi input
    $query = "SELECT COUNT(*) as count FROM kriteria WHERE nama = '$nama'";
    $result = $db->query($query);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        header("Location: d_kriteria.php?error=duplicate");
        exit;
    } else {
        // If no errors, insert data
        $query = "INSERT INTO kriteria (kode, nama, tipe, atribut, bobot) VALUES ('$kode', '$nama', '$tipe', '$atribut', $bobot)";
        $db->query($query);

        // Create table for the new criterion based on tipe
        $table_name = 'sub_' . strtolower($kode);

        // Check the type and create the corresponding table
        if ($tipe == 'jenis') {
            // Create table for 'jenis' type (id, nama, nilai)
            $query = "CREATE TABLE IF NOT EXISTS `$table_name` (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama VARCHAR(30) NOT NULL,
                nilai INT NOT NULL
            )";
        } elseif ($tipe == 'nilai_rentang') {
            // Create table for 'nilai_rentang' type (id, min, max, satuan, nilai)
            $query = "CREATE TABLE IF NOT EXISTS `$table_name` (
                id INT AUTO_INCREMENT PRIMARY KEY,
                min DECIMAL(10, 2) NULL,
                max DECIMAL(10, 2) NULL,
                satuan VARCHAR(10) NOT NULL,
                nilai INT NOT NULL
            )";
        }

        // Execute the query to create the table
        $db->query($query);

        // Add a new column to  table
        $tables = [
            'alternatif' => 'VARCHAR(30)',
            'keputusan' => 'INT',
            'saw_keputusan_r' => 'FLOAT',
            'topsis_keputusan_r' => 'FLOAT',
            'topsis_keputusan_y' => 'FLOAT',
            'topsis_matrikssolusi' => 'FLOAT'
        ];
        $column_name = $db->real_escape_string($kode); // Sanitasi nama kolom
        foreach ($tables as $table => $type) {
            // Tentukan nilai default berdasarkan tipe data kolom
            if ($type === 'VARCHAR(30)') {
                $default_value = "''"; // Default untuk VARCHAR
            } else {
                $default_value = '0'; // Default untuk INT dan FLOAT
            }

            // Bangun query ALTER TABLE
            $query = "ALTER TABLE `$table` ADD COLUMN `$column_name` $type DEFAULT $default_value";

            // Jalankan query
            if (!$db->query($query)) {
                echo "Error adding column to $table: " . $db->error . "<br>";
            }
        }
        header("Location: d_kriteria.php?");
        exit;
    }
}

// Handle editing criteria
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $tipe = $_POST['tipe'];
    $atribut = $_POST['atribut'];
    $bobot = $_POST['bobot'] / 100;

    // Get the old kode
    $id = intval($id); // Sanitasi input
    $query = "SELECT kode FROM kriteria WHERE id = $id";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
    $old_kode = $row['kode'];

    // Check if the new kode already exists
    $query = "SELECT COUNT(*) as count FROM kriteria WHERE kode = '$kode' AND kode != '$old_kode'";
    $result = $db->query($query);
    $row = $result->fetch_assoc();

    // Check if the new nama already exists
    $nama = $db->real_escape_string($nama); // Sanitasi input
    $query = "SELECT COUNT(*) as count FROM kriteria WHERE nama = '$nama' AND id != $id";
    $result = $db->query($query);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        $alert_modal = '<div class="alert alert-danger" role="alert">Nama Kriteria sudah ada.</div>';
        exit;
    }

    // Update kriteria
    $query = "UPDATE kriteria SET kode = '$kode', nama = '$nama', tipe = '$tipe',atribut = '$atribut', bobot = $bobot WHERE id = $id";
    $db->query($query);

    // Update table name if kode has changed
    if ($old_kode !== $kode) {
        // Rename the old table to the new table name
        $old_table_name = 'sub_' . $old_kode;
        $new_table_name = 'sub_' . $kode;

        // Rename the table
        $query = "RENAME TABLE `$old_table_name` TO `$new_table_name`";
        $db->query($query);
    }

    header("Location: d_kriteria.php");
    exit;
}

// Displaying the alert based on the URL parameters
if (isset($_GET['error']) && $_GET['error'] == 'duplicate') {
    $alert_modal = '<div class="alert alert-danger p-1" role="alert">Nama kriteria sudah ada.</div>';
} else {
    $alert_modal = '';
}

// Handle deleting criteria
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    // Get the kode of the criterion to delete
    $id = intval($id); // Sanitasi input
    $query = "SELECT kode FROM kriteria WHERE id = $id";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
    $kode = $row['kode'];

    // Delete the criterion
    $query = "DELETE FROM kriteria WHERE id = $id";
    $db->query($query);

    // Drop the table for sub-criteria
    $table_name = 'sub_' . $kode;
    $query = "DROP TABLE IF EXISTS `$table_name`";
    $db->query($query);

    $tables = [
        'alternatif',
        'keputusan',
        'saw_keputusan_r',
        'topsis_keputusan_r',
        'topsis_keputusan_y',
        'topsis_matrikssolusi'
    ];

    $column_name = $db->real_escape_string($kode); // Sanitasi nama kolom

    foreach ($tables as $table) {
        $query = "ALTER TABLE `$table` DROP COLUMN `$column_name`";
        if (!$db->query($query)) {
            echo "Error dropping column from $table: " . $db->error . "<br>";
        }
    }

    header("Location: d_kriteria.php");
    exit;
}

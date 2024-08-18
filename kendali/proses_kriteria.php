<?php

$alert_modal = '';

// Handle adding new criteria
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
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
        $query = "INSERT INTO kriteria (kode, nama, atribut, bobot) VALUES ('$kode', '$nama', '$atribut', $bobot)";
        $db->query($query);

        // Create table for the new criterion
        createSubKriteriaTable($db, $kode);

        // Add a new column to the alternatif table
        addColumn($db, $kode);

        header("Location: d_kriteria.php?");
        exit;
    }
}

// Displaying the alert based on the URL parameters
if (isset($_GET['error']) && $_GET['error'] == 'duplicate') {
    $alert_modal = '<div class="alert alert-danger" role="alert">Nama kriteria sudah ada.</div>';
} else {
    $alert_modal = '';
}

// Handle editing criteria
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
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
    $query = "UPDATE kriteria SET kode = '$kode', nama = '$nama', atribut = '$atribut', bobot = $bobot WHERE id = $id";
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
        'keputusan_r',
        'keputusan_y',
        'm_solusi'
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
<?php

//head
function setActiveNavLink($linkId)
{
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var navLink = document.getElementById('$linkId');
            if (navLink) {
                navLink.classList.add('active');
            }
        });
    </script>";
}

//kriteria
function getNextKode($db, $tabel, $huruf)
{
    // Fungsi untuk mendapatkan kode kriteria berikutnya berdasarkan slot yang kosong
    $query = "SELECT kode FROM $tabel ORDER BY kode ASC";
    $result = $db->query($query);
    $codes = [];

    while ($row = $result->fetch_assoc()) {
        $codes[] = $row['kode'];
    }
    // Cari kode yang kosong
    for ($i = 1; $i <= count($codes) + 1; $i++) {
        $kode = $huruf . $i;
        if (!in_array($kode, $codes)) {
            return $kode;
        }
    }
}
function createSubKriteriaTable($db, $kode)
{
    $table_name = 'sub_' . $kode;
    $query = "CREATE TABLE IF NOT EXISTS `$table_name` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        nilai INT NOT NULL
    )";
    $db->query($query);
}
function addColumn($db, $kode)
{
    $tables = [
        'alternatif' => 'VARCHAR(50)',
        'keputusan' => 'INT',
        'keputusan_r' => 'FLOAT',
        'keputusan_y' => 'FLOAT',
        'm_solusi' => 'FLOAT'
    ];

    $column_name = $db->real_escape_string($kode); // Sanitasi nama kolom

    foreach ($tables as $table => $type) {
        // Tentukan nilai default berdasarkan tipe data kolom
        if ($type === 'VARCHAR(50)') {
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
}
function generateAlerts($db)
{
    $alert_messages = '';

    // Logika untuk memeriksa bobot kriteria
    $query = "SELECT SUM(bobot) AS total_bobot FROM kriteria";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
    $total_bobot = $row['total_bobot'];
    $total_bobot = round($total_bobot * 100, 2); // Mengubah total bobot ke skala 100% dan membulatkan ke 2 desimal

    if ($total_bobot < 100) {
        $alert_messages .= '!!! Total bobot kurang dari 100%. Mohon periksa kembali data kriteria Anda. !!!';
    } elseif ($total_bobot > 100) {
        $alert_messages .= '!!! Total bobot lebih dari 100%. Mohon periksa kembali data kriteria Anda. !!!';
    }

    // Logika untuk memeriksa parameter error
    // if (isset($_GET['error']) && $_GET['error'] == 'duplicate') {
    //     $alert_messages .= '<div class="alert alert-danger" role="alert">Nama kriteria sudah ada.</div>';
    // }

    // Mengembalikan semua pesan alert yang digabungkan
    return $alert_messages;
}

//subkriteria
function getSubKriteriaTables($db)
{
    $tables = [];
    $query = "SHOW TABLES LIKE 'sub_%'";
    $result = $db->query($query);
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
    return $tables;
}
function getKriteriaData($db, $kode)
{
    $query = "SELECT kode, nama FROM kriteria WHERE kode = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "s", $kode);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

//alternatif
function getSubKriteriaOptions($db, $kode_alternatif)
{
    // Function untuk mengambil data sub-kriteria berdasarkan kode alternatif
    $options = [];
    // Pastikan kode alternatif disediakan
    if ($kode_alternatif) {
        // Bangun nama tabel berdasarkan kode alternatif
        $tabel_sub = "sub_" . strtoupper($kode_alternatif);

        // Query untuk mendapatkan data dari tabel sub_x
        $query = "SELECT nama, nilai FROM $tabel_sub";
        $result = $db->query($query);

        // Proses hasil query
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // Simpan nama sebagai opsi dan nilai sebagai value
                $options[$row['nilai']] = $row['nama'];
            }
        }
    }

    return $options;
}

//perhitungan
function hitung($db)
{
    hitungKeputusanR($db, 'keputusan', 'kriteria', 'keputusan_r');
    hitungKeputusanY($db, 'keputusan_r', 'kriteria', 'keputusan_y');
    hitungMatrikSolusi($db, 'keputusan_y', 'kriteria', 'm_solusi');
    hitungJarakSolusi($db, 'keputusan_y', 'm_solusi', 'j_solusi');
    hitungPreferensi($db);
}

function getTableColumns($db, $tableName, $excludedColumns = ['kode', 'id'])
{
    $query = "SHOW COLUMNS FROM $tableName";
    $result = $db->query($query);

    $columns = [];
    while ($row = $result->fetch_assoc()) {
        if (!in_array($row['Field'], $excludedColumns)) {
            $columns[] = $row['Field'];
        }
    }
    return $columns;
}

function getTableData($db, $tableName, $urut)
{
    $query = "SELECT * FROM $tableName ORDER BY $urut ASC";
    return $db->query($query);
}

function hitungKeputusanR($db, $tableKeputusan, $tableKriteria, $tableKeputusanR)
{
    // Ambil data kriteria
    $queryKriteria = "SELECT kode, atribut FROM $tableKriteria";
    $resultKriteria = $db->query($queryKriteria);

    $kriteria = [];
    while ($row = $resultKriteria->fetch_assoc()) {
        $kriteria[$row['kode']] = $row['atribut'];
    }

    echo "";
    // Loop melalui setiap kriteria untuk menghitung normalisasi
    foreach ($kriteria as $kodeKriteria => $atribut) {
        // Ambil nilai max/min untuk kriteria ini
        $queryMaxMin = "SELECT MAX($kodeKriteria) AS maxVal, MIN($kodeKriteria) AS minVal FROM $tableKeputusan";
        $resultMaxMin = $db->query($queryMaxMin);
        $row = $resultMaxMin->fetch_assoc();

        $maxValue = (float) $row['maxVal'];
        $minValue = (float) $row['minVal'];
        // echo "min $minValue,  max $maxValue  ";

        // Ambil data untuk semua alternatif berdasarkan kriteria ini
        $queryKeputusan = "SELECT kode, $kodeKriteria FROM $tableKeputusan";
        $resultKeputusan = $db->query($queryKeputusan);

        // Update nilai-nilai di tabel keputusan_r
        while ($rowKeputusan = $resultKeputusan->fetch_assoc()) {
            $kodeAlternatif = $rowKeputusan['kode'];
            $value = (float) $rowKeputusan[$kodeKriteria]; // Cast ke FLOAT
            $normalizedValue = 0.0;

            // Perhitungan normalisasi
            if ($atribut == 'benefit' && $maxValue != 0) {
                $normalizedValue = round($value / $maxValue, 2);
                // echo "$kodeAlternatif Benefit: $value / $maxValue = $normalizedValue<br>";
            } elseif ($atribut == 'cost' && $value != 0) {
                $normalizedValue = round($minValue / $value, 2);
                // echo "Cost: $minValue / $value = $normalizedValue<br>";
            }

            // Update nilai di tabel keputusan_r
            $updateQuery = "UPDATE $tableKeputusanR SET $kodeKriteria = $normalizedValue WHERE kode = '$kodeAlternatif'";
            $db->query($updateQuery);
        }
    }
}

function hitungKeputusanY($db, $tableKeputusanR, $tableKriteria, $tableKeputusanY)
{
    // Ambil bobot kriteria dari tabel 'kriteria'
    $queryKriteria = "SELECT kode, bobot FROM $tableKriteria";
    $resultKriteria = $db->query($queryKriteria);

    $kriteriaBobot = [];
    while ($rowKriteria = $resultKriteria->fetch_assoc()) {
        $kriteriaBobot[$rowKriteria['kode']] = $rowKriteria['bobot'];
    }

    // Ambil data dari tabel 'keputusan_r'
    $queryKeputusanR = "SELECT * FROM $tableKeputusanR";
    $resultKeputusanR = $db->query($queryKeputusanR);

    // Menghitung dan memperbarui nilai matriks keputusan ternormalisasi terbobot (Y)
    while ($rowKeputusanR = $resultKeputusanR->fetch_assoc()) {
        $kode = $rowKeputusanR['kode'];

        foreach ($kriteriaBobot as $kodeKriteria => $bobot) {
            $nilai = $rowKeputusanR[$kodeKriteria];
            $nilaiTerbobot = $nilai * $bobot;
            $nilaiTerbobot = round($nilaiTerbobot, 2); // Format dengan dua angka desimal

            // Update nilai di tabel keputusan_y
            $updateQuery = "UPDATE $tableKeputusanY SET $kodeKriteria = $nilaiTerbobot WHERE kode = '$kode'";
            $db->query($updateQuery);
        }
    }
}

function hitungMatrikSolusi($db, $tableKeputusanY, $tableKriteria, $tableMSolusi)
{
    // Ambil data kriteria
    $queryKriteria = "SELECT kode, atribut FROM $tableKriteria";
    $resultKriteria = $db->query($queryKriteria);

    $atributKriteria = [];
    while ($row = $resultKriteria->fetch_assoc()) {
        $atributKriteria[$row['kode']] = $row['atribut'];
    }

    // Ambil data dari tabel keputusan_y
    $queryKeputusanY = "SELECT * FROM $tableKeputusanY";
    $resultKeputusanY = $db->query($queryKeputusanY);

    $dataKeputusanY = [];
    while ($row = $resultKeputusanY->fetch_assoc()) {
        $dataKeputusanY[] = $row;
    }

    // Tentukan kriteria berdasarkan kolom tabel
    $kriteria = array_keys($dataKeputusanY[0]);
    array_shift($kriteria); // Menghapus kolom 'kode'

    // Inisialisasi nilai A+ dan A-
    $aPlus = [];
    $aMinus = [];

    // Hitung nilai A+ dan A-
    foreach ($kriteria as $kriteriaItem) {
        $atribut = $atributKriteria[$kriteriaItem] ?? null;

        // Ambil nilai max/min dari data
        $maxValue = max(array_column($dataKeputusanY, $kriteriaItem));
        $minValue = min(array_column($dataKeputusanY, $kriteriaItem));

        if ($atribut == 'benefit') {
            $aPlus[$kriteriaItem] = $maxValue;
            $aMinus[$kriteriaItem] = $minValue;
        } elseif ($atribut == 'cost') {
            $aPlus[$kriteriaItem] = $minValue;
            $aMinus[$kriteriaItem] = $maxValue;
        }
    }

    // Update nilai di tabel m_solusi
    foreach (['A+', 'A-'] as $kode) {
        $values = [];
        $data = $kode === 'A+' ? $aPlus : $aMinus;

        foreach ($kriteria as $kriteriaItem) {
            if (isset($data[$kriteriaItem])) {
                $values[] = "$kriteriaItem = " . round($data[$kriteriaItem], 2);
            }
        }
        $queryUpdate = "UPDATE $tableMSolusi SET " . implode(', ', $values) . " WHERE kode = '$kode'";
        if ($db->query($queryUpdate));
    }
}

function hitungJarakSolusi($db, $tableKeputusanY, $tableMSolusi, $tableJSolusi)
{
    // Ambil data dari tabel keputusan_y
    $queryKeputusanY = "SELECT * FROM $tableKeputusanY";
    $resultKeputusanY = $db->query($queryKeputusanY);

    // Ambil data dari tabel m_solusi
    $queryMSolusi = "SELECT * FROM $tableMSolusi";
    $resultMSolusi = $db->query($queryMSolusi);

    // Simpan data m_solusi dalam array untuk perhitungan
    $dataMSolusi = [];
    while ($row = $resultMSolusi->fetch_assoc()) {
        $kodeSolusi = $row['kode'];
        unset($row['kode']); // Hapus kolom kode
        $dataMSolusi[$kodeSolusi] = $row;
    }

    // Loop untuk setiap alternatif
    while ($rowKeputusanY = $resultKeputusanY->fetch_assoc()) {
        $kodeAlternatif = $rowKeputusanY['kode'];
        unset($rowKeputusanY['kode']); // Hapus kolom kode

        $distancesPlus = $distancesMinus = 0.0;


        // Hitung jarak untuk setiap kriteria
        foreach ($rowKeputusanY as $kriteria => $nilaiAlternatif) {
            if ($kriteria === 'id') {
                continue; // Lewati kolom id
            }

            $nilaiAPlus = $dataMSolusi['A+'][$kriteria] ?? 0;
            $nilaiAMinus = $dataMSolusi['A-'][$kriteria] ?? 0;

            // Hitung jarak
            $distancePlus = pow($nilaiAlternatif - $nilaiAPlus, 2);
            $distanceMinus = pow($nilaiAlternatif - $nilaiAMinus, 2);

            $distancesPlus += $distancePlus;
            $distancesMinus += $distanceMinus;
        }

        // Akhiri perhitungan jarak dengan akar kuadrat
        $distancesPlus = sqrt($distancesPlus);
        $distancesMinus = sqrt($distancesMinus);

        // Bulatkan hasil ke 3 angka belakang koma
        $distancesPlus = number_format($distancesPlus, 3);
        $distancesMinus = number_format($distancesMinus, 3);

        // Tampilkan hasil akhir untuk verifikasi

        // Update tabel j_solusi
        $updateQuery = "UPDATE $tableJSolusi SET `Di+` = $distancesPlus, `Di-` = $distancesMinus WHERE kode = '$kodeAlternatif'";
        if (!$db->query($updateQuery)) {
            echo "Error updating j_solusi for $kodeAlternatif: " . $db->error . "<br>";
        }
    }
}

function hitungPreferensi($db)
{
    // Ambil semua data dari tabel j_solusi
    $query = "SELECT kode, `Di+`, `Di-` FROM j_solusi";
    $result = $db->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $kode = $row['kode'];
            $Di_plus = $row['Di+'];
            $Di_minus = $row['Di-'];

            // Hitung nilai preferensi V
            $nilai_preferensi = round($Di_minus / ($Di_plus + $Di_minus), 3);

            // Simpan hasilnya ke tabel preferensi
            $updateQuery = "UPDATE preferensi SET nilai = $nilai_preferensi WHERE kode = '$kode'";
            $db->query($updateQuery);
        }
    } else {
        echo "Error: " . $db->error;
    }
}



function updateHasilTable($db)
{
    // Ambil data dari tabel preferensi, mengurutkan berdasarkan nilai terbesar
    $query = "SELECT p.kode, p.nilai, a.nama  FROM preferensi p JOIN alternatif a ON p.kode = a.kode ORDER BY p.nilai DESC";

    $result = $db->query($query);

    if ($result) {
        $rank = 1;
        while ($row = $result->fetch_assoc()) {
            $kode = $row['kode'];
            $nama = $row['nama'];
            $nilai = $row['nilai'];

            // Update data ke tabel hasil dengan peringkat
            $updateQuery = "UPDATE hasil 
                            SET nama = '$nama', nilai = $nilai, rangking = $rank 
                            WHERE kode = '$kode'";
            $db->query($updateQuery);

            $rank++;
        }
    } else {
        echo "Error: " . $db->error;
    }
}

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

function formatAngka($angka)
{
    return rtrim(rtrim(number_format($angka, 2, '.', ''), '0'), '.'); // Hilangkan .00 atau .0
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


function getKriteriaTipe($db, $kode_kriteria)
{
    $query = "SELECT tipe FROM kriteria WHERE kode = '$kode_kriteria'";
    $result = $db->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        return $row['tipe'];
    }
    return null;
}

//alternatif
function getSubKriteriaOptions($db, $kode_alternatif)
{
    // Function untuk mengambil data sub-kriteria berdasarkan kode alternatif
    $options = [];

    // Pastikan kode alternatif disediakan
    if ($kode_alternatif) {
        // Bangun nama tabel berdasarkan kode alternatif
        $tabel_sub = "sub_" . strtolower($kode_alternatif);

        // Cek apakah tabel ada
        $query_check_table = "SHOW TABLES LIKE '$tabel_sub'";
        $result_check_table = $db->query($query_check_table);

        if ($result_check_table && $result_check_table->num_rows > 0) {
            // Jika tabel ada, lakukan query untuk mendapatkan data
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
    }

    return $options;
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


//perhitungan
function hitung($db)
{
    hitungSawKeputusanR($db, 'keputusan', 'kriteria', 'saw_keputusan_r');
    hitungSawPreferensi($db, 'saw_keputusan_r', 'kriteria', 'saw_preferensi');
    hitungTopsisKeputusanR($db, 'keputusan', 'kriteria', 'topsis_keputusan_r');
    hitungTopsisKeputusanY($db, 'topsis_keputusan_r', 'kriteria', 'topsis_keputusan_y');
    hitungTopsisMatrikSolusi($db, 'topsis_keputusan_y', 'kriteria', 'topsis_matrikssolusi');
    hitungTopsisJarakMatriks($db, 'topsis_keputusan_y', 'topsis_matrikssolusi', 'topsis_jarakmatriks');
    hitungTopsisPreferensi($db, 'topsis_jarakmatriks', 'topsis_preferensi');
}

function hitungSawKeputusanR($db, $tableKeputusan, $tableKriteria, $tableKeputusanR)
{
    // Ambil data kriteria
    $queryKriteria = "SELECT kode, atribut FROM $tableKriteria";
    $resultKriteria = $db->query($queryKriteria);

    $kriteria = [];
    while ($row = $resultKriteria->fetch_assoc()) {
        $kriteria[$row['kode']] = $row['atribut'];
    }

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
                $normalizedValue = round($value / $maxValue, 4);
                // echo "$kodeAlternatif Benefit: $value / $maxValue = $normalizedValue<br>";
            } elseif ($atribut == 'cost' && $value != 0) {
                $normalizedValue = round($minValue / $value, 4);
                // echo "Cost: $minValue / $value = $normalizedValue<br>";
            }

            // Update nilai di tabel keputusan_r
            $updateQuery = "UPDATE $tableKeputusanR SET $kodeKriteria = $normalizedValue WHERE kode = '$kodeAlternatif'";
            $db->query($updateQuery);
        }
    }
}
function hitungSawPreferensi($db, $tableKeputusanR, $tableKriteria, $tablePreferensi)
{
    // Ambil bobot untuk setiap kriteria
    $queryKriteria = "SELECT kode, bobot FROM $tableKriteria";
    $resultKriteria = $db->query($queryKriteria);

    $bobotKriteria = [];
    while ($row = $resultKriteria->fetch_assoc()) {
        $bobotKriteria[$row['kode']] = (float) $row['bobot'];
    }

    // Ambil data alternatif dari tabel keputusan_r
    $queryKeputusanR = "SELECT * FROM $tableKeputusanR";
    $resultKeputusanR = $db->query($queryKeputusanR);

    while ($rowKeputusanR = $resultKeputusanR->fetch_assoc()) {
        $kodeAlternatif = $rowKeputusanR['kode'];
        $nilaiPreferensi = 0.0;

        // Hitung nilai preferensi berdasarkan bobot kriteria
        foreach ($bobotKriteria as $kodeKriteria => $bobot) {
            $nilaiNormalisasi = (float) $rowKeputusanR[$kodeKriteria];
            $nilaiPreferensi += $nilaiNormalisasi * $bobot;
        }

        // Batasi nilai preferensi menjadi 4 desimal
        $nilaiPreferensi = round($nilaiPreferensi, 4);

        // Simpan nilai preferensi ke tabel preferensi
        $queryUpdatePreferensi = "UPDATE $tablePreferensi SET nilai = $nilaiPreferensi WHERE kode = '$kodeAlternatif'";
        $db->query($queryUpdatePreferensi);
    }
}

function hitungTopsisKeputusanR($db, $tableKeputusan, $tableKriteria, $tableKeputusanR)
{
    // Ambil data kriteria
    $queryKriteria = "SELECT kode FROM $tableKriteria";
    $resultKriteria = $db->query($queryKriteria);

    $kriteria = [];
    while ($row = $resultKriteria->fetch_assoc()) {
        $kriteria[] = $row['kode'];
    }

    // Jika tidak ada kriteria, hentikan proses
    if (empty($kriteria)) {
        return;
    }

    // Hitung total kuadrat untuk setiap kriteria
    $totalKuadrat = [];
    foreach ($kriteria as $kodeKriteria) {
        $query = "SELECT $kodeKriteria FROM $tableKeputusan";
        $result = $db->query($query);

        $totalKuadrat[$kodeKriteria] = 0;
        while ($row = $result->fetch_assoc()) {
            $value = (float)$row[$kodeKriteria];
            $totalKuadrat[$kodeKriteria] += pow($value, 2);
        }
    }

    // Loop data alternatif untuk menghitung matriks R
    $queryAlternatif = "SELECT kode, " . implode(", ", $kriteria) . " FROM $tableKeputusan";
    $resultAlternatif = $db->query($queryAlternatif);

    while ($rowAlternatif = $resultAlternatif->fetch_assoc()) {
        $kodeAlternatif = $rowAlternatif['kode'];

        foreach ($kriteria as $kodeKriteria) {
            $value = (float)$rowAlternatif[$kodeKriteria];
            $normalizedValue = 0.0;

            // Normalisasi
            if (isset($totalKuadrat[$kodeKriteria]) && $totalKuadrat[$kodeKriteria] > 0) {
                $normalizedValue = round($value / sqrt($totalKuadrat[$kodeKriteria]), 4);
            }

            // Update nilai normalisasi ke tabel keputusan_r
            $updateQuery = "UPDATE $tableKeputusanR SET $kodeKriteria = $normalizedValue WHERE kode = '$kodeAlternatif'";
            $db->query($updateQuery);
        }
    }
}

function hitungTopsisKeputusanY($db, $tableKeputusanR, $tableKriteria, $tableKeputusanY)
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
            $nilaiTerbobot = round($nilaiTerbobot, 4); // Format dengan dua angka desimal

            // Update nilai di tabel keputusan_y
            $updateQuery = "UPDATE $tableKeputusanY SET $kodeKriteria = $nilaiTerbobot WHERE kode = '$kode'";
            $db->query($updateQuery);
        }
    }
}

function hitungTopsisMatrikSolusi($db, $tableKeputusanY, $tableKriteria, $tableMatriksSolusi)
{
    // Ambil data kriteria
    $queryKriteria = "SELECT kode, atribut FROM $tableKriteria";
    $resultKriteria = $db->query($queryKriteria);

    // Cek jika tidak ada data di tabel kriteria
    if ($resultKriteria->num_rows == 0
    ) {
        return;  // Jika tidak ada data, hentikan proses
    }

    $atributKriteria = [];
    while ($row = $resultKriteria->fetch_assoc()) {
        $atributKriteria[$row['kode']] = $row['atribut'];
    }

    // Ambil data dari tabel keputusan_y
    $queryKeputusanY = "SELECT * FROM $tableKeputusanY";
    $resultKeputusanY = $db->query($queryKeputusanY);

    $dataKeputusanY = [];
    if ($resultKeputusanY) {
        while ($row = $resultKeputusanY->fetch_assoc()) {
            $dataKeputusanY[] = $row;
        }
    }
    // Tentukan kriteria berdasarkan kolom tabel
    $kriteria = !empty($dataKeputusanY) ? array_keys($dataKeputusanY[0]) : [];
    array_shift($kriteria); // Menghapus kolom 'kode'

    // Jika tidak ada kriteria, hentikan proses
    if (empty($kriteria)) {
        return;
    }
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
                $values[] = "$kriteriaItem = " . round($data[$kriteriaItem], 4);
            }
        }
        $queryUpdate = "UPDATE $tableMatriksSolusi SET " . implode(', ', $values) . " WHERE kode = '$kode'";
        if ($db->query($queryUpdate));
    }
}

function hitungTopsisJarakMatriks($db, $tableKeputusanY, $tableMatriksSolusi, $tableJarakMatriks)
{
    // Ambil data dari tabel keputusan_y
    $queryKeputusanY = "SELECT * FROM $tableKeputusanY";
    $resultKeputusanY = $db->query($queryKeputusanY);

    // Ambil data dari tabel m_solusi
    $queryMSolusi = "SELECT * FROM $tableMatriksSolusi";
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

        // Bulatkan hasil ke 4 angka belakang koma
        $distancesPlus = number_format($distancesPlus, 4);
        $distancesMinus = number_format($distancesMinus, 4);

        // Tampilkan hasil akhir untuk verifikasi

        // Update tabel j_solusi
        $updateQuery = "UPDATE $tableJarakMatriks SET `Di+` = $distancesPlus, `Di-` = $distancesMinus WHERE kode = '$kodeAlternatif'";
        if (!$db->query($updateQuery)) {
            echo "Error updating j_solusi for $kodeAlternatif: " . $db->error . "<br>";
        }
    }
}

function hitungTopsisPreferensi($db, $tableJarakMatriks, $tablePreferensi)
{
    // Ambil semua data dari tabel jarak solusi
    $query = "SELECT kode, `Di+`, `Di-` FROM $tableJarakMatriks";
    $result = $db->query($query);

    // Cek apakah ada data
    if ($result->num_rows == 0) {
        return; // Jika tidak ada data, hentikan proses
    }

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if (empty($row['kode'])) {
                return;
            }
            
            $kode = $row['kode'];
            $Di_plus = $row['Di+'];
            $Di_minus = $row['Di-'];


            // Pastikan pembagi tidak nol
            if ($Di_plus + $Di_minus == 0) {
                return; // Hentikan proses jika pembagi nol
            }

            // Hitung nilai preferensi V
            $nilai_preferensi = round($Di_minus / ($Di_plus + $Di_minus), 4);

            // Simpan hasilnya ke tabel preferensi
            $updateQuery = "UPDATE $tablePreferensi SET nilai = $nilai_preferensi WHERE kode = '$kode'";
            $db->query($updateQuery);
        }
    } else {
        return;
    }
}

function gabungPreferensiSawTopsis($db, $tableSaw, $tableTopsis, $tableGabungan)
{
    // Ambil data preferensi dari tabel SAW dan TOPSIS
    $querySaw = "SELECT kode, nilai AS nilaiSaw FROM $tableSaw";
    $queryTopsis = "SELECT kode, nilai AS nilaiTopsis FROM $tableTopsis";

    $resultSaw = $db->query($querySaw);
    $resultTopsis = $db->query($queryTopsis);

    if (!$resultSaw || !$resultTopsis) {
        echo "Error: " . $db->error;
        return;
    }

    // Simpan hasil preferensi dari SAW ke dalam array
    $preferensiSaw = [];
    while ($row = $resultSaw->fetch_assoc()) {
        $preferensiSaw[$row['kode']] = (float)$row['nilaiSaw'];
    }

    // Gabungkan nilai preferensi SAW dan TOPSIS
    $gabungan = [];
    while ($row = $resultTopsis->fetch_assoc()) {
        $kode = $row['kode'];
        $nilaiTopsis = (float)$row['nilaiTopsis'];

        if (isset($preferensiSaw[$kode])) {
            $nilaiSaw = $preferensiSaw[$kode];
            $nilaiGabungan = round(($nilaiSaw + $nilaiTopsis) / 2, 4);
            $gabungan[] = ['kode' => $kode, 'nilai' => $nilaiGabungan];
        }
    }

    // Urutkan nilai gabungan dari yang terbesar ke yang terkecil
    usort($gabungan, function ($a, $b) {
        return $b['nilai'] <=> $a['nilai']; // Descending order
    });

    // Simpan ke tabel gabungan dan tambahkan ranking
    $rank = 1;
    foreach ($gabungan as $item) {
        $kode = $item['kode'];
        $nilai = $item['nilai'];

        $updateQuery = "UPDATE $tableGabungan SET nilai = $nilai, ranking = $rank WHERE kode = '$kode'";
        $db->query($updateQuery);
        $rank++;
    }
}

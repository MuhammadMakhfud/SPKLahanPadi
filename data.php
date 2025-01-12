<!DOCTYPE html>
<html lang="en">

<?php
require_once 'kendali/koneksi.php';
?>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistem Pendukung Keputusan Lahan Padi dengan kombinasi metode SAW dan TOPSIS" />
    <meta name="author" content="Syaffira" />
    <title>SPK Lahan Padi</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/user.css" rel="stylesheet" />
    <link rel='short icon' href='assets/img/padi.png'>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script async data-id="five-server" src="http://localhost:5500/fiveserver.js"></script>

</head>

<body>
    <!-- Awal Navbar -->
    <nav class="navbar sticky-top navbar-dark" style="background-color: #2E5077 ;">
        <a class="navbar-brand no-hover" href="data.php" style="font-weight: bold; color: white;">SPK Lahan Padi</a>
        <div class="navbar-nav d-flex flex-row align-items-center">
            <!-- Navbar Links -->
            <a class="navbar-brand" href="index.php">Beranda</a>
            <a class="navbar-brand active" href="data.php">Data</a>
        </div>
    </nav>
    <!-- Akhir Navbar -->

    <!-- Awal Isi -->
    <main>
        <div class="container-fluid px-4">
            <div class="flex-between">
                <div>
                    <h1 class="mt-4">Data Hasil</h1>
                    <ol class="breadcrumb mb-4">
                        <!-- <li class="breadcrumb-item active">Dataa</li> -->
                    </ol>
                </div>
                <div class="flex-column-auto mb-4 print-button">
                    <button class="btn" style="color: #2E5077" onclick="window.print();">
                        <i class="fa-solid fa-print"></i>
                    </button>
                </div>
            </div>
            <div id="tabel1" class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Hasil Perankingan
                </div>
                <div class="card-body">
                    <table class="datatabel">
                        <?php
                        // Ambil data kolom dinamis dari tabel alternatif
                        $queryColumns = "SHOW COLUMNS FROM alternatif";
                        $resultColumns = $db->query($queryColumns);

                        $columns = [];
                        while ($row = $resultColumns->fetch_assoc()) {
                            $columns[] = $row['Field'];
                        }

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

                        // Gabungkan data dari tabel `hasil` dan `alternatif`
                        $queryData = "
                        SELECT a.kode, a.nama, h.nilai, h.ranking, " . implode(", ", $dynamicColumns) . " 
                        FROM alternatif a
                        JOIN hasil h ON a.kode = h.kode
                        ORDER BY h.ranking ASC";
                        $resultData = $db->query($queryData);
                        ?>
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Nilai</th>
                                <th>Nama Lokasi</th>
                                <?php foreach ($dynamicColumns as $col): ?>
                                    <th>
                                        <?= htmlspecialchars($kriteriaNames[$col]); ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultData->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['ranking']); ?></td>
                                    <td><?= htmlspecialchars(number_format((float)$row['nilai'], 4)); ?></td>
                                    <td><?= htmlspecialchars($row['kode']); ?> - <?= htmlspecialchars($row['nama']); ?></td>
                                    <?php foreach ($dynamicColumns as $col): ?>
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
                                            <?= htmlspecialchars($row[$col]); ?>
                                            <?php if (!empty($satuan)): ?>
                                                <?= " " . htmlspecialchars($satuan); ?>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Akhir Isi -->

    <!-- Awal Footer -->
    <footer class="py-4 mt-auto" style="background-color: #2E5077 ;">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-center small">
                <div class="text-muted">Copyright &copy; SPK Lahan Padi 2024</div>
            </div>
        </div>
    </footer>
    <!-- Akhir Footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple.js"></script>

</body>

</html>
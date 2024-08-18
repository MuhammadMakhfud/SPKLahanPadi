<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SPK Lahan Padi</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/user.css" rel="stylesheet" />
    <link rel='short icon' href='assets/img/logo.png'>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script async data-id="five-server" src="http://localhost:5500/fiveserver.js"></script>
    <style>


    </style>
</head>

<body>
    <!-- Awal Navbar -->
    <nav class="navbar sticky-top navbar-dark bg-dark">
        <a class="navbar-brand no-hover" href="#">SPK Lahan Padi</a>
        <div class="navbar-nav">
            <!-- Navbar Links -->
            <a class="navbar-brand " href="index.php">Dashboard</a>
            <a class="navbar-brand active" href="data.php">Data</a>
        </div>

    </nav>
    <!-- Akhir Navbar -->

    <!-- Awal Isi -->
    <main>
        <div class="container-fluid px-4">
            <div class="flex-between">
                <div>
                    <h1 class="mt-4">DATA</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Data</li>
                    </ol>
                </div>
                <div class="flex-column-auto mb-4 print-button">
                    <button class="btn btn-dark" onclick="window.print();">
                        <i class="fa-solid fa-print"></i>
                    </button>
                </div>
            </div>
            <div id="tabel2" class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    DataTable 2
                </div>
                <div class="card-body">
                    <table id="datatablesSimple2">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Age</th>
                                <th>Start date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                            </tr>
                            <tr>
                                <td>Garrett Winters</td>
                                <td>Accountant</td>
                                <td>Tokyo</td>
                                <td>63</td>
                                <td>2011/07/25</td>
                            </tr>
                            <tr>
                                <td>Ashton Cox</td>
                                <td>Junior Technical Author</td>
                                <td>San Francisco</td>
                                <td>66</td>
                                <td>2009/01/12</td>
                            </tr>
                            <tr>
                                <td>Cedric Kelly</td>
                                <td>Senior Javascript Developer</td>
                                <td>Edinburgh</td>
                                <td>22</td>
                                <td>2012/03/29</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <!-- Akhir Isi -->

    <!-- Awal Footer -->
    <footer class="py-4 bg-dark mt-auto">
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
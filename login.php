<?php

include("kendali/koneksi.php"); // Pastikan koneksi.php sudah benar dan menghubungkan ke database

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Buat query SQL untuk memilih pengguna berdasarkan username dan password
    $sql = "SELECT * FROM pengguna WHERE username='$username' AND password='$password'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Simpan informasi pengguna di sesi
        session_start();
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];

        // Cek level pengguna dan arahkan sesuai
        if ($user['level'] == 1) {
            // Arahkan ke halaman admin jika level = 1
            header("Location: admin.php");
        } elseif ($user['level'] == 2) {
            // Arahkan ke halaman user biasa jika level = 2
            header("Location: index.php");
        }
        exit(); // Jangan lupa untuk menghentikan eksekusi setelah redirect
    } else {
        header("Location: login.php?status=error");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login Akun</title>

    <!-- Link CSS -->
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/login.css" rel="stylesheet" />

    <!-- Script -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js" defer></script>
    <script async data-id="five-server" src="http://localhost:5500/fiveserver.js"></script>

</head>

<body class="bg-primary">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header">
            <h3 class="text-center font-weight-light my-4">Login Akun</h3>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
                <div class="alert alert-danger alert-dismissible fade show " role="alert">
                    <font>Username atau Password Salah</font>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="form-floating mb-3">
                    <input class="form-control" id="inputusername" type="text" placeholder="Username" name="username" required />
                    <label for="inputusername">Nama Pengguna</label>
                </div>
                <div class="form-floating mb-3 position-relative">
                    <input class="form-control pe-5" id="inputPassword" type="password" placeholder="Masukkan password" name="password" required />
                    <label for="inputPassword">Kata Sandi</label>
                    <span class="position-absolute end-0 top-50 translate-middle-y me-3" id="togglePassword" style="cursor: pointer;">
                        <i class="fa fa-eye-slash" id="eyeIcon" style="opacity: 0.6;"></i>
                    </span>
                </div>
                <div class="d-flex align-items-center justify-content-center mt-4 mb-0">
                    <button class="btn btn-primary btn-lg" type="submit" name="login">Masuk</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
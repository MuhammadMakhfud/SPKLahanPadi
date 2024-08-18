<?php

include("kendali/koneksi.php");

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];


    $sql = "INSERT INTO pengguna (username, password, level, email) VALUES ('$username', '$password', '2', '$email') ";

    if ($db->query($sql)) {
        // Redirect setelah berhasil insert data
        header("Location: register.php?status=success");
        exit();
    } else {
        // Redirect setelah gagal insert data
        header("Location: register.php?status=error");
        exit();
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

    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <!-- JavaScript -->
    <script src="js/scripts.js" defer></script>
    <script async data-id="five-server" src="http://localhost:5500/fiveserver.js"></script>

</head>

<body class="bg-primary">



    <div class="card shadow-lg border-0 rounded-lg ">
        <div class="card-header">
            <h3 class="text-center font-weight-light my-4">Daftar Akun</h3>
        </div>
        <div class="card-body">

            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert alert-success " role="alert">
                    Pendaftaran berhasil! Anda akan dialihkan ke halaman login.
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 3000); // Redirect setelah 3 detik
                </script>
            <?php elseif (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
                <div class="alert alert-danger" role="alert">
                    Terjadi kesalahan saat mendaftarkan akun. Silakan coba lagi.
                </div>
            <?php endif; ?>

            <form action="register.php" method="post">
                <div class="form-floating mb-3">
                    <input class="form-control" id="inputName" type="text" placeholder="Masukkan Nama" name="username" required />
                    <label for="inputName">Nama</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" id="inputEmail" type="email" placeholder="nama@example.com" name="email" required />
                    <label for="inputEmail">Email</label>
                </div>
                <div class="form-floating mb-3 position-relative">
                    <input class="form-control pe-5" id="inputPassword" type="password" placeholder="Masukkan password" name="password" required />
                    <label for="inputPassword">Kata Sandi</label>
                    <span class="position-absolute end-0 top-50 translate-middle-y me-3" id="togglePassword" style="cursor: pointer;">
                        <i class="fa fa-eye-slash" id="eyeIcon" style="opacity: 0.6;"></i>
                    </span>
                </div>
                <div class="d-flex align-items-center justify-content-center mt-4 mb-0">
                    <button class="btn btn-primary btn-lg" type="submit" name="register">Daftar</button>
                </div>
            </form>


        </div>
    </div>
</body>

</html>
document.addEventListener('DOMContentLoaded', function () {
    // SideBar
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function (event) {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
        });
    }

    // Inisialisasi DataTables untuk semua tabel dengan kelas 'datatablesSimple'
    const tables = document.querySelectorAll('.datatabel');
    tables.forEach(function (table) {
        new simpleDatatables.DataTable(table);
    });

    // Hide/show Password
    const togglePassword = document.getElementById('togglePassword');
    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const passwordInput = document.getElementById('inputPassword');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });
    }

    //Fungsi untuk jika submit
    function handleFormSubmission(formSelector) {
        const forms = document.querySelectorAll(formSelector);
        forms.forEach(function (form) {
            form.addEventListener('submit', function () {
                const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
                if (modal) {
                    modal.hide();
                }
                window.location.reload();
            });
        });
    }

    // Penggunaan fungsi form
    handleFormSubmission('form[action="d_kriteria.php"]');
    handleFormSubmission('form[action="d_subkriteria.php"]');
    handleFormSubmission('form[action="d_alternatif.php"]');

    // Sembunyikan alert setelah 5 detik
    const alertMessage = document.getElementById('alert-modal');
    if (alertMessage) {
        setTimeout(function () {
            alertMessage.remove(); // Menghapus elemen dari DOM
        }, 5000); // 5000 milidetik = 5 detik
    }
});






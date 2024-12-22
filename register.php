<?php
// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mendapatkan nilai CAPTCHA yang dikirimkan dari form
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Secret Key Anda dari Google reCAPTCHA
    $secretKey = '6LcZfZ4qAAAAAB4VlsOrr3qY1Sjy-v4VmjUBGOUe'; // Ganti dengan Secret Key Anda
    
    // URL untuk verifikasi reCAPTCHA
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    
    // Kirim permintaan ke Google untuk memverifikasi reCAPTCHA
    $response = file_get_contents($url . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
    $responseKeys = json_decode($response, true);

    // Cek apakah CAPTCHA valid
    if (intval($responseKeys["success"]) !== 1) {
        $error_message = "Verifikasi CAPTCHA gagal, coba lagi!";
    } else {
        // CAPTCHA valid, lanjutkan proses registrasi
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validasi email dan password
        if ($password != $confirm_password) {
            $error_message = "Password dan konfirmasi password tidak cocok!";
        } elseif (empty($email) || empty($password)) {
            $error_message = "Email atau password tidak boleh kosong!";
        } else {
            // Enkripsi password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Simpan data registrasi ke database
            // Database connection
            $db = new mysqli("localhost", "root", "", "akademik"); // Ganti dengan kredensial database Anda
            if ($db->connect_error) {
                die("Connection failed: " . $db->connect_error);
            }

            // Periksa apakah email sudah ada di database
            $query = "SELECT * FROM user WHERE email = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = "Email sudah terdaftar!";
            } else {
                // Insert data ke database
                $stmt = $db->prepare("INSERT INTO user (email, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $hashed_password);

                if ($stmt->execute()) {
                    $success_message = "Registrasi berhasil!";
                    header("Location: index.php");
                } else {
                    $error_message = "Terjadi kesalahan, coba lagi!";
                }

                $stmt->close();
            }

            $db->close();
        }
    }
}
?>

<!-- Form Registrasi -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<div class="container mt-5">
    <h2>Form Registrasi</h2>
    <!-- Menampilkan pesan error atau sukses -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>

    <!-- Form untuk Registrasi -->
    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>

        <!-- reCAPTCHA Widget -->
        <div class="g-recaptcha" data-sitekey="6LcZfZ4qAAAAAN8-hXYG2Y5fc_mZNrJ7IabvFhaK"></div> <!-- Ganti dengan Site Key Anda -->
      
        <button type="submit" class="btn btn-primary mt-3">Registrasi</button>
    </form>
</div>

<!-- Skrip untuk memuat reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>
</html>

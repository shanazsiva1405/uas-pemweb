<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM dosen WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Dosen</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Detail Dosen</h2>
        <table class="table table-bordered">
            <tr>
                <th>Nama</th>
                <td><?php echo $data['nama']; ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?php echo $data['alamat']; ?></td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td><?php echo $data['tanggal_lahir']; ?></td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td><?php echo $data['jabatan']; ?></td>
            </tr>
            <tr>
                <th>Nomor Telepon</th>
                <td><?php echo $data['nomor_telepon']; ?></td>
            </tr>
            <tr>
                <th>Foto</th>
                <td><img src="assets/uploads/<?php echo $data['foto']; ?>" width="150"></td>
            </tr>
        </table>
        <a href="dashboard.php" class="btn btn-secondary btn-block">Kembali</a>
    </div>
</body>
</html>

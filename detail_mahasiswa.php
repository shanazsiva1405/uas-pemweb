<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM mahasiswa WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Mahasiswa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Detail Mahasiswa</h2>
        <table class="table table-bordered">
            <tr>
                <th>Nama</th>
                <td><?php echo $data['nama']; ?></td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td><?php echo $data['tanggal_lahir']; ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?php echo $data['alamat']; ?></td>
            </tr>
            <tr>
                <th>Nomor Telepon</th>
                <td><?php echo $data['nomor_telepon']; ?></td>
            </tr>
            <tr>
                <th>Jurusan</th>
                <td><?php echo $data['jurusan']; ?></td>
            </tr>
            <tr>
                <th>Prodi</th>
                <td><?php echo $data['prodi']; ?></td>
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

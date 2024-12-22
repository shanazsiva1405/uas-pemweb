<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Hapus data
$query = "DELETE FROM mahasiswa WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Gagal menghapus data: " . mysqli_error($conn);
}
?>

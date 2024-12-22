<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Ambil keyword pencarian
$search_mahasiswa = isset($_GET['search_mahasiswa']) ? $_GET['search_mahasiswa'] : '';
$search_dosen = isset($_GET['search_dosen']) ? $_GET['search_dosen'] : '';

// Pagination setup
$limit = 5; // Jumlah data per halaman
$page_mahasiswa = isset($_GET['page_mahasiswa']) ? (int)$_GET['page_mahasiswa'] : 1;
$page_dosen = isset($_GET['page_dosen']) ? (int)$_GET['page_dosen'] : 1;
$offset_mahasiswa = ($page_mahasiswa - 1) * $limit;
$offset_dosen = ($page_dosen - 1) * $limit;

// Query untuk mahasiswa
$query_mahasiswa = "SELECT * FROM mahasiswa WHERE nama LIKE '%$search_mahasiswa%' LIMIT $limit OFFSET $offset_mahasiswa";
$result_mahasiswa = mysqli_query($conn, $query_mahasiswa);

// Total mahasiswa
$total_mahasiswa_query = "SELECT COUNT(*) as total FROM mahasiswa WHERE nama LIKE '%$search_mahasiswa%'";
$total_mahasiswa_result = mysqli_fetch_assoc(mysqli_query($conn, $total_mahasiswa_query));
$total_mahasiswa = $total_mahasiswa_result['total'];
$total_pages_mahasiswa = ceil($total_mahasiswa / $limit);

// Query untuk dosen
$query_dosen = "SELECT * FROM dosen WHERE nama LIKE '%$search_dosen%' LIMIT $limit OFFSET $offset_dosen";
$result_dosen = mysqli_query($conn, $query_dosen);

// Total dosen
$total_dosen_query = "SELECT COUNT(*) as total FROM dosen WHERE nama LIKE '%$search_dosen%'";
$total_dosen_result = mysqli_fetch_assoc(mysqli_query($conn, $total_dosen_query));
$total_dosen = $total_dosen_result['total'];
$total_pages_dosen = ceil($total_dosen / $limit);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        nav .pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 20px 0;
    justify-content: center;
}

nav .pagination .page-item {
    margin: 0 5px;
}

nav .pagination .page-link {
    display: inline-block;
    padding: 10px 15px;
    text-decoration: none;
    color: #ff6fa5; /* Warna pink lembut */
    background-color: #fff0f5; /* Warna putih dengan sentuhan pink */
    border: 1px solid #ffb3d9; /* Warna pink lebih gelap untuk border */
    border-radius: 5px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background-color: #ffcce5; /* Warna pink lembut saat hover */
    color: #ffffff; /* Warna putih untuk teks */
    border-color: #ff80b3;
}

.pagination .active .page-link {
    background-color: #ff80b3; /* Warna pink lebih pekat */
    color: #ffffff; /* Warna putih untuk teks */
    border-color: #ff4d94;
}

    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Dashboard</h2>
        <a href="logout.php" class="btn btn-danger mt-3 mb-3">Logout</a>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="mahasiswa-tab" data-toggle="tab" href="#mahasiswa" role="tab">Data Mahasiswa</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="dosen-tab" data-toggle="tab" href="#dosen" role="tab">Data Dosen</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Tabel Mahasiswa -->
            <div class="tab-pane fade show active" id="mahasiswa" role="tabpanel">
                <a href="tambah_mahasiswa.php" class="btn btn-primary mt-3 mb-3">Tambah Mahasiswa</a>

                <div class="search-container">
                <form method="GET" class="form-inline mb-3">
                    <input style="width: 50%;
                                padding: 10px;
                                border: 2px solid #ffccd5; /* Pink lembut */
                                border-radius: 5px;
                                font-size: 16px;
                                outline: none;
                                transition: border-color 0.3s ease-in-out;
                    
                    " type="text" name="search_mahasiswa" class="form-control mr-2" placeholder="Cari Mahasiswa" value="<?= $search_mahasiswa ?>">
                    <button type="submit" class="btn btn-secondary">Cari</button>
                </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                
                                <th>Jurusan</th>
                                
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = $offset_mahasiswa + 1;
                            while ($row = mysqli_fetch_assoc($result_mahasiswa)) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . $row['nama'] . "</td>";
                              
                                echo "<td>" . $row['jurusan'] . "</td>";
                               
                                echo "<td><img src='assets/uploads/" . $row['foto'] . "' width='50'></td>";
                                echo '<td>
                                        <a href="edit_mahasiswa.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm mb-2">Edit</a>
                                        <a href="hapus_mahasiswa.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm mb-2" onclick="return confirm(\'Yakin ingin menghapus data?\');">Hapus</a>
                                        <a href="detail_mahasiswa.php?id=' . $row['id'] . '" class="btn btn-info btn-sm mb-2">Detail</a>
                                    </td>';
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Mahasiswa -->
                <nav>
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_pages_mahasiswa; $i++): ?>
                            <li class="page-item <?= $i == $page_mahasiswa ? 'active' : '' ?>">
                                <a class="page-link" href="?page_mahasiswa=<?= $i ?>&search_mahasiswa=<?= $search_mahasiswa ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>

            <!-- Tabel Dosen -->
            <div class="tab-pane fade" id="dosen" role="tabpanel">
                <a href="tambah_dosen.php" class="btn btn-primary mt-3 mb-3">Tambah Dosen</a>
                <div class="search-container">
                <form method="GET" class="form-inline mb-3">
                    <input style="width: 50%;
                                padding: 10px;
                                border: 2px solid #ffccd5; /* Pink lembut */
                                border-radius: 5px;
                                font-size: 16px;
                                outline: none;
                                transition: border-color 0.3s ease-in-out;
                    
                    " type="text" name="search_dosen" class="form-control mr-2" placeholder="Cari Dosen" value="<?= $search_dosen ?>">
                    <button type="submit" class="btn btn-secondary">Cari</button>
                </form>
                </div>
               

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                               
                                <th>Jabatan</th>
                               
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = $offset_dosen + 1;
                            while ($row = mysqli_fetch_assoc($result_dosen)) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . $row['nama'] . "</td>";
                               
                                echo "<td>" . $row['jabatan'] . "</td>";
                               
                                echo "<td><img src='assets/uploads/" . $row['foto'] . "' width='50'></td>";
                                echo '<td>
                                        <a href="edit_dosen.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm mb-2">Edit</a>
                                        <a href="hapus_dosen.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm mb-2" onclick="return confirm(\'Yakin ingin menghapus data?\');">Hapus</a>
                                        <a href="detail_dosen.php?id=' . $row['id'] . '" class="btn btn-info btn-sm mb-2">Detail</a>
                                    </td>';
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Dosen -->
                <nav>
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_pages_dosen; $i++): ?>
                            <li class="page-item <?= $i == $page_dosen ? 'active' : '' ?>">
                                <a class="page-link" href="?page_dosen=<?= $i ?>&search_dosen=<?= $search_dosen ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

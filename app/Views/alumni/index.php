<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">🎓 Alumni Tracking System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?controller=alumni&action=index">Alumni</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=tracking&action=index">Tracking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=alumni&action=statistics">Statistik</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col">
                <h2>📋 Daftar Alumni</h2>
            </div>
            <div class="col text-end">
                <a href="index.php?controller=alumni&action=create" class="btn btn-success">
                    ➕ Tambah Alumni
                </a>
                <a href="index.php?controller=alumni&action=export" class="btn btn-info">
                    📥 Export CSV
                </a>
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                if ($_GET['success'] == 'created') echo "Data alumni berhasil ditambahkan!";
                if ($_GET['success'] == 'updated') echo "Data alumni berhasil diupdate!";
                if ($_GET['success'] == 'deleted') echo "Data alumni berhasil dihapus!";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <table id="alumniTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Program Studi</th>
                            <th>Tahun Lulus</th>
                            <th>Email</th>
                            <th>Kota Asal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($alumni_list as $alumni): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($alumni['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($alumni['program_studi']) ?></td>
                            <td><?= htmlspecialchars($alumni['tahun_kelulusan']) ?></td>
                            <td><?= htmlspecialchars($alumni['email']) ?></td>
                            <td><?= htmlspecialchars($alumni['kota_asal']) ?></td>
                            <td>
                                <?php if ($alumni['status_data'] == 'Data Ditemukan'): ?>
                                    <span class="badge bg-success">Ditemukan</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Belum Ditemukan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?controller=alumni&action=detail&id=<?= $alumni['id'] ?>" 
                                   class="btn btn-sm btn-primary">👁️</a>
                                <a href="index.php?controller=alumni&action=edit&id=<?= $alumni['id'] ?>" 
                                   class="btn btn-sm btn-warning">✏️</a>
                                <a href="index.php?controller=tracking&action=track&id=<?= $alumni['id'] ?>" 
                                   class="btn btn-sm btn-info">🔍</a>
                                <a href="index.php?controller=alumni&action=history&id=<?= $alumni['id'] ?>" 
                                   class="btn btn-sm btn-secondary">📜</a>
                                <a href="index.php?controller=alumni&action=delete&id=<?= $alumni['id'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Yakin ingin menghapus?')">🗑️</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#alumniTable').DataTable();
        });
    </script>
</body>
</html>
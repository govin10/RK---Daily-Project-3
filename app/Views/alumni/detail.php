<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Alumni - <?= htmlspecialchars($alumni['nama_lengkap']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">🎓 Alumni Tracking System</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=alumni&action=index">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                if ($_GET['success'] == 'updated') echo "Data alumni berhasil diperbarui!";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <!-- Profile Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-graduate"></i> Profil Alumni</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="display-1 text-primary">
                                <i class="fas fa-user-circle"></i>
                            </div>
                        </div>
                        <h4><?= htmlspecialchars($alumni['nama_lengkap']) ?></h4>
                        <p class="text-muted">
                            <i class="fas fa-graduation-cap"></i> <?= htmlspecialchars($alumni['program_studi']) ?><br>
                            <i class="fas fa-calendar"></i> Angkatan <?= htmlspecialchars($alumni['tahun_kelulusan']) ?>
                        </p>
                        
                        <div class="d-grid gap-2">
                            <a href="index.php?controller=tracking&action=track&id=<?= $alumni['id'] ?>" 
                               class="btn btn-primary">
                                <i class="fas fa-search"></i> Lacak Alumni
                            </a>
                            <a href="index.php?controller=alumni&action=edit&id=<?= $alumni['id'] ?>" 
                               class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Data
                            </a>
                            <a href="index.php?controller=alumni&action=history&id=<?= $alumni['id'] ?>" 
                               class="btn btn-info">
                                <i class="fas fa-history"></i> Riwayat Tracking
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="card mb-4">
                    <div class="card-header <?= $alumni['status_data'] == 'Data Ditemukan' ? 'bg-success' : 'bg-warning' ?> text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Status</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($alumni['status_data'] == 'Data Ditemukan'): ?>
                            <div class="text-center text-success">
                                <i class="fas fa-check-circle fa-3x mb-2"></i>
                                <h5>Data Ditemukan</h5>
                                <p>Terakhir diupdate: <?= date('d/m/Y H:i', strtotime($alumni['updated_at'])) ?></p>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-warning">
                                <i class="fas fa-hourglass-half fa-3x mb-2"></i>
                                <h5>Belum Ditemukan</h5>
                                <p>Lakukan tracking untuk mencari data</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Informasi Kontak -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-address-card"></i> Informasi Kontak</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong><i class="fas fa-envelope"></i> Email:</strong></td>
                                <td><?= htmlspecialchars($alumni['email']) ?: '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-city"></i> Kota Asal:</strong></td>
                                <td><?= htmlspecialchars($alumni['kota_asal']) ?: '-' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Informasi Pekerjaan (Jika sudah ditemukan) -->
                <?php if ($alumni['status_data'] == 'Data Ditemukan'): ?>
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-briefcase"></i> Informasi Pekerjaan</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Pekerjaan:</strong></td>
                                <td><?= htmlspecialchars($alumni['pekerjaan_sekarang']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Perusahaan:</strong></td>
                                <td><?= htmlspecialchars($alumni['perusahaan']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Lokasi Kerja:</strong></td>
                                <td><?= htmlspecialchars($alumni['lokasi_kerja']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Bidang:</strong></td>
                                <td><?= htmlspecialchars($alumni['bidang_aktivitas']) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Hasil Tracking Terbaru -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-clock"></i> Hasil Tracking Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($tracking_history)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Belum ada riwayat tracking untuk alumni ini.
                                <hr>
                                <a href="index.php?controller=tracking&action=track&id=<?= $alumni['id'] ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i> Mulai Tracking Sekarang
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="timeline">
                                <?php foreach ($tracking_history as $history): ?>
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>
                                                    <i class="fas fa-<?= 
                                                        $history['sumber_platform'] == 'LinkedIn' ? 'linkedin' : 
                                                        ($history['sumber_platform'] == 'GitHub' ? 'github' : 
                                                        ($history['sumber_platform'] == 'Google Scholar' ? 'graduation-cap' : 'share-alt')) 
                                                    ?>"></i>
                                                    <?= htmlspecialchars($history['sumber_platform']) ?>
                                                </strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock"></i> 
                                                    <?= date('d/m/Y H:i', strtotime($history['waktu_pencarian'])) ?>
                                                </small>
                                            </div>
                                            <div>
                                                <span class="badge bg-<?= 
                                                    $history['tingkat_kepercayaan'] == 'Tinggi' ? 'success' : 
                                                    ($history['tingkat_kepercayaan'] == 'Sedang' ? 'warning' : 'secondary') 
                                                ?>">
                                                    <?= $history['tingkat_kepercayaan'] ?>
                                                </span>
                                            </div>
                                        </div>
                                        <p class="mt-2 mb-0">
                                            <?= htmlspecialchars($history['ringkasan_informasi']) ?>
                                        </p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .timeline {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 5px;
    }
    .timeline::-webkit-scrollbar {
        width: 5px;
    }
    .timeline::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .timeline::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Tracking - <?= htmlspecialchars($alumni['nama_lengkap']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .score-high { color: #28a745; font-weight: bold; }
        .score-medium { color: #ffc107; font-weight: bold; }
        .score-low { color: #dc3545; font-weight: bold; }
        .progress { height: 10px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <h2>Hasil Pelacakan: <?= htmlspecialchars($alumni['nama_lengkap']) ?></h2>
                <p class="text-muted">Data alumni dari database kampus</p>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"><strong>Program Studi:</strong></div>
                            <div class="col-md-9"><?= htmlspecialchars($alumni['program_studi']) ?></div>
                            
                            <div class="col-md-3"><strong>Tahun Kelulusan:</strong></div>
                            <div class="col-md-9"><?= htmlspecialchars($alumni['tahun_kelulusan']) ?></div>
                            
                            <div class="col-md-3"><strong>Email:</strong></div>
                            <div class="col-md-9"><?= htmlspecialchars($alumni['email']) ?></div>
                            
                            <div class="col-md-3"><strong>Kota Asal:</strong></div>
                            <div class="col-md-9"><?= htmlspecialchars($alumni['kota_asal']) ?></div>
                        </div>
                    </div>
                </div>

                <h4 class="mt-4">Hasil Pencarian di Berbagai Platform</h4>
                
                <?php foreach ($tracking_results as $result): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>Profil Ditemukan</h5>
                                <p>
                                    <strong>Platform:</strong> <?= htmlspecialchars($this->getPlatformName($result['info']['platform_id'])) ?><br>
                                    <strong>Nama Profil:</strong> <?= htmlspecialchars($result['info']['nama_profil']) ?><br>
                                    <strong>Institusi:</strong> <?= htmlspecialchars($result['info']['institusi']) ?><br>
                                    <strong>Jabatan:</strong> <?= htmlspecialchars($result['info']['jabatan']) ?><br>
                                    <strong>Lokasi:</strong> <?= htmlspecialchars($result['info']['lokasi']) ?><br>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <h6>Skor Kecocokan: <?= round($result['match_score'] * 100) ?>%</h6>
                                <div class="progress mb-2">
                                    <div class="progress-bar <?= $result['kategori'] == 'cocok_tinggi' ? 'bg-success' : ($result['kategori'] == 'cocok_sedang' ? 'bg-warning' : 'bg-danger') ?>" 
                                         style="width: <?= $result['match_score'] * 100 ?>%"></div>
                                </div>
                                
                                <h6 class="mt-3">Parameter Kecocokan:</h6>
                                <ul class="list-unstyled">
                                    <li>Nama: <?= round($result['parameters']['nama'] * 100) ?>%</li>
                                    <li>Bidang: <?= round($result['parameters']['bidang'] * 100) ?>%</li>
                                    <li>Lokasi: <?= round($result['parameters']['lokasi'] * 100) ?>%</li>
                                    <li>Institusi: <?= round($result['parameters']['institusi'] * 100) ?>%</li>
                                </ul>
                                
                                <span class="badge <?= $result['kategori'] == 'cocok_tinggi' ? 'bg-success' : ($result['kategori'] == 'cocok_sedang' ? 'bg-warning' : 'bg-secondary') ?>">
                                    <?= str_replace('_', ' ', $result['kategori']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <h4 class="mt-4">Data Alumni Terbaru (Setelah Tracking)</h4>
                <?php
                $updated_alumni = (new App\Models\AlumniModel())->getAlumniById($alumni['id']);
                ?>
                <div class="card">
                    <div class="card-body">
                        <?php if ($updated_alumni['status_data'] == 'Data Ditemukan'): ?>
                            <div class="alert alert-success">
                                <strong>Status:</strong> Data Alumni Berhasil Diperbarui!
                            </div>
                            <p>
                                <strong>Pekerjaan Sekarang:</strong> <?= htmlspecialchars($updated_alumni['pekerjaan_sekarang']) ?><br>
                                <strong>Perusahaan:</strong> <?= htmlspecialchars($updated_alumni['perusahaan']) ?><br>
                                <strong>Lokasi Kerja:</strong> <?= htmlspecialchars($updated_alumni['lokasi_kerja']) ?><br>
                                <strong>Bidang Aktivitas:</strong> <?= htmlspecialchars($updated_alumni['bidang_aktivitas']) ?>
                            </p>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                Belum ditemukan data baru yang cocok untuk alumni ini.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="index.php?controller=tracking&action=index" class="btn btn-secondary">
                        ← Kembali ke Daftar Alumni
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
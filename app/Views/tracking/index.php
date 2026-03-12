<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Tracking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-track { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; }
        .btn-track:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">🎓 Alumni Tracking System</span>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col">
                <h2>Daftar Alumni</h2>
                <p class="text-muted">Pilih alumni untuk melakukan pelacakan digital</p>
            </div>
        </div>

        <div class="row">
            <?php foreach ($alumni_list as $alumni): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($alumni['nama_lengkap']) ?></h5>
                        <p class="card-text">
                            <strong>Program Studi:</strong> <?= htmlspecialchars($alumni['program_studi']) ?><br>
                            <strong>Tahun Lulus:</strong> <?= htmlspecialchars($alumni['tahun_kelulusan']) ?><br>
                            <strong>Kota Asal:</strong> <?= htmlspecialchars($alumni['kota_asal']) ?><br>
                            <span class="badge <?= $alumni['status_data'] == 'Data Ditemukan' ? 'bg-success' : 'bg-warning' ?>">
                                <?= htmlspecialchars($alumni['status_data']) ?>
                            </span>
                        </p>
                        <a href="index.php?controller=tracking&action=track&id=<?= $alumni['id'] ?>" 
                           class="btn btn-track w-100">
                            🔍 Lacak Alumni
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
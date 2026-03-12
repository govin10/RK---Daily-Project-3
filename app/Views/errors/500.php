<?php
$page_title = "Kesalahan Server";
ob_start();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-template">
                <h1 class="display-1 text-danger">500</h1>
                <h2 class="display-4">Internal Server Error</h2>
                <div class="error-details my-4">
                    <p class="lead">Maaf, terjadi kesalahan pada server.</p>
                    <p class="text-muted">Tim teknis kami sedang menangani masalah ini.</p>
                    <p class="text-muted">Silakan coba lagi beberapa saat.</p>
                </div>
                <div class="error-actions">
                    <a href="index.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> Kembali ke Dashboard
                    </a>
                    <button onclick="location.reload()" class="btn btn-warning btn-lg">
                        <i class="fas fa-sync"></i> Refresh Halaman
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-template {
    padding: 40px 15px;
    animation: shake 0.5s ease-in-out;
}
.display-1 {
    font-size: 10rem;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
    20%, 40%, 60%, 80% { transform: translateX(10px); }
}
</style>

<?php
$content = ob_get_clean();
include '../app/Views/layout/template.php';
?>
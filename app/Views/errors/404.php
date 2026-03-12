<?php
$page_title = "Halaman Tidak Ditemukan";
ob_start();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-template">
                <h1 class="display-1 text-primary">404</h1>
                <h2 class="display-4">Oops! Halaman Tidak Ditemukan</h2>
                <div class="error-details my-4">
                    <p class="lead">Maaf, halaman yang Anda cari tidak dapat ditemukan.</p>
                    <p class="text-muted">Mungkin URL salah atau halaman telah dipindahkan.</p>
                </div>
                <div class="error-actions">
                    <a href="index.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> Kembali ke Dashboard
                    </a>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-template {
    padding: 40px 15px;
    animation: fadeIn 1s ease-in;
}
.display-1 {
    font-size: 10rem;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<?php
$content = ob_get_clean();
include '../app/Views/layout/template.php';
?>
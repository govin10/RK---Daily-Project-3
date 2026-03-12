<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?>Alumni Tracking System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --dark-color: #2b2d42;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .btn {
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .footer {
            background: linear-gradient(135deg, var(--dark-color), #1a1e2b);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }
        
        .footer a {
            color: var(--success-color);
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        .loading-spinner {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
        
        .loading-spinner.show {
            display: block;
        }
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .card:hover {
                transform: none;
            }
        }
    </style>
    
    <?php if (isset($custom_css)): ?>
        <?php foreach ($custom_css as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap"></i> Alumni Tracking System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['SCRIPT_NAME']) == 'index.php' && !isset($_GET['controller']) ? 'active' : '' ?>" 
                           href="index.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'alumni' ? 'active' : '' ?>" 
                           href="index.php?controller=alumni&action=index">
                            <i class="fas fa-users"></i> Alumni
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] == 'tracking' ? 'active' : '' ?>" 
                           href="index.php?controller=tracking&action=index">
                            <i class="fas fa-search"></i> Tracking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=home&action=statistics">
                            <i class="fas fa-chart-bar"></i> Statistik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=home&action=help">
                            <i class="fas fa-question-circle"></i> Bantuan
                        </a>
                    </li>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex" action="index.php" method="GET">
                    <input type="hidden" name="controller" value="home">
                    <input type="hidden" name="action" value="search">
                    <input class="form-control me-2" type="search" name="keyword" placeholder="Cari alumni..." 
                           value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button class="btn btn-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <!-- User Menu -->
                <ul class="navbar-nav ms-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <?php if (isset($breadcrumb) && !empty($breadcrumb)): ?>
    <nav aria-label="breadcrumb" class="container mt-3">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumb as $key => $item): ?>
                <?php if ($key < count($breadcrumb) - 1): ?>
                    <li class="breadcrumb-item"><a href="<?= $item['url'] ?>"><?= $item['title'] ?></a></li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= $item['title'] ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="container my-4">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-graduation-cap"></i> Alumni Tracking System</h5>
                    <p>Sistem pelacakan alumni digital untuk memantau perkembangan karir lulusan.</p>
                </div>
                <div class="col-md-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li><a href="index.php?controller=alumni&action=index"><i class="fas fa-users"></i> Alumni</a></li>
                        <li><a href="index.php?controller=tracking&action=index"><i class="fas fa-search"></i> Tracking</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope"></i> support@alumni-tracking.com</li>
                        <li><i class="fas fa-phone"></i> (021) 1234-5678</li>
                        <li><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <small>&copy; 2026 Alumni Tracking System. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Show loading spinner
        function showLoading() {
            document.getElementById('loadingSpinner').classList.add('show');
        }
        
        // Hide loading spinner
        function hideLoading() {
            document.getElementById('loadingSpinner').classList.remove('show');
        }
        
        // Show toast notification
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white bg-${type}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
            toast.show();
            
            // Remove toast after hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        }
        
        // Handle AJAX requests
        $(document).ajaxStart(function() {
            showLoading();
        });
        
        $(document).ajaxStop(function() {
            hideLoading();
        });
        
        $(document).ajaxError(function(event, jqxhr, settings, error) {
            showToast('Terjadi kesalahan: ' + error, 'danger');
        });
        
        // Confirm delete
        function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
            return confirm(message);
        }
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            });
        }, 5000);
        
        // Back to top button
        const backToTop = document.createElement('button');
        backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
        backToTop.setAttribute('class', 'btn btn-primary back-to-top');
        backToTop.setAttribute('style', 'position: fixed; bottom: 20px; right: 20px; display: none; border-radius: 50%; width: 50px; height: 50px;');
        document.body.appendChild(backToTop);
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
    
    <?php if (isset($custom_js)): ?>
        <?php foreach ($custom_js as $js): ?>
            <script src="<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
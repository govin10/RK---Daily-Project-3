<?php
namespace App\Controllers;

use App\Models\AlumniModel;
use App\Models\TrackingModel;

class AlumniController {
    private $alumniModel;
    private $trackingModel;

    public function __construct() {
        $this->alumniModel = new AlumniModel();
        $this->trackingModel = new TrackingModel();
    }

    // Menampilkan daftar semua alumni
    public function index() {
        $alumni_list = $this->alumniModel->getAlumniData();
        include '../app/Views/alumni/index.php';
    }

    // Menampilkan detail alumni
    public function detail($id) {
        $alumni = $this->alumniModel->getAlumniById($id);
        $tracking_history = $this->trackingModel->getTrackingHistory($id);
        
        if (!$alumni) {
            header("Location: index.php?controller=alumni&action=index");
            exit();
        }
        
        include '../app/Views/alumni/detail.php';
    }

    // Menampilkan form tambah alumni
    public function create() {
        include '../app/Views/alumni/create.php';
    }

    // Menyimpan data alumni baru
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = (new \App\Config\Database())->getConnection();
            
            $query = "INSERT INTO alumni (nama_lengkap, program_studi, tahun_kelulusan, email, kota_asal) 
                      VALUES (:nama_lengkap, :program_studi, :tahun_kelulusan, :email, :kota_asal)";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":nama_lengkap", $_POST['nama_lengkap']);
            $stmt->bindParam(":program_studi", $_POST['program_studi']);
            $stmt->bindParam(":tahun_kelulusan", $_POST['tahun_kelulusan']);
            $stmt->bindParam(":email", $_POST['email']);
            $stmt->bindParam(":kota_asal", $_POST['kota_asal']);
            
            if ($stmt->execute()) {
                $alumni_id = $conn->lastInsertId();
                // Buat pola identitas untuk alumni baru
                $this->alumniModel->createIdentityPatterns($alumni_id);
                
                header("Location: index.php?controller=alumni&action=index&success=created");
            } else {
                header("Location: index.php?controller=alumni&action=create&error=failed");
            }
        }
    }

    // Menampilkan form edit alumni
    public function edit($id) {
        $alumni = $this->alumniModel->getAlumniById($id);
        
        if (!$alumni) {
            header("Location: index.php?controller=alumni&action=index");
            exit();
        }
        
        include '../app/Views/alumni/edit.php';
    }

    // Mengupdate data alumni
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = (new \App\Config\Database())->getConnection();
            
            $query = "UPDATE alumni SET 
                      nama_lengkap = :nama_lengkap,
                      program_studi = :program_studi,
                      tahun_kelulusan = :tahun_kelulusan,
                      email = :email,
                      kota_asal = :kota_asal
                      WHERE id = :id";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":nama_lengkap", $_POST['nama_lengkap']);
            $stmt->bindParam(":program_studi", $_POST['program_studi']);
            $stmt->bindParam(":tahun_kelulusan", $_POST['tahun_kelulusan']);
            $stmt->bindParam(":email", $_POST['email']);
            $stmt->bindParam(":kota_asal", $_POST['kota_asal']);
            $stmt->bindParam(":id", $id);
            
            if ($stmt->execute()) {
                header("Location: index.php?controller=alumni&action=detail&id=$id&success=updated");
            } else {
                header("Location: index.php?controller=alumni&action=edit&id=$id&error=failed");
            }
        }
    }

    // Menghapus data alumni
    public function delete($id) {
        $conn = (new \App\Config\Database())->getConnection();
        
        $query = "DELETE FROM alumni WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        if ($stmt->execute()) {
            header("Location: index.php?controller=alumni&action=index&success=deleted");
        } else {
            header("Location: index.php?controller=alumni&action=detail&id=$id&error=delete_failed");
        }
    }

    // Melihat riwayat tracking alumni
    public function history($id) {
        $alumni = $this->alumniModel->getAlumniById($id);
        $tracking_history = $this->trackingModel->getTrackingHistory($id);
        
        if (!$alumni) {
            header("Location: index.php?controller=alumni&action=index");
            exit();
        }
        
        include '../app/Views/alumni/history.php';
    }

    // Ekspor data alumni ke CSV
    public function export() {
        $alumni_list = $this->alumniModel->getAlumniData();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="alumni_data_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nama Lengkap', 'Program Studi', 'Tahun Kelulusan', 'Email', 'Kota Asal', 'Pekerjaan', 'Perusahaan', 'Status']);
        
        foreach ($alumni_list as $alumni) {
            fputcsv($output, [
                $alumni['id'],
                $alumni['nama_lengkap'],
                $alumni['program_studi'],
                $alumni['tahun_kelulusan'],
                $alumni['email'],
                $alumni['kota_asal'],
                $alumni['pekerjaan_sekarang'],
                $alumni['perusahaan'],
                $alumni['status_data']
            ]);
        }
        
        fclose($output);
        exit();
    }

    // Statistik alumni
    public function statistics() {
        $conn = (new \App\Config\Database())->getConnection();
        
        // Total alumni
        $query_total = "SELECT COUNT(*) as total FROM alumni";
        $stmt_total = $conn->prepare($query_total);
        $stmt_total->execute();
        $total = $stmt_total->fetch(\PDO::FETCH_ASSOC);
        
        // Alumni per program studi
        $query_prodi = "SELECT program_studi, COUNT(*) as jumlah FROM alumni GROUP BY program_studi";
        $stmt_prodi = $conn->prepare($query_prodi);
        $stmt_prodi->execute();
        $per_prodi = $stmt_prodi->fetchAll(\PDO::FETCH_ASSOC);
        
        // Alumni per tahun kelulusan
        $query_tahun = "SELECT tahun_kelulusan, COUNT(*) as jumlah FROM alumni GROUP BY tahun_kelulusan ORDER BY tahun_kelulusan";
        $stmt_tahun = $conn->prepare($query_tahun);
        $stmt_tahun->execute();
        $per_tahun = $stmt_tahun->fetchAll(\PDO::FETCH_ASSOC);
        
        // Status tracking
        $query_status = "SELECT status_data, COUNT(*) as jumlah FROM alumni GROUP BY status_data";
        $stmt_status = $conn->prepare($query_status);
        $stmt_status->execute();
        $status = $stmt_status->fetchAll(\PDO::FETCH_ASSOC);
        
        include '../app/Views/alumni/statistics.php';
    }
}
?>
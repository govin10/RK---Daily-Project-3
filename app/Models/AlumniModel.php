<?php
namespace App\Models;

use App\Config\Database;

class AlumniModel {
    private $conn;
    private $table = "alumni";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Sistem Mengambil dan Menyiapkan Data Alumni (Step 1)
    public function getAlumniData() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAlumniById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Sistem Membentuk Pola Identitas Alumni (Step 2)
    public function createIdentityPatterns($alumni_id) {
        $alumni = $this->getAlumniById($alumni_id);
        if (!$alumni) return false;

        $patterns = [];
        
        // nama lengkap
        $patterns[] = ['alumni_id' => $alumni_id, 'pola_nama' => $alumni['nama_lengkap'], 'tipe_pola' => 'nama_lengkap'];
        
        // inisial
        $names = explode(' ', $alumni['nama_lengkap']);
        $inisial = '';
        foreach ($names as $name) {
            $inisial .= substr($name, 0, 1);
        }
        $patterns[] = ['alumni_id' => $alumni_id, 'pola_nama' => $inisial, 'tipe_pola' => 'inisial'];
        
        // nama depan + nama belakang
        if (count($names) >= 2) {
            $nama_depan_belakang = $names[0] . ' ' . end($names);
            $patterns[] = ['alumni_id' => $alumni_id, 'pola_nama' => $nama_depan_belakang, 'tipe_pola' => 'nama_depan_belakang'];
        }
        
        // kombinasi nama dengan prodi
        $patterns[] = ['alumni_id' => $alumni_id, 'pola_nama' => $alumni['nama_lengkap'] . ' ' . $alumni['program_studi'], 'tipe_pola' => 'nama_dengan_prodi'];
        
        // kombinasi nama dengan kota
        if ($alumni['kota_asal']) {
            $patterns[] = ['alumni_id' => $alumni_id, 'pola_nama' => $alumni['nama_lengkap'] . ' ' . $alumni['kota_asal'], 'tipe_pola' => 'nama_dengan_kota'];
        }

        // Insert patterns to database
        foreach ($patterns as $pattern) {
            $query = "INSERT INTO identity_patterns (alumni_id, pola_nama, tipe_pola) VALUES (:alumni_id, :pola_nama, :tipe_pola)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($pattern);
        }

        return true;
    }

    // Sistem Memperbarui Data Alumni (Step 9)
    public function updateAlumniData($id, $data) {
        $query = "UPDATE " . $this->table . 
                " SET pekerjaan_sekarang = :pekerjaan_sekarang, 
                    perusahaan = :perusahaan, 
                    lokasi_kerja = :lokasi_kerja,
                    bidang_aktivitas = :bidang_aktivitas,
                    status_data = 'Data Ditemukan'
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pekerjaan_sekarang", $data['pekerjaan_sekarang']);
        $stmt->bindParam(":perusahaan", $data['perusahaan']);
        $stmt->bindParam(":lokasi_kerja", $data['lokasi_kerja']);
        $stmt->bindParam(":bidang_aktivitas", $data['bidang_aktivitas']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
}
?>
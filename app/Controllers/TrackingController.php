<?php
namespace App\Controllers;

use App\Models\AlumniModel;
use App\Models\TrackingModel;
use App\Models\PlatformModel;

class TrackingController {
    private $alumniModel;
    private $trackingModel;

    public function __construct() {
        $this->alumniModel = new AlumniModel();
        $this->trackingModel = new TrackingModel();
    }

    public function index() {
        $alumni_list = $this->alumniModel->getAlumniData();
        include '../app/Views/tracking/index.php';
    }

    public function track($alumni_id) {
        // Step 1: Ambil data alumni
        $alumni = $this->alumniModel->getAlumniById($alumni_id);
        
        // Step 2: Buat pola identitas
        $this->alumniModel->createIdentityPatterns($alumni_id);
        
        // Ambil pola dari database
        $patterns = $this->getPatterns($alumni_id);
        
        // Step 3 & 4: Cari di berbagai platform
        $search_results = $this->trackingModel->searchProfiles($alumni_id, $patterns);
        
        // Step 5: Ekstrak informasi
        $extracted_info = $this->trackingModel->extractProfileInfo($search_results);
        
        $tracking_results = [];
        
        // Step 6, 7, 8: Analisis dan klasifikasi
        foreach ($extracted_info as $info) {
            $match_analysis = $this->trackingModel->analyzeMatch($info, $alumni);
            $kategori = $this->trackingModel->classifyResult($match_analysis);
            
            // Simpan hasil tracking
            $result_id = $this->saveTrackingResult($alumni_id, $info, $match_analysis, $kategori);
            
            // Step 9: Update data alumni jika cocok tinggi
            if ($kategori == 'cocok_tinggi') {
                $update_data = [
                    'pekerjaan_sekarang' => $info['jabatan'],
                    'perusahaan' => $info['institusi'],
                    'lokasi_kerja' => $info['lokasi'],
                    'bidang_aktivitas' => 'Teknologi Informasi'
                ];
                $this->alumniModel->updateAlumniData($alumni_id, $update_data);
            }
            
            // Step 10: Simpan riwayat
            $history_data = [
                'platform_id' => $info['platform_id'],
                'sumber_platform' => $this->getPlatformName($info['platform_id']),
                'tautan_profil' => '#',
                'ringkasan_informasi' => "Ditemukan profil dengan jabatan {$info['jabatan']} di {$info['institusi']}",
                'tingkat_kepercayaan' => $this->getConfidenceLevel($match_analysis['total_score'])
            ];
            $this->trackingModel->saveTrackingHistory($alumni_id, $history_data);
            
            $tracking_results[] = [
                'info' => $info,
                'match_score' => $match_analysis['total_score'],
                'parameters' => $match_analysis['parameters'],
                'kategori' => $kategori
            ];
        }
        
        // Gabungkan data dari multiple platform
        $merged_data = $this->trackingModel->mergePlatformData($alumni_id);
        
        include '../app/Views/tracking/results.php';
    }

    private function getPatterns($alumni_id) {
        $conn = (new \App\Config\Database())->getConnection();
        $query = "SELECT * FROM identity_patterns WHERE alumni_id = :alumni_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":alumni_id", $alumni_id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function saveTrackingResult($alumni_id, $info, $match_analysis, $kategori) {
        $conn = (new \App\Config\Database())->getConnection();
        $query = "INSERT INTO tracking_results 
                 (alumni_id, platform_id, profile_url, profile_username, display_name, institusi, jabatan, lokasi, deskripsi, aktivitas_publik, skor_kecocokan, kategori, tingkat_kepercayaan) 
                 VALUES 
                 (:alumni_id, :platform_id, :profile_url, :profile_username, :display_name, :institusi, :jabatan, :lokasi, :deskripsi, :aktivitas_publik, :skor_kecocokan, :kategori, :tingkat_kepercayaan)";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":alumni_id", $alumni_id);
        $stmt->bindParam(":platform_id", $info['platform_id']);
        $stmt->bindParam(":profile_url", $info['nama_pengguna']);
        $stmt->bindParam(":profile_username", $info['nama_pengguna']);
        $stmt->bindParam(":display_name", $info['nama_profil']);
        $stmt->bindParam(":institusi", $info['institusi']);
        $stmt->bindParam(":jabatan", $info['jabatan']);
        $stmt->bindParam(":lokasi", $info['lokasi']);
        $stmt->bindParam(":deskripsi", $info['deskripsi']);
        $stmt->bindParam(":aktivitas_publik", $info['aktivitas']);
        $stmt->bindParam(":skor_kecocokan", $match_analysis['total_score']);
        $stmt->bindParam(":kategori", $kategori);
        $confidence = $this->getConfidenceLevel($match_analysis['total_score']);
        $stmt->bindParam(":tingkat_kepercayaan", $confidence);
        
        $stmt->execute();
        return $conn->lastInsertId();
    }

    private function getPlatformName($platform_id) {
        $conn = (new \App\Config\Database())->getConnection();
        $query = "SELECT nama_platform FROM platforms WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $platform_id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $result['nama_platform'] : 'Unknown';
    }

    private function getConfidenceLevel($score) {
        if ($score >= 0.7) return 'Tinggi';
        if ($score >= 0.4) return 'Sedang';
        return 'Rendah';
    }
}
?>
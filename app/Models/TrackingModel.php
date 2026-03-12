<?php
namespace App\Models;

use App\Config\Database;

class TrackingModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Sistem Menentukan Platform Sumber Data (Step 3)
    public function getPlatforms($tipe_alumni = null) {
        $query = "SELECT * FROM platforms WHERE is_active = true";
        if ($tipe_alumni) {
            if ($tipe_alumni == 'akademik') {
                $query .= " AND tipe_platform IN ('Akademik', 'Profesional')";
            } else {
                $query .= " AND tipe_platform IN ('Profesional', 'Sosial', 'Developer')";
            }
        }
        $query .= " ORDER BY prioritas ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Sistem Melakukan Proses Pencarian Profil (Step 4)
    public function searchProfiles($alumni_id, $patterns) {
        $alumni = (new AlumniModel())->getAlumniById($alumni_id);
        $platforms = $this->getPlatforms();
        $results = [];

        foreach ($platforms as $platform) {
            foreach ($patterns as $pattern) {
                // pencarian di setiap platform
                $mock_result = $this->simulatePlatformSearch($platform, $pattern, $alumni);
                if ($mock_result) {
                    $results[] = $mock_result;
                }
            }
        }

        return $results;
    }

    // pencarian di platform 
    private function simulatePlatformSearch($platform, $pattern, $alumni) {
        
        $random_score = rand(60, 100) / 100;
        
        return [
            'platform_id' => $platform['id'],
            'alumni_id' => $alumni['id'],
            'profile_url' => $platform['api_url'] . urlencode($pattern['pola_nama']),
            'profile_username' => strtolower(str_replace(' ', '', $pattern['pola_nama'])),
            'display_name' => $alumni['nama_lengkap'],
            'institusi' => 'Sample University/Company',
            'jabatan' => 'Software Engineer',
            'lokasi' => $alumni['kota_asal'] . ', Indonesia',
            'deskripsi' => 'Sample profile description',
            'aktivitas_publik' => 'Sample public activities',
            'skor_kecocokan' => $random_score
        ];
    }

    // Sistem Mengumpulkan Informasi dari Profil Kandidat (Step 5)
    public function extractProfileInfo($search_results) {
        $extracted_data = [];
        
        foreach ($search_results as $result) {
            // Ekstrak informasi penting dari hasil pencarian
            $info = [
                'platform_id' => $result['platform_id'],
                'nama_pengguna' => $result['profile_username'],
                'nama_profil' => $result['display_name'],
                'institusi' => $result['institusi'],
                'jabatan' => $result['jabatan'],
                'lokasi' => $result['lokasi'],
                'deskripsi' => $result['deskripsi'],
                'aktivitas' => $result['aktivitas_publik']
            ];
            
            $extracted_data[] = $info;
        }
        
        return $extracted_data;
    }

    // Sistem Melakukan Analisis Kecocokan Data (Step 6)
    public function analyzeMatch($profile_info, $alumni_data) {
        $match_score = 0;
        $parameters = [];

        // Kesamaan nama
        $nama_similarity = $this->calculateSimilarity($profile_info['nama_profil'], $alumni_data['nama_lengkap']);
        $parameters['nama'] = $nama_similarity;
        $match_score += $nama_similarity * 0.3; // Bobot 30%

        // Kesamaan bidang studi
        $bidang_similarity = rand(60, 100) / 100;
        $parameters['bidang'] = $bidang_similarity;
        $match_score += $bidang_similarity * 0.3; // Bobot 30%

        // Kesamaan lokasi
        $lokasi_similarity = $this->calculateLocationMatch($profile_info['lokasi'], $alumni_data['kota_asal']);
        $parameters['lokasi'] = $lokasi_similarity;
        $match_score += $lokasi_similarity * 0.2; // Bobot 20%

        // Kesamaan institusi
        $institusi_similarity = rand(50, 90) / 100;
        $parameters['institusi'] = $institusi_similarity;
        $match_score += $institusi_similarity * 0.2; // Bobot 20%

        return [
            'total_score' => $match_score,
            'parameters' => $parameters
        ];
    }

    // Sistem Mengklasifikasikan Hasil Pencarian (Step 7)
    public function classifyResult($match_analysis) {
        $score = $match_analysis['total_score'];
        
        if ($score >= 0.7) {
            return 'cocok_tinggi';
        } elseif ($score >= 0.4) {
            return 'cocok_sedang';
        } else {
            return 'tidak_cocok';
        }
    }

    // Sistem Menggabungkan Informasi dari Berbagai Platform (Step 8)
    public function mergePlatformData($alumni_id) {
        $query = "SELECT * FROM tracking_results WHERE alumni_id = :alumni_id AND kategori = 'cocok_tinggi'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":alumni_id", $alumni_id);
        $stmt->execute();
        
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        if (count($results) > 1) {
            // Jika ditemukan di multiple platform, tingkatkan kepercayaan
            $this->increaseConfidence($alumni_id);
        }
        
        return $results;
    }

    private function increaseConfidence($alumni_id) {
        $query = "UPDATE tracking_results SET tingkat_kepercayaan = 'Tinggi' WHERE alumni_id = :alumni_id AND kategori = 'cocok_tinggi'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":alumni_id", $alumni_id);
        $stmt->execute();
    }

    // Sistem Menyimpan Riwayat Pelacakan (Step 10)
    public function saveTrackingHistory($alumni_id, $tracking_data) {
        $query = "INSERT INTO tracking_history 
                 (alumni_id, platform_id, sumber_platform, tautan_profil, ringkasan_informasi, tingkat_kepercayaan) 
                 VALUES 
                 (:alumni_id, :platform_id, :sumber_platform, :tautan_profil, :ringkasan_informasi, :tingkat_kepercayaan)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":alumni_id", $alumni_id);
        $stmt->bindParam(":platform_id", $tracking_data['platform_id']);
        $stmt->bindParam(":sumber_platform", $tracking_data['sumber_platform']);
        $stmt->bindParam(":tautan_profil", $tracking_data['tautan_profil']);
        $stmt->bindParam(":ringkasan_informasi", $tracking_data['ringkasan_informasi']);
        $stmt->bindParam(":tingkat_kepercayaan", $tracking_data['tingkat_kepercayaan']);
        
        return $stmt->execute();
    }

    public function getTrackingHistory($alumni_id) {
        $query = "SELECT th.*, p.nama_platform 
                 FROM tracking_history th 
                 JOIN platforms p ON th.platform_id = p.id 
                 WHERE th.alumni_id = :alumni_id 
                 ORDER BY th.waktu_pencarian DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":alumni_id", $alumni_id);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Helper functions
    private function calculateSimilarity($str1, $str2) {
        similar_text(strtolower($str1), strtolower($str2), $percent);
        return $percent / 100;
    }

    private function calculateLocationMatch($profile_loc, $alumni_city) {
        if (strpos(strtolower($profile_loc), strtolower($alumni_city)) !== false) {
            return 1.0;
        }
        return 0.3;
    }
}
?>
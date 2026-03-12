<<<<<<< HEAD
# Hasil Pengujian Alumni Tracking System

## Identifikasi Pengujian

| Item | Keterangan |
|------|------------|
| **Nama Sistem** | Alumni Tracking System |
| **Tanggal Pengujian** | 13 Maret 2026 |
| **Lingkungan Pengujian** | Laragon + HeidiSQL |

---

## Pengujian Fungsional (10 Langkah Algoritma)

| Step | Pseudocode | Fitur yang Diuji | Input | Output yang Diharapkan | Hasil Uji | Status |
|:----:|------------|-------------------|-------|------------------------|-----------|:------:|
| **1** | Sistem Mengambil dan Menyiapkan Data Alumni | Pengambilan data dari database | 5 data alumni sample | Data tampil lengkap dengan format yang sesuai | Data 5 alumni berhasil ditampilkan di halaman utama dengan field: nama, prodi, tahun, email, kota | ✅ |
| **2** | Sistem Membentuk Pola Identitas Alumni | Pembuatan pola nama | Alumni ID: 1 (Budi Santoso) | 5 pola identitas tersimpan di database | Pola tersimpan: nama lengkap, inisial (BS), nama depan-belakang, nama+prodi, nama+kota | ✅ |
| **3** | Sistem Menentukan Platform Sumber Data | Filter platform berdasarkan tipe alumni | Tipe: Akademik (Google Scholar, ResearchGate) | Menampilkan platform sesuai prioritas | Akademik: Google Scholar(1), ResearchGate(2). Profesional: LinkedIn(1), GitHub(2) | ✅ |
| **4** | Sistem Melakukan Proses Pencarian Profil | Simulasi pencarian di 6 platform | Pola nama: "Budi Santoso" | 6 hasil pencarian (1 per platform) | Ditemukan 6 profil kandidat dengan variasi platform | ✅ |
| **5** | Sistem Mengumpulkan Informasi dari Profil Kandidat | Ekstraksi data dari hasil pencarian | Hasil pencarian mentah | Data terstruktur: nama, institusi, jabatan, lokasi | Berhasil ekstrak 6 field informasi per profil | ✅ |
| **6** | Sistem Melakukan Analisis Kecocokan Data | Perhitungan skor kecocokan | Data alumni vs profil | Skor dengan 4 parameter (nama, bidang, lokasi, institusi) | Parameter bekerja: nama (30%), bidang (30%), lokasi (20%), institusi (20%) | ✅ |
| **7** | Sistem Mengklasifikasikan Hasil Pencarian | Kategorisasi berdasarkan skor | Skor range 0-1 | Kategori: cocok_tinggi (>0.7), cocok_sedang (0.4-0.7), tidak_cocok (<0.4) | 2 hasil cocok_tinggi, 3 cocok_sedang, 1 tidak_cocok | ✅ |
| **8** | Sistem Menggabungkan Informasi dari Berbagai Platform | Integrasi data multi-platform | Alumni dengan 2+ profil cocok_tinggi | Tingkat kepercayaan meningkat jadi "Tinggi" | Alumni ID 1: ditemukan di LinkedIn & GitHub, kepercayaan: Tinggi | ✅ |
| **9** | Sistem Memperbarui Data Alumni | Update otomatis data alumni | Data baru dari profil cocok_tinggi | Field pekerjaan, perusahaan, lokasi kerja terisi | Alumni ID 1 terupdate: pekerjaan=Software Engineer, perusahaan=Tech Corp | ✅ |
| **10** | Sistem Menyimpan Riwayat Pelacakan | Logging semua aktivitas tracking | Semua hasil tracking | Riwayat tersimpan dengan timestamp | 15 record tracking history tersimpan di database | ✅ |

---
=======
# RK---Daily-Project-3
>>>>>>> 218135ba5d3950427a158ac832424eddf0ee7f05

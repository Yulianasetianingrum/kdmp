# KDMP — Sistem Survei Lapangan & Master Data Desa

Aplikasi Laravel 12 yang difokuskan khusus untuk **Modul Survei Lapangan Desa** dan **Master Data & Konfigurasi Entitas**.

## Fitur Utama

- **Modul Survei Lapangan**:
  - **Sesi Survei**: Pembuatan dan manajemen sesi survei per desa/wilayah, tahun, dan penugasan petugas dengan token akses link publik.
  - **Survei RT (Ketua RT / Baseline KK)**: Pengumpulan data baseline kependudukan dari tingkat RT.
  - **Survei Produsen Desa**: Pendataan kelompok tani dan pelaku ekonomi kreatif (ekraf) desa beserta komoditas dan kapasitas produksinya.
  - **Survei Konsumsi Masyarakat**: Multi-step form interaktif untuk mendata profil keluarga, anggota keluarga, pola konsumsi pangan harian/bulanan (dilengkapi Web Speech API), dan validasi akhir.
  - **Validasi Survei**: Perbandingan data baseline vs data riil hasil survei lapangan untuk perangkat desa.
  - **Bank Pertanyaan**: Pengelolaan pertanyaan survei.

- **Modul Master Data**:
  - Manajemen Entitas / Koperasi Desa (Super Admin)
  - Komoditas
  - Satuan Ukur
  - Master Barang & Pengaturan Stok Minimum
  - Pihak / Mitra
  - Gudang
  - Kas & Bank
  - Periode Akuntansi & Chart of Accounts (COA)

## Cara Menjalankan

1. Pastikan server web dan database MySQL telah berjalan (misal: via XAMPP).
2. Jalankan server Laravel:
   ```bash
   php artisan serve --port=8000
   ```
3. Akses melalui browser:
   `http://127.0.0.1:8000`

### Akun Login
- **Super Administrator**: `superadmin@ngobar.id` / `password`
- **Manajer Desa**: `manajer@kdmp-a.test` / `password`

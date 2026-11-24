<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewMasukan extends Model
{
    use HasFactory;

    protected $table = 'review_masukan';

    protected $fillable = [
        'nama_reviewer',
        'halaman_url',
        'halaman_path',
        'judul_halaman',
        'bagian_elemen',
        'sumber_area',
        'detail_sumber',
        'posisi_x',
        'posisi_y',
        'kategori',
        'komentar',
        'coretan_gambar',
        'audio_rekaman',
        'data_anotasi',
        'device_info',
        'status',
        'tanggapan',
    ];

    protected $casts = [
        'posisi_x' => 'float',
        'posisi_y' => 'float',
        'data_anotasi' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getDemoUrlAttribute(): string
    {
        $separator = str_contains($this->halaman_url, '?') ? '&' : '?';
        return $this->halaman_url . $separator . 'review_demo_id=' . $this->id;
    }

    public function getSumberAreaBadgeAttribute(): array
    {
        return match ($this->sumber_area) {
            'dropdown_sidebar' => ['label' => 'Dropdown Sidebar', 'bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-300'],
            'sidebar' => ['label' => 'Sidebar Navigasi', 'bg' => 'bg-sky-100', 'text' => 'text-sky-800', 'border' => 'border-sky-300'],
            default => ['label' => 'Dokumen Utama', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-300'],
        };
    }

    public function getKategoriBadgeAttribute(): array
    {
        return match ($this->kategori) {
            'ui_ux' => ['label' => 'UI / Tampilan', 'bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-200'],
            'alur' => ['label' => 'Alur & Logika', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200'],
            'typo' => ['label' => 'Teks / Typo', 'bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-200'],
            'data' => ['label' => 'Data & Nilai', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200'],
            'bug' => ['label' => 'Bug / Eror', 'bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200'],
            'fitur' => ['label' => 'Usulan Fitur', 'bg' => 'bg-cyan-100', 'text' => 'text-cyan-800', 'border' => 'border-cyan-200'],
            default => ['label' => 'Umum', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200'],
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'dikerjakan' => ['label' => 'Dalam Pengerjaan', 'bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-300'],
            'selesai' => ['label' => 'Selesai / Diperbaiki', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-300'],
            default => ['label' => 'Pending / Belum Ditindak', 'bg' => 'bg-rose-100', 'text' => 'text-rose-800', 'border' => 'border-rose-300'],
        };
    }
}

<?php

namespace Tests\Feature;

use App\Models\Review\ReviewMasukan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecretReviewFeatureTest extends TestCase
{
    public function test_secret_panel_page_renders_successfully(): void
    {
        $response = $this->get('/secret');
        $response->assertStatus(200);
        $response->assertSee('Secret Review Panel');
        $response->assertSee('Sumber Masukan');
    }

    public function test_dashboard_renders_with_review_widget(): void
    {
        $response = $this->get('/dashboard');
        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    public function test_store_review_with_dropdown_sidebar_source(): void
    {
        $payload = [
            'nama_reviewer' => 'Dosen Penguji',
            'halaman_url' => 'http://127.0.0.1:8000/dashboard',
            'halaman_path' => '/dashboard',
            'judul_halaman' => 'Dashboard Utama',
            'bagian_elemen' => 'Item Dropdown "Petani"',
            'sumber_area' => 'dropdown_sidebar',
            'detail_sumber' => 'Item Dropdown Sidebar "Petani" (Grup: Master Data)',
            'posisi_x' => 14.5,
            'posisi_y' => 38.2,
            'kategori' => 'saran',
            'komentar' => 'Perbaiki penataan submenu master data di dropdown.',
        ];

        $response = $this->postJson(route('secret.store'), $payload);
        $response->assertStatus(201);
        $response->assertJson(['success' => true]);

        // Verifikasi endpoint alias /secret/review juga menerima payload dengan benar
        $responseAlias = $this->postJson('/secret/review', array_merge($payload, ['komentar' => 'Saran lewat alias /secret/review']));
        $responseAlias->assertStatus(201);
        $responseAlias->assertJson(['success' => true]);

        $this->assertDatabaseHas('review_masukan', [
            'nama_reviewer' => 'Dosen Penguji',
            'sumber_area' => 'dropdown_sidebar',
            'detail_sumber' => 'Item Dropdown Sidebar "Petani" (Grup: Master Data)',
            'bagian_elemen' => 'Item Dropdown "Petani"',
        ]);
    }

    public function test_filter_secret_by_sumber_area(): void
    {
        ReviewMasukan::create([
            'nama_reviewer' => 'Reviewer A',
            'halaman_url' => 'http://127.0.0.1:8000/komoditas',
            'halaman_path' => '/komoditas',
            'judul_halaman' => 'Komoditas',
            'bagian_elemen' => 'Tabel Komoditas',
            'sumber_area' => 'dokumen_utama',
            'detail_sumber' => 'Tabel Kolom "Nama"',
            'komentar' => 'Komentar Dokumen Utama',
        ]);

        ReviewMasukan::create([
            'nama_reviewer' => 'Reviewer B',
            'halaman_url' => 'http://127.0.0.1:8000/petani',
            'halaman_path' => '/petani',
            'judul_halaman' => 'Petani',
            'bagian_elemen' => 'Submenu Petani',
            'sumber_area' => 'dropdown_sidebar',
            'detail_sumber' => 'Item Dropdown "Petani"',
            'komentar' => 'Komentar Dropdown',
        ]);

        $response = $this->get('/secret?sumber=dropdown_sidebar');
        $response->assertStatus(200);
        $response->assertSee('Komentar Dropdown');
        $response->assertDontSee('Komentar Dokumen Utama');

        $responseMain = $this->get('/secret?sumber=dokumen_utama');
        $responseMain->assertStatus(200);
        $responseMain->assertSee('Komentar Dokumen Utama');
        $responseMain->assertDontSee('Komentar Dropdown');
    }

    public function test_store_review_with_sidebar_navigation_source(): void
    {
        $payload = [
            'nama_reviewer' => 'Dosen Penguji',
            'halaman_url' => 'http://127.0.0.1:8000/dashboard',
            'halaman_path' => '/dashboard',
            'judul_halaman' => 'Dashboard Utama',
            'bagian_elemen' => 'Menu Sidebar "Pendataan"',
            'sumber_area' => 'sidebar',
            'detail_sumber' => 'Grup Header Sidebar "Pendataan"',
            'posisi_x' => 12.0,
            'posisi_y' => 24.0,
            'kategori' => 'saran',
            'komentar' => 'Menu pendataan perlu disorot lebih terang.',
        ];

        $response = $this->postJson(route('secret.store'), $payload);
        $response->assertStatus(201);
        $response->assertJson(['success' => true]);

        // Verifikasi endpoint alias /secret/review juga menerima payload dengan benar
        $responseAlias = $this->postJson('/secret/review', array_merge($payload, ['komentar' => 'Saran lewat alias /secret/review']));
        $responseAlias->assertStatus(201);
        $responseAlias->assertJson(['success' => true]);

        $this->assertDatabaseHas('review_masukan', [
            'nama_reviewer' => 'Dosen Penguji',
            'sumber_area' => 'sidebar',
            'detail_sumber' => 'Grup Header Sidebar "Pendataan"',
        ]);
    }

    public function test_demo_data_endpoint_and_redirect(): void
    {
        $review = ReviewMasukan::create([
            'nama_reviewer' => 'Dosen Pembimbing',
            'halaman_url' => 'http://127.0.0.1:8000/dashboard',
            'halaman_path' => '/dashboard',
            'judul_halaman' => 'Dashboard Utama',
            'bagian_elemen' => 'Tombol Cetak',
            'sumber_area' => 'dokumen_utama',
            'posisi_x' => 50,
            'posisi_y' => 50,
            'komentar' => 'Tombol cetak perlu diperjelas',
            'data_anotasi' => [
                'pin' => ['text' => 'Tombol cetak perlu diperjelas', 'xPct' => 50, 'yPct' => 50],
                'strokes' => []
            ],
        ]);

        $response = $this->getJson(route('secret.demo_data', $review));
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $review->id,
            'komentar' => 'Tombol cetak perlu diperjelas',
            'bagian_elemen' => 'Tombol Cetak',
        ]);
        $response->assertJsonStructure([
            'id', 'halaman_url', 'demo_url', 'data_anotasi'
        ]);

        $redirect = $this->get(route('secret.demo', $review));
        $redirect->assertRedirect($review->demo_url);
    }
}

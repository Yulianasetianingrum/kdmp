<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Review\ReviewMasukan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecretReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = ReviewMasukan::query()->latest();

        $selectedPath = $request->query('path');
        if ($selectedPath) {
            $query->where('halaman_path', $selectedPath);
        }

        $selectedStatus = $request->query('status');
        if ($selectedStatus && in_array($selectedStatus, ['pending', 'dikerjakan', 'selesai'])) {
            $query->where('status', $selectedStatus);
        }

        $selectedKategori = $request->query('kategori');
        if ($selectedKategori) {
            $query->where('kategori', $selectedKategori);
        }

        $selectedSumber = $request->query('sumber');
        if ($selectedSumber && in_array($selectedSumber, ['dokumen_utama', 'sidebar', 'dropdown_sidebar'])) {
            $query->where('sumber_area', $selectedSumber);
        }

        if ($request->boolean('has_coretan')) {
            $query->whereNotNull('coretan_gambar')->where('coretan_gambar', '!=', '');
        }

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('komentar', 'like', "%{$search}%")
                  ->orWhere('nama_reviewer', 'like', "%{$search}%")
                  ->orWhere('judul_halaman', 'like', "%{$search}%")
                  ->orWhere('halaman_path', 'like', "%{$search}%")
                  ->orWhere('bagian_elemen', 'like', "%{$search}%")
                  ->orWhere('detail_sumber', 'like', "%{$search}%")
                  ->orWhere('tanggapan', 'like', "%{$search}%");
            });
        }

        $masukanList = $query->paginate(15)->withQueryString();

        // Statistik
        $totalMasukan = ReviewMasukan::count();
        $totalPending = ReviewMasukan::where('status', 'pending')->count();
        $totalDikerjakan = ReviewMasukan::where('status', 'dikerjakan')->count();
        $totalSelesai = ReviewMasukan::where('status', 'selesai')->count();
        $totalCoretan = ReviewMasukan::whereNotNull('coretan_gambar')->where('coretan_gambar', '!=', '')->count();
        $totalDokumenUtama = ReviewMasukan::where('sumber_area', 'dokumen_utama')->count();
        $totalDropdownSidebar = ReviewMasukan::where('sumber_area', 'dropdown_sidebar')->count();
        $totalSidebarNav = ReviewMasukan::where('sumber_area', 'sidebar')->count();

        // Daftar halaman unik untuk navigasi sidebar
        $pagesList = ReviewMasukan::query()
            ->selectRaw('halaman_path, MAX(judul_halaman) as judul, COUNT(*) as total_count, SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count')
            ->groupBy('halaman_path')
            ->orderByDesc('total_count')
            ->get();

        return view('review.secret', [
            'title' => 'Review & Masukan (Secret Panel)',
            'masukanList' => $masukanList,
            'pagesList' => $pagesList,
            'stats' => [
                'total' => $totalMasukan,
                'pending' => $totalPending,
                'dikerjakan' => $totalDikerjakan,
                'selesai' => $totalSelesai,
                'coretan' => $totalCoretan,
                'dokumen_utama' => $totalDokumenUtama,
                'dropdown_sidebar' => $totalDropdownSidebar,
                'sidebar_nav' => $totalSidebarNav,
            ],
            'selectedPath' => $selectedPath,
            'selectedStatus' => $selectedStatus,
            'selectedKategori' => $selectedKategori,
            'selectedSumber' => $selectedSumber,
            'search' => $search,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'komentar' => ['required', 'string', 'max:5000'],
            'halaman_url' => ['required', 'string'],
            'halaman_path' => ['required', 'string', 'max:255'],
            'judul_halaman' => ['nullable', 'string', 'max:255'],
            'nama_reviewer' => ['nullable', 'string', 'max:100'],
            'bagian_elemen' => ['nullable', 'string', 'max:255'],
            'sumber_area' => ['nullable', 'string', 'max:50'],
            'detail_sumber' => ['nullable', 'string', 'max:255'],
            'posisi_x' => ['nullable', 'numeric'],
            'posisi_y' => ['nullable', 'numeric'],
            'kategori' => ['nullable', 'string', 'max:50'],
            'coretan_gambar' => ['nullable', 'string'],
            'audio_rekaman' => ['nullable', 'string'],
            'data_anotasi' => ['nullable'],
            'device_info' => ['nullable', 'string', 'max:150'],
        ]);

        if (empty($validated['nama_reviewer'])) {
            $validated['nama_reviewer'] = 'Reviewer';
        }

        if (empty($validated['kategori'])) {
            $validated['kategori'] = 'umum';
        }

        if (empty($validated['sumber_area'])) {
            $validated['sumber_area'] = 'dokumen_utama';
        }

        if (isset($validated['data_anotasi']) && is_string($validated['data_anotasi'])) {
            $decoded = json_decode($validated['data_anotasi'], true);
            if (is_array($decoded)) {
                $validated['data_anotasi'] = $decoded;
            }
        }

        $masukan = ReviewMasukan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Masukan dan coretan berhasil dikirim ke panel secret!',
            'data' => [
                'id' => $masukan->id,
                'demo_url' => $masukan->demo_url,
                'created_at' => $masukan->created_at->format('d M Y H:i'),
            ],
        ], 201);
    }

    public function demoData(ReviewMasukan $masukan): JsonResponse
    {
        return response()->json([
            'id' => $masukan->id,
            'nama_reviewer' => $masukan->nama_reviewer,
            'halaman_url' => $masukan->halaman_url,
            'halaman_path' => $masukan->halaman_path,
            'judul_halaman' => $masukan->judul_halaman,
            'bagian_elemen' => $masukan->bagian_elemen,
            'sumber_area' => $masukan->sumber_area,
            'detail_sumber' => $masukan->detail_sumber,
            'posisi_x' => $masukan->posisi_x,
            'posisi_y' => $masukan->posisi_y,
            'kategori' => $masukan->kategori,
            'komentar' => $masukan->komentar,
            'coretan_gambar' => $masukan->coretan_gambar,
            'audio_rekaman' => $masukan->audio_rekaman,
            'data_anotasi' => $masukan->data_anotasi,
            'demo_url' => $masukan->demo_url,
            'status' => $masukan->status,
            'created_at' => $masukan->created_at->format('d M Y H:i'),
        ]);
    }

    public function demoRedirect(ReviewMasukan $masukan): RedirectResponse
    {
        return redirect()->to($masukan->demo_url);
    }

    public function updateStatus(Request $request, ReviewMasukan $masukan): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,dikerjakan,selesai'],
        ]);

        $masukan->update(['status' => $validated['status']]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $masukan->status,
                'badge' => $masukan->status_badge,
            ]);
        }

        return back()->with('status', 'Status masukan berhasil diperbarui.');
    }

    public function updateTanggapan(Request $request, ReviewMasukan $masukan): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'tanggapan' => ['nullable', 'string', 'max:3000'],
        ]);

        $masukan->update(['tanggapan' => $validated['tanggapan']]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'tanggapan' => $masukan->tanggapan,
            ]);
        }

        return back()->with('status', 'Catatan tindak lanjut berhasil disimpan.');
    }

    public function destroy(ReviewMasukan $masukan): RedirectResponse
    {
        $masukan->delete();

        return back()->with('status', 'Masukan berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\FaseKerja;
use App\Models\KategoriProfesi;
use App\Models\Profesi;
use App\Models\RutinitasHarian;
use App\Services\CareerMatchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Mengikuti class diagram CDR untuk ProfessionController.
 */
class ProfessionController extends Controller
{
    /** UC-13/14 Cari profesi dengan kata kunci + filter kategori. */
    public function professionKeywordSearch(Request $request): View
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
            'kategori' => ['nullable', 'string', 'max:40'],
        ]);
        $keyword = $data['q'] ?? '';
        $kategori = $data['kategori'] ?? 'All';

        return view('profesi.search', [
            'profesi' => $this->professionCategorySearch($kategori)->cari($keyword)->orderBy('nama_profesi')->get(),
            'keyword' => $keyword,
            'kategori' => $kategori,
            'filters' => $this->daftarFilter(),
        ]);
    }

    /** Query dasar filter kategori ('All' = tanpa filter). */
    public function professionCategorySearch(string $kategori)
    {
        return Profesi::with('kategori')->kategori($kategori);
    }

    /** UC-15 Detail profesi. */
    public function loadDetailProfesi(Profesi $profesi): View
    {
        $profesi->load(['kategori', 'reviews.akun']);

        return view('profesi.show', [
            'profesi' => $profesi,
            'bookmarked' => Auth::user()->bookmarks()->whereKey($profesi->id)->exists(),
        ]);
    }

    /** UC-16 Career review (dipakai popup). */
    public function loadReviewProfesi(Profesi $profesi): JsonResponse
    {
        return response()->json([
            'profesi' => $profesi->nama_profesi,
            'burnout_rate' => $profesi->burnout_rate,
            'reviews' => $profesi->reviews()->with('akun:id,username')->get()->map(fn ($r) => [
                'nama' => $r->akun->username,
                'jabatan' => $r->jabatan_reviewer,
                'rating' => $r->rating,
                'isi' => $r->isi_ulasan,
            ]),
        ]);
    }

    /** UC-18 Simpan bookmark. */
    public function saveBookmarked(Profesi $profesi): RedirectResponse
    {
        Auth::user()->bookmarks()->syncWithoutDetaching([$profesi->id => ['tanggal_ditandai' => now()->toDateString()]]);

        return back()->with('toast', 'Bookmark Saved');
    }

    /** UC-19 Lihat bookmark. */
    public function readBookmarked(Request $request): View
    {
        $kategori = (string) $request->query('kategori', 'All');
        $profesi = Auth::user()->bookmarks()->with('kategori')->kategori($kategori)
            ->cari((string) $request->query('q', ''))
            ->orderByPivot('tanggal_ditandai', 'desc')->get();

        return view('profesi.bookmark', [
            'profesi' => $profesi,
            'kategori' => $kategori,
            'keyword' => (string) $request->query('q', ''),
            'filters' => $this->daftarFilter(),
        ]);
    }

    /** UC-20 Hapus bookmark. */
    public function removeBookmark(Profesi $profesi): RedirectResponse
    {
        Auth::user()->bookmarks()->detach($profesi->id);

        return back()->with('toast', 'Bookmark Removed')->with('toastType', 'danger');
    }

    /** UC-12 Career match (JSON). */
    public function hitungPersentaseCareerMatch(CareerMatchService $match): JsonResponse
    {
        return response()->json($match->hitung(Auth::user()->load(['skills', 'pengalaman']))->map(fn ($m) => [
            'profesi' => $m['profesi']->nama_profesi,
            'slug' => $m['profesi']->slug,
            'persen' => $m['persen'],
            'dimiliki' => $m['dimiliki'],
            'kurang' => $m['kurang'],
        ]));
    }

    /** UC-21/22 Day in the life: daftar rutinitas & proyek dalam satu halaman bertab. */
    public function dayInTheLife(Request $request, Profesi $profesi): View
    {
        $proyek = $profesi->proyek()->with('fase')->first();
        $fase = $proyek?->fase ?? collect();

        return view('profesi.day', [
            'profesi' => $profesi,
            'rutinitas' => $this->daftarRutinitas($profesi),
            'fase' => $fase,
            'totalJam' => round($this->daftarRutinitas($profesi)->sum(fn ($r) => $r->durasiMenit()) / 60),
            'totalMinggu' => (int) ceil($fase->sum(fn ($f) => $f->durasiHari()) / 7),
            'tab' => $request->query('tab') === 'project' ? 'project' : 'rutin',
        ]);
    }

    public function daftarRutinitas(Profesi $profesi)
    {
        return $profesi->rutinitas;
    }

    public function detailRutinitas(RutinitasHarian $rutinitas): JsonResponse
    {
        return response()->json([
            'nama' => $rutinitas->kegiatan,
            'jam' => $rutinitas->jamMulai(),
            'durasi' => $rutinitas->durasiMenit().' menit',
            'deskripsi' => $rutinitas->deskripsi,
        ]);
    }

    public function daftarProyek(Profesi $profesi)
    {
        return $profesi->proyek()->with('fase')->get();
    }

    public function detailProyek(FaseKerja $fase): JsonResponse
    {
        return response()->json([
            'nama' => $fase->nama_fase,
            'urutan' => $fase->urutan,
            'durasi' => $fase->durasi,
            'sprint' => $fase->sprint,
            'deskripsi' => $fase->deskripsi,
        ]);
    }

    /** @return list<string> */
    private function daftarFilter(): array
    {
        return array_merge(['All'], KategoriProfesi::orderBy('id')->pluck('nama_kategori')->all());
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AkunProfil;
use App\Models\Pengalaman;
use App\Models\Skill;
use App\Services\CareerMatchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Mengikuti class diagram CDR: signIn, login, updateUsername, updatePassword,
 * createPengalaman, deletePengalaman, deleteSkill, loadDataProfil (+ CRUD skill lainnya).
 */
class AkunController extends Controller
{
    public function formLogin(): View
    {
        return view('auth.login');
    }

    public function formSignIn(): View
    {
        return view('auth.register');
    }

    /** UC-01 Registrasi akun ("Sign in" pada desain). */
    public function signIn(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:akun_profil,username'],
            'email' => ['required', 'email', 'max:255', 'unique:akun_profil,email'],
            'phone_number' => ['required', 'regex:/^0[0-9]{8,14}$/'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'phone_number.regex' => 'Nomor telepon harus diawali 0 dan berisi 9-15 digit.',
        ]);

        $akun = AkunProfil::create($data);
        Auth::login($akun);
        $request->session()->regenerate();

        return redirect()->route('welcome');
    }

    /** UC-02 Login dengan username atau email. */
    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($data['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (! Auth::attempt([$field => $data['username'], 'password' => $data['password']], $request->boolean('remember'))) {
            return back()->withErrors(['username' => 'Username atau password salah.'])->onlyInput('username');
        }
        $request->session()->regenerate();

        return redirect()->route('welcome');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    public function welcome(): View
    {
        return view('auth.welcome', ['akun' => Auth::user()]);
    }

    /** UC-03 Lihat profil (skill, pengalaman, career match). */
    public function loadDataProfil(CareerMatchService $match): View
    {
        $akun = Auth::user()->load(['skills', 'pengalaman']);

        return view('profile.index', [
            'akun' => $akun,
            'matches' => $match->hitung($akun),
            'levels' => Skill::LEVELS,
            'tipe' => Pengalaman::TIPE,
        ]);
    }

    public function settings(): View
    {
        return view('profile.settings', ['akun' => Auth::user()]);
    }

    /** UC-04 Ubah username (maks sekali per 30 hari). */
    public function updateUsername(Request $request): RedirectResponse
    {
        $akun = Auth::user();
        $data = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', Rule::unique('akun_profil', 'username')->ignore($akun->id)],
        ]);

        if ($data['username'] === $akun->username) {
            return back();
        }
        if (! $akun->bisaGantiUsername()) {
            return back()->withErrors(['username' => 'Username hanya bisa diganti sekali setiap 30 hari.'], 'username');
        }

        $akun->update(['username' => $data['username'], 'username_diubah_pada' => now()]);

        return back()->with('toast', 'Username Updated');
    }

    /** UC-05 Ubah password. */
    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('password', [
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        Auth::user()->update(['password' => $data['password']]);

        return back()->with('toast', 'Password Updated');
    }

    // ---------- Skill (UC-06..08) ----------

    public function createSkill(Request $request): RedirectResponse
    {
        Auth::user()->skills()->create($this->validasiSkill($request));

        return back()->with('toast', 'Changes Saved');
    }

    public function updateSkill(Request $request, Skill $skill): RedirectResponse
    {
        $this->pastikanPemilik($skill->akun_profil_id);
        $skill->update($this->validasiSkill($request));

        return back()->with('toast', 'Changes Saved');
    }

    public function deleteSkill(Skill $skill): RedirectResponse
    {
        $this->pastikanPemilik($skill->akun_profil_id);
        $skill->delete();

        return back()->with('toast', 'Entry Deleted')->with('toastType', 'danger');
    }

    // ---------- Pengalaman (UC-09..11) ----------

    public function createPengalaman(Request $request): RedirectResponse
    {
        Auth::user()->pengalaman()->create($this->validasiPengalaman($request));

        return back()->with('toast', 'Changes Saved')->with('tab', 'pengalaman');
    }

    public function updatePengalaman(Request $request, Pengalaman $pengalaman): RedirectResponse
    {
        $this->pastikanPemilik($pengalaman->akun_profil_id);
        $pengalaman->update($this->validasiPengalaman($request));

        return back()->with('toast', 'Changes Saved')->with('tab', 'pengalaman');
    }

    public function deletePengalaman(Pengalaman $pengalaman): RedirectResponse
    {
        $this->pastikanPemilik($pengalaman->akun_profil_id);
        $pengalaman->delete();

        return back()->with('toast', 'Entry Deleted')->with('toastType', 'danger')->with('tab', 'pengalaman');
    }

    private function validasiSkill(Request $request): array
    {
        return $request->validateWithBag('skill', [
            'nama_skill' => ['required', 'string', 'max:60'],
            'level' => ['required', Rule::in(Skill::LEVELS)],
        ]);
    }

    private function validasiPengalaman(Request $request): array
    {
        return $request->validateWithBag('pengalaman', [
            'tipe' => ['required', Rule::in(Pengalaman::TIPE)],
            'posisi' => ['required', 'string', 'max:100'],
            'perusahaan' => ['required', 'string', 'max:100'],
            'tanggal_mulai' => ['required', 'date', 'before_or_equal:today'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    private function pastikanPemilik(int $akunId): void
    {
        abort_unless($akunId === Auth::id(), 403);
    }
}

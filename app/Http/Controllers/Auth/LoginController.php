<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Mengirimkan variabel 'title' ke view agar konsisten
        $title = 'Login';
        return view('auth.login', compact('title'));
    }

    /**
     * Menangani permintaan login yang masuk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba untuk mengautentikasi pengguna
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // 3. Jika berhasil, regenerate session untuk keamanan
            $request->session()->regenerate();

            // 4. SEMUA PENGGUNA DIARAHKAN KE SATU TEMPAT YANG SAMA
            // Controller Dashboard akan menangani tampilan berdasarkan role.
            return redirect()->intended(route('dashboard'));
        }

        // 5. Jika autentikasi gagal, kembalikan ke form login dengan pesan error
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Menangani proses logout pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        // Invalidate session dan regenerate token untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan pengguna kembali ke halaman login setelah logout
        return redirect('/login');
    }
}
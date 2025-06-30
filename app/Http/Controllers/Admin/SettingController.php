<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    /**
     * Show admin account settings page
     */
    public function account()
    {
        $admin = Auth::user();
        
        return view('admin.settings.account', compact('admin'));
    }

    /**
     * Update admin profile information
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^08[0-9]{8,13}$/', 'max:15'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'phone.regex' => 'Format nomor telepon tidak valid. Gunakan format: 08xxxxxxxxxx',
            'phone.max' => 'Nomor telepon maksimal 15 digit.',
        ]);

        try {
            $admin->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui!'
                ]);
            }

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui profil.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        try {
            $admin->update([
                'password' => Hash::make($request->password),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil diubah!'
                ]);
            }

            return redirect()->back()->with('success', 'Password berhasil diubah!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengubah password.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah password.');
        }
    }

    /**
     * Logout from all devices
     */
    public function logoutAllDevices(Request $request)
    {
        try {
            // Invalidate current session
            Session::flush();
            
            // Regenerate session ID
            $request->session()->regenerate();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil logout dari semua perangkat!',
                    'redirect' => route('login')
                ]);
            }

            return redirect()->route('login')->with('success', 'Berhasil logout dari semua perangkat!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat logout.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat logout.');
        }
    }
}
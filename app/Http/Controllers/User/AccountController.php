<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Display the account settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('user.account.index');
    }
    
    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\UpdateProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        
        // Update user profile
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
        
        // Log activity
        if (function_exists('activity')) {
            activity()
                ->performedOn($user)
                ->withProperties([
                    'old_values' => [
                        'name' => $user->getOriginal('name'),
                        'phone' => $user->getOriginal('phone'),
                    ],
                    'new_values' => [
                        'name' => $user->name,
                        'phone' => $user->phone,
                    ]
                ])
                ->log('profile_updated');
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'user' => [
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                ]
            ]);
        }
        
        return redirect()->route('user.account.index')->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Update the user's password.
     *
     * @param  \App\Http\Requests\UpdatePasswordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        
        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'current_password' => ['Password saat ini tidak sesuai.'],
                    ]
                ], 422);
            }
            
            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini tidak sesuai.'],
            ]);
        }
        
        // Update the password
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Log activity
        if (function_exists('activity')) {
            activity()
                ->performedOn($user)
                ->log('password_changed');
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah',
            ]);
        }
        
        return redirect()->route('user.account.index')->with('success', 'Password berhasil diubah');
    }

    /**
     * Logout from all devices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logoutAllDevices(Request $request)
    {
        $user = Auth::user();
        
        // Regenerate the user's remember token to invalidate sessions
        $user->setRememberToken(null);
        $user->save();
        
        // Log activity before logout
        if (function_exists('activity')) {
            activity()
                ->performedOn($user)
                ->log('logout_all_devices');
        }
        
        if ($request->expectsJson()) {
            $response = [
                'success' => true,
                'message' => 'Anda berhasil keluar dari semua perangkat',
                'redirect' => route('login'),
            ];
            
            // Force logout from current device for non-AJAX requests
            // For AJAX, we'll handle the redirect on client side
            return response()->json($response);
        }
        
        // Force logout from current device
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda berhasil keluar dari semua perangkat');
    }
}
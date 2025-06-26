<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function loginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }

        return view('auth.login');
    }

    /**
     * Show the registration form
     */
    public function registerForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }

        return view('auth.register');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Attempt to log the user in
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return back()->with('error', 'Your account is inactive. Please contact administrator.');
            }

            // Check if user is suspended
            if ($user->is_suspended) {
                Auth::logout();
                return back()->with('error', 'Your account is suspended. Please contact administrator.');
            }

            $request->session()->regenerate();

            // Log the successful login using helper
            log_activity('User logged in', $user);

            return $this->redirectBasedOnRole();
        }

        return back()->with('error', 'Email or password is incorrect.')->withInput($request->only('email'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'business_name' => ['required', 'string', 'max:255'],
            'agree_terms' => ['required', 'accepted'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        DB::beginTransaction();

        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'is_active' => true,
                'is_suspended' => false,
            ]);

            // Create business profile using helper for URL generation
            $business = Business::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'public_url' => generate_business_url($request->business_name),
                'publish_status' => false,
                'progress_completion' => 10,
            ]);

            // Fire the registered event
            event(new Registered($user));

            // Log the user in
            Auth::login($user);

            // Log the registration
            log_activity('User registered', $user);

            DB::commit(); // Commit jika semua berhasil

            return redirect()->route('user.dashboard')->with('success', 'Registration successful! Welcome to our platform.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error

            return back()
                ->withErrors(['registration' => 'Registration failed. Please try again.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Log the logout using helper
            log_activity('User logged out', $user);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Handle email verification
     */
    public function verifyEmail(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid verification link.']);
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid verification link.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard')->with('info', 'Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));

            // Log email verification using helper
            log_activity('Email verified', $user);
        }

        return redirect()->route('user.dashboard')->with('success', 'Email verified successfully!');
    }

    /**
     * Resend email verification
     */
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('info', 'Email already verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }

    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    }

    /**
     * Check if business URL is available (AJAX)
     */
    public function checkBusinessUrl(Request $request)
    {
        $businessName = $request->input('business_name');
        $suggestedUrl = generate_business_url($businessName);
        $originalUrl = \Illuminate\Support\Str::slug($businessName);

        $isAvailable = !Business::where('public_url', $originalUrl)->exists();

        return response()->json([
            'available' => $isAvailable,
            'original_url' => $originalUrl,
            'suggested_url' => $suggestedUrl
        ]);
    }

    /**
     * Handle Google OAuth login (placeholder)
     */
    public function googleLogin()
    {
        // This would integrate with Laravel Socialite for Google OAuth
        // For now, return a placeholder response
        return back()->with('info', 'Google login will be available soon.');
    }

    /**
     * Show forgot password form
     */
    public function forgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset request
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Log password reset request using helper
        log_activity('Password reset requested', $user);

        // Here you would send password reset email
        // For now, just return success message
        return back()->with('success', 'Password reset link sent to your email!');
    }

    /**
     * Get dashboard stats for welcome message
     */
    public function getDashboardWelcome()
    {
        $user = current_user();
        $business = user_business();

        if (!$user || !$business) {
            return response()->json(['error' => 'User or business not found'], 404);
        }

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'initials' => user_initials($user->name),
                'avatar' => avatar_url($user->email),
                'phone_wa' => whatsapp_link($user->phone),
            ],
            'business' => [
                'name' => $business->business_name,
                'completion' => business_completion($business),
                'url' => $business->public_url,
                'status' => $business->publish_status,
            ],
            'stats' => [
                'joined' => time_ago($user->created_at),
                'last_login' => time_ago($user->updated_at),
            ]
        ]);
    }
}
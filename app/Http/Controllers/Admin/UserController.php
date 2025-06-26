<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Business;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        // Get query parameters for filtering
        $search = $request->input('search');
        $status = $request->input('status');
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        // Start query builder
        $usersQuery = User::where('role', 'user');
        
        // Apply search filter if provided
        if ($search) {
            $usersQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter if provided
        if ($status) {
            if ($status == 'active') {
                $usersQuery->where('is_active', true)
                           ->where('is_suspended', false);
            } elseif ($status == 'inactive') {
                $usersQuery->where('is_active', false);
            } elseif ($status == 'suspended') {
                $usersQuery->where('is_suspended', true);
            }
        }
        
        // Apply sorting
        $usersQuery->orderBy($sortField, $sortDirection);
        
        // Get paginated results
        $users = $usersQuery->paginate(10)
                          ->withQueryString();
        
        // Get counts for filters
        $counts = [
            'all' => User::where('role', 'user')->count(),
            'active' => User::where('role', 'user')
                           ->where('is_active', true)
                           ->where('is_suspended', false)
                           ->count(),
            'inactive' => User::where('role', 'user')
                             ->where('is_active', false)
                             ->count(),
            'suspended' => User::where('role', 'user')
                              ->where('is_suspended', true)
                              ->count(),
        ];
        
        return view('admin.users.index', compact('users', 'counts', 'search', 'status', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'in:user,admin'],
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
            'is_suspended' => false,
            'email_verified_at' => $request->has('verified') ? now() : null,
        ]);
        
        activity()
            ->performedOn($user)
            ->withProperties(['action' => 'created'])
            ->log('User created by admin');
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', "Pengguna {$user->name} berhasil dibuat");
    }
    
    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Get user's businesses
        $businesses = Business::where('user_id', $user->id)
                             ->get();
        
        // Get user's activity logs (if available)
        $activities = [];
        if (class_exists('App\Models\ActivityLog')) {
            $activities = \App\Models\ActivityLog::where('user_id', $user->id)
                                               ->latest()
                                               ->limit(10)
                                               ->get();
        }
        
        return view('admin.users.show', compact('user', 'businesses', 'activities'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'phone' => ['required', 'string', 'max:20'],
        'role' => ['required', 'in:user,admin'],
    ]);
    
    // Log old values
    $oldValues = $user->only(['name', 'email', 'phone', 'role', 'email_verified_at']);
    
    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->phone = $validated['phone'];
    $user->role = $validated['role'];
    
    // Handle email verification
    if ($request->has('verify_email') && !$user->email_verified_at) {
        $user->email_verified_at = now();
    } elseif (!$request->has('verify_email') && $user->email_verified_at) {
        $user->email_verified_at = null;
    }
    
    $user->save();
    
    activity()
        ->performedOn($user)
        ->withProperties([
            'action' => 'updated',
            'old_values' => $oldValues,
            'new_values' => $user->only(['name', 'email', 'phone', 'role', 'email_verified_at'])
        ])
        ->log('User updated by admin');
    
    return redirect()
        ->route('admin.users.show', $user)
        ->with('success', "Pengguna {$user->name} berhasil diperbarui");
}

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Check if user has businesses
        $businessCount = Business::where('user_id', $user->id)->count();
        
        if ($businessCount > 0) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', "Tidak dapat menghapus pengguna. Pengguna memiliki {$businessCount} bisnis terdaftar.");
        }
        
        $userName = $user->name;
        
        activity()
            ->withProperties(['action' => 'deleted'])
            ->log("User {$userName} deleted by admin");
        
        $user->delete();
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', "Pengguna {$userName} berhasil dihapus");
    }

    public function activate(User $user)
    {
        if (!$user->is_suspended) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('warning', "Pengguna {$user->name} sudah aktif");
        }
        
        $user->is_suspended = false;
        $user->save();
        
        activity()
            ->performedOn($user)
            ->withProperties(['action' => 'activated'])
            ->log('User activated by admin');
        
        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Pengguna {$user->name} berhasil diaktifkan");
    }

    public function suspend(User $user)
    {
        if ($user->is_suspended) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('warning', "Pengguna {$user->name} sudah dinonaktifkan");
        }
        
        $user->is_suspended = true;
        $user->save();
        
        activity()
            ->performedOn($user)
            ->withProperties(['action' => 'suspended'])
            ->log('User suspended by admin');
        
        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Pengguna {$user->name} berhasil dinonaktifkan");
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(10);
        $user->password = Hash::make($newPassword);
        $user->save();
        
        activity()
            ->performedOn($user)
            ->withProperties(['action' => 'password_reset'])
            ->log('User password reset by admin');
        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Password pengguna {$user->name} berhasil direset. Password baru: {$newPassword}");
    }
}
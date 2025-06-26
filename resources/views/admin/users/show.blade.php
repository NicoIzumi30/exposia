@extends('admin.layouts.app')

@section('title', 'Detail Pengguna')

@section('page-title', 'Detail Pengguna')

@section('content')
<!-- User Details Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Informasi Pengguna
        </h2>
        <div class="flex space-x-2">
            <!-- Edit Button -->
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            
            <!-- Toggle Status Button -->
            @if($user->is_suspended)
                <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800"
                        data-action="{{ route('admin.users.activate', $user) }}"
                        data-method="POST"
                        data-confirm-type="toggle"
                        data-confirm-title="Konfirmasi Aktivasi"
                        data-confirm-text="Pengguna {{ $user->name }} akan diaktifkan kembali dan bisa login. Lanjutkan?"
                        data-item-name="pengguna"
                        data-is-activating="true"
                        onclick="handleActionWithConfirmation(this)">
                    <i class="fas fa-toggle-on mr-2"></i>
                    Aktifkan
                </button>
            @else
                <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800"
                        data-action="{{ route('admin.users.suspend', $user) }}"
                        data-method="POST"
                        data-confirm-type="toggle"
                        data-confirm-title="Konfirmasi Nonaktivasi"
                        data-confirm-text="Pengguna {{ $user->name }} akan dinonaktifkan dan tidak bisa login. Semua website milik pengguna juga akan tidak dapat diakses. Lanjutkan?"
                        data-item-name="pengguna"
                        data-is-activating="false"
                        onclick="handleActionWithConfirmation(this)">
                    <i class="fas fa-toggle-off mr-2"></i>
                    Nonaktifkan
                </button>
            @endif
            
            <!-- Reset Password Button -->
            <button type="button"
                    class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800"
                    data-action="{{ route('admin.users.reset-password', $user) }}"
                    data-method="POST"
                    data-confirm-type="reset-password"
                    data-confirm-title="Reset Password Pengguna"
                    data-confirm-text="Password pengguna {{ $user->name }} akan direset dan password baru akan dikirim ke email {{ $user->email }}. Lanjutkan?"
                    onclick="handleActionWithConfirmation(this)">
                <i class="fas fa-key mr-2"></i>
                Reset Password
            </button>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-semibold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $user->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->is_suspended ? 'Akun Dinonaktifkan' : ($user->is_active ? 'Akun Aktif' : 'Belum Verifikasi') }}
                        </p>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <dl class="grid grid-cols-1 gap-4">
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Email
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                {{ $user->email }}
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        <i class="fas fa-clock mr-1"></i>
                                        Belum Verifikasi
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Telepon
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                {{ $user->phone }}
                            </dd>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Terdaftar
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                {{ $user->created_at->format('d M Y, H:i') }}
                                ({{ $user->created_at->diffForHumans() }})
                            </dd>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Diperbarui
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                {{ $user->updated_at->format('d M Y, H:i') }}
                                ({{ $user->updated_at->diffForHumans() }})
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Account Statistics -->
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Statistik Akun
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Website Count -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Total Website
                            </span>
                            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $businesses->count() }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Published Websites -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Website Dipublikasi
                            </span>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ $businesses->where('publish_status', 'published')->count() }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Average Completion -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Rata-rata Kelengkapan
                                </span>
                                <span class="text-lg font-bold text-amber-600 dark:text-amber-400">
                                    {{ $businesses->avg('progress_completion') ? round($businesses->avg('progress_completion'), 1) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $businesses->avg('progress_completion') ? round($businesses->avg('progress_completion'), 1) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Last Login -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Login Terakhir
                            </span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $user->last_login_at ? $user->last_login_at->format('d M Y, H:i') : 'Belum Ada' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Websites Card -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Website Pengguna
        </h2>
    </div>
    
    <div class="overflow-x-auto">
        @if($businesses->count() > 0)
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nama Bisnis
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        URL
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Kelengkapan
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider text-right">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($businesses as $business)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($business->logo_url)
                                <img src="{{ $business->logo_url }}" alt="{{ $business->business_name }}" class="h-10 w-10 rounded-full object-cover">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-store"></i>
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $business->business_name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Dibuat {{ $business->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white truncate max-w-xs">
                            {{ $business->public_url }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($business->publish_status === 'published')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                                Dipublikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                <span class="w-1.5 h-1.5 inline-block bg-gray-500 rounded-full mr-1.5"></span>
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2 w-24">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $business->progress_completion }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-900 dark:text-white">
                                {{ $business->progress_completion }}%
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.websites.show', $business->id) }}" 
                           class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.websites.preview', $business->id) }}" 
                           class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300" 
                           target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="p-6 text-center">
            <i class="fas fa-store text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400">Pengguna belum memiliki website</p>
        </div>
        @endif
    </div>
</div>

<!-- Recent Activities Card -->
@if(count($activities) > 0)
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Aktivitas Terkini
        </h2>
    </div>
    
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @foreach($activities as $activity)
        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-history text-sm"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900 dark:text-white">
                        {{ $activity->description }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $activity->created_at->format('d M Y, H:i') }} ({{ $activity->created_at->diffForHumans() }})
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
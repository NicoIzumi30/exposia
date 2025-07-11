@extends('admin.layouts.app')

@section('title', 'Detail Pengguna')

@section('page-title', 'Detail Pengguna')

@section('content')
<!-- User Details Card -->
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900">
            Informasi Pengguna
        </h2>
        <div class="flex space-x-2">
            <!-- Edit Button -->
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            
            <!-- Toggle Status Button -->
            @if($user->is_suspended)
                <button type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
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
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
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
                    class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
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
                    <div class="flex-shrink-0 w-16 h-16 rounded-full bg-gray-800 flex items-center justify-center text-white text-2xl font-semibold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $user->name }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $user->is_suspended ? 'Akun Dinonaktifkan' : ($user->is_active ? 'Akun Aktif' : 'Belum Verifikasi') }}
                        </p>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-200">
                    <dl class="grid grid-cols-1 gap-4">
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">
                                Email
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ $user->email }}
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Belum Verifikasi
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">
                                Telepon
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ $user->phone }}
                            </dd>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">
                                Terdaftar
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ $user->created_at->format('d M Y, H:i') }}
                                ({{ $user->created_at->diffForHumans() }})
                            </dd>
                        </div>
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">
                                Diperbarui
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ $user->updated_at->format('d M Y, H:i') }}
                                ({{ $user->updated_at->diffForHumans() }})
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Account Statistics -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Statistik Akun
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Website Count -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">
                                Total Website
                            </span>
                            <span class="text-2xl font-bold text-gray-800">
                                {{ $businesses->count() }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Published Websites -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">
                                Website Dipublikasi
                            </span>
                            <span class="text-2xl font-bold text-green-600">
                                {{ $businesses->where('publish_status', 'published')->count() }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Average Completion -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">
                                    Rata-rata Kelengkapan
                                </span>
                                <span class="text-lg font-bold text-gray-700">
                                    {{ $businesses->avg('progress_completion') ? round($businesses->avg('progress_completion'), 1) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-600 h-2 rounded-full" style="width: {{ $businesses->avg('progress_completion') ? round($businesses->avg('progress_completion'), 1) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Last Login -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">
                                Login Terakhir
                            </span>
                            <span class="text-sm font-medium text-gray-900">
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
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">
            Website Pengguna
        </h2>
    </div>
    
    <div class="overflow-x-auto">
        @if($businesses->count() > 0)
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nama Bisnis
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        URL
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kelengkapan
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($businesses as $business)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($business->logo_url)
                                <img src="{{ $business->logo_url }}" alt="{{ $business->business_name }}" class="h-10 w-10 rounded-full object-cover">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                    <i class="fas fa-store"></i>
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $business->business_name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Dibuat {{ $business->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 truncate max-w-xs">
                            {{ $business->public_url }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($business->publish_status === 'published')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                                Dipublikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <span class="w-1.5 h-1.5 inline-block bg-gray-500 rounded-full mr-1.5"></span>
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2 w-24">
                                <div class="bg-gray-600 h-2 rounded-full" style="width: {{ $business->progress_completion }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-900">
                                {{ $business->progress_completion }}%
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.websites.show', $business->id) }}" 
                           class="text-gray-600 hover:text-gray-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.websites.preview', $business->id) }}" 
                           class="text-gray-600 hover:text-gray-900" 
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
            <i class="fas fa-store text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Pengguna belum memiliki website</p>
        </div>
        @endif
    </div>
</div>

<!-- Recent Activities Card -->
@if(count($activities) > 0)
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">
            Aktivitas Terkini
        </h2>
    </div>
    
    <div class="divide-y divide-gray-200">
        @foreach($activities as $activity)
        <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-8 h-8 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-history text-sm"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900">
                        {{ $activity->description }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
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
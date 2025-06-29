@extends('layouts.public')

@section('title', 'Cek Status Laporan')

@section('content')
<div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-accent-400/5 via-transparent to-primary-500/5"></div>
    
    <div class="relative w-full max-w-lg mx-auto">
        <!-- Main Card -->
        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl border border-gray-200 dark:border-gray-700 overflow-hidden backdrop-blur-sm bg-white/95 dark:bg-gray-800/95 animate-card-entrance">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-accent-400 to-accent-600 p-8 text-center">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <span class="material-icons-outlined text-white text-3xl">search</span>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">
                    Cek Status Laporan
                </h2>
                <p class="text-accent-50 text-sm">
                    Masukkan kode laporan untuk melihat status penanganan
                </p>
            </div>
            
            <!-- Form Section -->
            <div class="p-8">
                <form action="{{ route('report.checkStatus') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="report_code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Kode Laporan <span class="text-accent-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-icons-outlined text-gray-400 text-lg">badge</span>
                            </div>
                            <input type="text" 
                                   name="report_code" 
                                   id="report_code" 
                                   value="{{ old('report_code') }}" 
                                   required 
                                   placeholder="Contoh: RPT-AB123"
                                   class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 dark:border-gray-600 focus:ring-2 focus:ring-accent-400 focus:border-accent-400 rounded-2xl shadow-sm dark:bg-gray-700 dark:text-white transition-all duration-200 @error('report_code') border-red-400 dark:border-red-500 @enderror">
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center">
                            <span class="material-icons-outlined text-xs mr-1">info</span>
                            Masukkan kode laporan yang Anda terima saat mengirimkan laporan
                        </p>
                        @error('report_code')
                            <div class="mt-2 flex items-center text-red-500">
                                <span class="material-icons-outlined text-sm mr-1">error</span>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-accent-400 to-accent-600 hover:from-accent-500 hover:to-accent-700 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-accent-400/50 flex items-center justify-center">
                        <span class="material-icons-outlined mr-2">search</span>
                        Cek Status Laporan
                    </button>
                </form>
                
                <!-- Error Message -->
                @if(session('report_not_found'))
                <div class="mt-6">
                    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 rounded-xl">
                        <div class="flex items-center">
                            <span class="material-icons-outlined text-red-500 mr-3">error</span>
                            <div>
                                <p class="text-sm font-medium text-red-800 dark:text-red-400">
                                    Laporan Tidak Ditemukan
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                                    {{ session('report_not_found') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Result Section -->
                @if(isset($report))
                <div class="mt-8 space-y-6">
                    <!-- Status Card -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-2xl p-6 shadow-inner">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                <span class="material-icons-outlined mr-2">assignment</span>
                                Detail Laporan
                            </h3>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold shadow-sm
                                @if($report->status === 'pending')
                                    bg-gradient-to-r from-yellow-400 to-yellow-500 text-yellow-900
                                @elseif($report->status === 'resolved')
                                    bg-gradient-to-r from-green-400 to-green-500 text-green-900
                                @elseif($report->status === 'rejected')
                                    bg-gradient-to-r from-red-400 to-red-500 text-red-900
                                @endif
                            ">
                                @if($report->status === 'pending')
                                    <span class="w-2 h-2 bg-yellow-800 rounded-full mr-2 animate-pulse"></span>
                                    Sedang Diproses
                                @elseif($report->status === 'resolved')
                                    <span class="w-2 h-2 bg-green-800 rounded-full mr-2"></span>
                                    Telah Diselesaikan
                                @elseif($report->status === 'rejected')
                                    <span class="w-2 h-2 bg-red-800 rounded-full mr-2"></span>
                                    Tidak Disetujui
                                @endif
                            </span>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <span class="material-icons-outlined text-accent-500 mt-0.5">confirmation_number</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Laporan</p>
                                    <p class="text-base font-bold text-gray-900 dark:text-white font-mono">
                                        {{ $report->report_code }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <span class="material-icons-outlined text-accent-500 mt-0.5">category</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Laporan</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ ucfirst($report->report_type) }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <span class="material-icons-outlined text-accent-500 mt-0.5">link</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">URL Website</p>
                                    <p class="text-sm text-gray-900 dark:text-white break-all bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                                        {{ $report->website_url }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <span class="material-icons-outlined text-accent-500 mt-0.5">schedule</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Laporan</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $report->created_at->format('d F Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                            
                            @if($report->status === 'resolved' || $report->status === 'rejected')
                            <div class="flex items-start space-x-3">
                                <span class="material-icons-outlined text-accent-500 mt-0.5">done</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Penyelesaian</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $report->resolved_at ? $report->resolved_at->format('d F Y, H:i') : 'Belum diselesaikan' }}
                                    </p>
                                </div>
                            </div>
                            
                            @php
                                $adminNotes = $report->admin_notes;
                                $contactEmail = null;
                                
                                if(strpos($adminNotes, 'Contact email:') === 0) {
                                    $parts = explode("\n", $adminNotes, 2);
                                    if(count($parts) > 1) {
                                        $contactEmail = str_replace('Contact email: ', '', $parts[0]);
                                        $adminNotes = $parts[1];
                                    } else {
                                        $contactEmail = str_replace('Contact email: ', '', $adminNotes);
                                        $adminNotes = null;
                                    }
                                }
                            @endphp
                            
                            @if($adminNotes)
                            <div class="flex items-start space-x-3">
                                <span class="material-icons-outlined text-accent-500 mt-0.5">note</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Admin</p>
                                    <div class="mt-2 p-3 bg-white dark:bg-gray-600 rounded-lg border-l-4 border-accent-400">
                                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">
                                            {{ $adminNotes }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                    
                    @if($report->status === 'pending')
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 p-4 rounded-xl">
                        <div class="flex items-center">
                            <span class="material-icons-outlined text-blue-500 mr-3 animate-pulse-soft">hourglass_empty</span>
                            <div>
                                <p class="text-sm font-medium text-blue-800 dark:text-blue-400">
                                    Laporan Sedang Diproses
                                </p>
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    Laporan Anda sedang dalam proses peninjauan oleh tim moderasi kami. Mohon menunggu 1-3 hari kerja.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                
                <!-- Footer Action -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            Belum membuat laporan?
                        </p>
                        <a href="{{ route('report.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <span class="material-icons-outlined mr-2 text-sm">add_circle</span>
                            Laporkan Website Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Automatically focus the input field
        const input = document.getElementById('report_code');
        input.focus();
        
        // Auto-capitalize input with animation
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            
            // Add visual feedback
            this.style.transform = 'scale(1.02)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
        
        // Add enter key submission with visual feedback
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const button = document.querySelector('button[type="submit"]');
                button.classList.add('animate-pulse');
                setTimeout(() => {
                    button.classList.remove('animate-pulse');
                }, 300);
            }
        });
    });
</script>
@endpush
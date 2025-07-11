@extends('admin.layouts.app')

@section('title', 'Detail Laporan')

@section('page-title', 'Detail Laporan')

@section('content')
<!-- Report Details Card -->
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">
                Laporan {{ $report->report_code }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Jenis: <span class="font-medium text-gray-700">{{ ucfirst($report->report_type) }}</span>
            </p>
        </div>
        
        <div>
            @if($report->status === 'pending')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    <span class="w-1.5 h-1.5 inline-block bg-yellow-500 rounded-full mr-1.5"></span>
                    Pending
                </span>
            @elseif($report->status === 'resolved')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                    Diselesaikan
                </span>
            @elseif($report->status === 'rejected')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <span class="w-1.5 h-1.5 inline-block bg-red-500 rounded-full mr-1.5"></span>
                    Ditolak
                </span>
            @endif
        </div>
    </div>
    
    <div class="p-6">
        <!-- Report Content -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-2">
                Isi Laporan
            </h3>
            <div class="mb-4">
                <span class="text-sm font-medium text-gray-500">URL Website:</span>
                <a href="{{ $report->website_url }}" target="_blank" class="text-gray-600 hover:underline ml-2">
                    {{ $report->website_url }}
                    <i class="fas fa-external-link-alt text-xs ml-1"></i>
                </a>
            </div>
            <p class="text-gray-700 whitespace-pre-line">
                {{ $report->report_content }}
            </p>
            
            <!-- Tampilkan gambar bukti jika ada -->
            @if($report->evidence_image)
            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Bukti Foto:</h4>
                <div class="relative w-full max-w-md overflow-hidden rounded-lg border border-gray-200">
                    <img src="{{ Storage::url($report->evidence_image) }}" 
                         alt="Bukti Laporan" 
                         class="w-full h-auto" />
                    <a href="{{ Storage::url($report->evidence_image) }}" 
                       target="_blank" 
                       class="absolute bottom-2 right-2 bg-white p-2 rounded-full shadow-md text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-expand-alt"></i>
                    </a>
                </div>
            </div>
            @endif
            
            <!-- Tampilkan info kontak jika ada -->
            @if(strpos($report->admin_notes, 'Contact email:') === 0 && $report->status === 'pending')
            <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="text-sm font-medium text-blue-700 mb-1">
                    <i class="fas fa-envelope mr-1"></i> Informasi Kontak Pelapor:
                </h4>
                <p class="text-sm text-blue-600">
                    {{ str_replace('Contact email: ', '', $report->admin_notes) }}
                </p>
            </div>
            @endif
            
            <div class="mt-4 text-sm text-gray-500">
                Dilaporkan pada {{ $report->created_at->format('d M Y, H:i') }} ({{ $report->created_at->diffForHumans() }})
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Reporter Information -->
            <div class="border border-gray-200 rounded-lg p-5">
                <h3 class="text-base font-semibold text-gray-900 mb-4">
                    Informasi Pelapor
                </h3>
                
                @if($report->user)
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center text-white font-semibold text-lg">
                            {{ strtoupper(substr($report->user->name, 0, 1)) }}
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">
                                {{ $report->user->name }}
                            </h4>
                            <p class="text-sm text-gray-500">
                                {{ $report->user->email }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">No. Telepon:</span> {{ $report->user->phone }}
                        </p>
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Status Akun:</span> 
                            @if($report->user->is_suspended)
                                <span class="text-red-600">Dinonaktifkan</span>
                            @elseif(!$report->user->is_active)
                                <span class="text-yellow-600">Belum Verifikasi</span>
                            @else
                                <span class="text-green-600">Aktif</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Terdaftar:</span> {{ $report->user->created_at->format('d M Y') }}
                        </p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.users.show', $report->user) }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-user mr-2"></i>
                            Lihat Profil Pengguna
                        </a>
                    </div>
                @else
                    <div class="bg-gray-100 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-medium text-gray-900">
                                    Pengunjung (Tidak Login)
                                </h4>
                                <p class="text-sm text-gray-500">
                                    Laporan dibuat oleh pengguna anonim
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Reported Website Information -->
            <div class="border border-gray-200 rounded-lg p-5">
                <h3 class="text-base font-semibold text-gray-900 mb-4">
                    Informasi Website yang Dilaporkan
                </h3>
                
                <div class="mb-4">
                    <div class="text-sm text-gray-700 mb-2">
                        <span class="font-medium">URL Website:</span>
                    </div>
                    <a href="{{ $report->website_url }}" 
                       target="_blank" 
                       class="text-gray-600 hover:underline">
                        {{ $report->website_url }}
                        <i class="fas fa-external-link-alt text-xs ml-1"></i>
                    </a>
                </div>
                
                @if($report->business)
                    <div class="flex items-center mb-4">
                        @if($report->business->logo_url)
                            <img src="{{ asset('storage/'.$report->business->logo_url) }}" alt="{{ $report->business->business_name }}" class="h-12 w-12 rounded-lg object-cover">
                        @else
                            <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center text-gray-500">
                                <i class="fas fa-store text-lg"></i>
                            </div>
                        @endif
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">
                                {{ $report->business->business_name }}
                            </h4>
                            <p class="text-sm text-gray-500">
                                {{ $report->business->public_url }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Status:</span> 
                            @if($report->business->publish_status === 'published')
                                <span class="text-green-600">Dipublikasi</span>
                            @else
                                <span class="text-gray-600">Draft</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Pemilik:</span> 
                            @if($report->business->user)
                                {{ $report->business->user->name }}
                            @else
                                Tidak diketahui
                            @endif
                        </p>
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Dibuat:</span> {{ $report->business->created_at->format('d M Y') }}
                        </p>
                    </div>
                    
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('admin.websites.show', $report->business) }}" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                        
                        <a href="{{ route('admin.websites.preview', $report->business) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Preview Website
                        </a>
                    </div>
                @else
                    <div class="bg-gray-100 rounded-lg p-4">
                        <p class="text-gray-500">Website tidak terdaftar dalam sistem atau telah dihapus.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Admin Actions Card -->
@if($report->status === 'pending')
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">
            Tindakan Admin
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Pilih tindakan untuk laporan ini
        </p>
    </div>
    
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <a href="{{ route('admin.reports.showResolveForm', $report) }}" 
               class="block w-full text-center px-4 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-check-circle mr-2"></i>
                Selesaikan Laporan
            </a>
            <p class="mt-2 text-xs text-gray-500 text-center">
                Tandai laporan sebagai diselesaikan dan ambil tindakan jika diperlukan.
            </p>
        </div>
        
        <div>
            <button type="button" 
                    class="block w-full text-center px-4 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    onclick="showRejectForm()">
                <i class="fas fa-times-circle mr-2"></i>
                Tolak Laporan
            </button>
            <p class="mt-2 text-xs text-gray-500 text-center">
                Tolak laporan jika tidak perlu ditindaklanjuti.
            </p>
        </div>
    </div>
    
    <!-- Reject Form (Hidden by default) -->
    <div id="reject-form-container" class="hidden p-6 pt-0 border-t border-gray-200">
        <form id="reject-form" action="{{ route('admin.reports.reject', $report) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea name="admin_notes" 
                          id="admin_notes" 
                          rows="4" 
                          required 
                          class="w-full px-4 py-2 border-gray-300 focus:ring-gray-500 focus:border-gray-500 rounded-md shadow-sm bg-white text-gray-900" 
                          placeholder="Berikan alasan mengapa laporan ini ditolak..."></textarea>
                <p class="mt-1 text-xs text-gray-500">
                    Minimal 10 karakter. Alasan ini akan disimpan sebagai catatan admin.
                </p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="hideRejectForm()"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Batal
                </button>
                <button type="button" 
                        onclick="handleFormSubmissionWithConfirmation()"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Tolak Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Resolution Details Card -->
@if($report->status !== 'pending')
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">
            Detail Penyelesaian
        </h2>
    </div>
    
    <div class="p-6">
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex items-center mb-4">
                @if($report->status === 'resolved')
                    <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3">
                        <i class="fas fa-check"></i>
                    </div>
                    <h3 class="text-lg font-medium text-green-600">
                        Laporan Diselesaikan
                    </h3>
                @else
                    <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mr-3">
                        <i class="fas fa-times"></i>
                    </div>
                    <h3 class="text-lg font-medium text-red-600">
                        Laporan Ditolak
                    </h3>
                @endif
            </div>
            
            <div class="mt-4 space-y-4">
                <!-- Jika ada kontak email, pisahkan dari catatan admin -->
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
                <div>
                    <h4 class="text-sm font-medium text-gray-700">
                        Catatan Admin:
                    </h4>
                    <p class="mt-1 text-gray-700 whitespace-pre-line">
                        {{ $adminNotes }}
                    </p>
                </div>
                @endif
                
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Diselesaikan pada: {{ $report->resolved_at ? $report->resolved_at->format('d M Y, H:i') : '-' }}
                        ({{ $report->resolved_at ? $report->resolved_at->diffForHumans() : '-' }})
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Back Button -->
<div class="flex justify-start mb-6">
    <a href="{{ route('admin.reports.index') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar Laporan
    </a>
</div>
@endsection

@push('scripts')
<script>
    function showRejectForm() {
        document.getElementById('reject-form-container').classList.remove('hidden');
    }
    
    function hideRejectForm() {
        document.getElementById('reject-form-container').classList.add('hidden');
    }
    
    function handleFormSubmissionWithConfirmation() {
        const notes = document.getElementById('admin_notes').value;
        
        if (notes.length < 10) {
            window.showToast('Alasan penolakan harus minimal 10 karakter', 'error');
            return;
        }
        
        window.confirmDelete({
            title: 'Konfirmasi Tolak Laporan',
            text: 'Apakah Anda yakin ingin menolak laporan ini? Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            confirmButtonText: 'Ya, Tolak Laporan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('reject-form').submit();
            }
        });
    }
</script>
@endpush
@extends('admin.layouts.app')

@section('title', 'Selesaikan Laporan')

@section('page-title', 'Selesaikan Laporan')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Selesaikan Laporan {{ $report->report_code }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Isi form berikut untuk menyelesaikan laporan
        </p>
    </div>
    
    <div class="p-6">
        <!-- Report Summary -->
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Ringkasan Laporan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Kode Laporan:
                    </p>
                    <p class="text-sm text-gray-900 dark:text-white">
                        {{ $report->report_code }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Jenis Laporan:
                    </p>
                    <p class="text-sm text-gray-900 dark:text-white">
                        {{ ucfirst($report->report_type) }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Tanggal Laporan:
                    </p>
                    <p class="text-sm text-gray-900 dark:text-white">
                        {{ $report->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        URL Website:
                    </p>
                    <p class="text-sm text-gray-900 dark:text-white truncate">
                        {{ $report->website_url }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Isi Laporan:
                    </p>
                    <p class="text-sm text-gray-900 dark:text-white">
                        {{ $report->report_content }}
                    </p>
                </div>
                
                @if($report->evidence_image)
                <div class="md:col-span-2">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                        Bukti Foto:
                    </p>
                    <a href="{{ Storage::url($report->evidence_image) }}" 
                       target="_blank" 
                       class="inline-block">
                        <img src="{{ Storage::url($report->evidence_image) }}" 
                             alt="Bukti Laporan" 
                             class="h-20 w-auto rounded border border-gray-200 dark:border-gray-700" />
                    </a>
                </div>
                @endif
                
                <!-- Info kontak pelapor jika ada -->
                @if(strpos($report->admin_notes, 'Contact email:') === 0)
                <div class="md:col-span-2">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                        Email Kontak Pelapor:
                    </p>
                    <p class="text-sm text-blue-600 dark:text-blue-400">
                        {{ str_replace('Contact email: ', '', $report->admin_notes) }}
                    </p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Resolution Form -->
        <form id="resolve-form" action="{{ route('admin.reports.resolve', $report) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Catatan Penyelesaian <span class="text-red-500">*</span>
                </label>
                <textarea name="admin_notes" 
                          id="admin_notes" 
                          rows="4" 
                          required 
                          class="w-full px-4 py-2 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white @error('admin_notes') border-red-500 dark:border-red-500 @enderror" 
                          placeholder="Jelaskan tindakan yang diambil untuk menyelesaikan laporan ini...">{{ old('admin_notes') }}@if(strpos($report->admin_notes, 'Contact email:') === 0)
Contact email: {{ str_replace('Contact email: ', '', $report->admin_notes) }}

@endif</textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Minimal 10 karakter. Catatan ini akan disimpan sebagai dokumentasi penyelesaian.
                </p>
                @error('admin_notes')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Tindakan yang Diambil <span class="text-red-500">*</span>
                </label>
                
                <div class="mt-2 space-y-3">
                    <div class="flex items-center">
                        <input id="action-none" 
                               name="action_taken" 
                               type="radio" 
                               value="none" 
                               checked 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <label for="action-none" class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                            Tidak ada tindakan khusus (laporan hanya dicatat)
                        </label>
                    </div>
                    
                    @if($report->business)
                    <div class="flex items-center">
                        <input id="action-unpublish" 
                               name="action_taken" 
                               type="radio" 
                               value="unpublish_business" 
                               {{ old('action_taken') == 'unpublish_business' ? 'checked' : '' }} 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <label for="action-unpublish" class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                            Unpublish website bisnis <span class="font-medium">{{ $report->business->business_name }}</span>
                        </label>
                    </div>
                    @endif
                    
                    @if($report->user && $report->user->id !== auth()->id())
                    <div class="flex items-center">
                        <input id="action-suspend" 
                               name="action_taken" 
                               type="radio" 
                               value="suspend_user" 
                               {{ old('action_taken') == 'suspend_user' ? 'checked' : '' }} 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <label for="action-suspend" class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                            Nonaktifkan akun pengguna <span class="font-medium">{{ $report->user->name }}</span>
                        </label>
                    </div>
                    @endif
                </div>
                
                @error('action_taken')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Warning Alert for Serious Actions -->
            <div id="action-warning" class="mb-6 hidden">
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-400">
                                <span id="warning-text">Tindakan ini akan memengaruhi pengguna atau bisnis terkait. Pastikan Anda telah mempertimbangkan dengan baik.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.reports.show', $report) }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    Batal
                </a>
                <button type="button" 
                        onclick="handleFormSubmissionWithConfirmation()"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800">
                    Selesaikan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide warning based on action selection
        const actionInputs = document.querySelectorAll('input[name="action_taken"]');
        const actionWarning = document.getElementById('action-warning');
        const warningText = document.getElementById('warning-text');
        
        function updateWarning() {
            const selectedAction = document.querySelector('input[name="action_taken"]:checked').value;
            
            if (selectedAction === 'none') {
                actionWarning.classList.add('hidden');
            } else {
                if (selectedAction === 'unpublish_business') {
                    warningText.textContent = 'Tindakan ini akan mematikan akses publik ke website bisnis. Pengguna masih dapat mengedit website tapi tidak dapat diakses publik.';
                } else if (selectedAction === 'suspend_user') {
                    warningText.textContent = 'Tindakan ini akan menonaktifkan akun pengguna dan mencegah mereka masuk ke sistem. Semua website mereka juga akan tidak dapat diakses.';
                }
                
                actionWarning.classList.remove('hidden');
            }
        }
        
        // Initialize warning visibility
        updateWarning();
        
        // Update warning when action changes
        actionInputs.forEach(input => {
            input.addEventListener('change', updateWarning);
        });
    });
    
    function handleFormSubmissionWithConfirmation() {
        const notes = document.getElementById('admin_notes').value;
        const selectedAction = document.querySelector('input[name="action_taken"]:checked').value;
        
        if (notes.length < 10) {
            window.showToast('Catatan penyelesaian harus minimal 10 karakter', 'error');
            return;
        }
        
        let confirmTitle = 'Konfirmasi Penyelesaian Laporan';
        let confirmText = 'Apakah Anda yakin ingin menyelesaikan laporan ini?';
        let confirmIcon = 'question';
        
        if (selectedAction !== 'none') {
            confirmTitle = 'Konfirmasi Tindakan Serius';
            confirmText = 'Anda akan mengambil tindakan serius terhadap pengguna atau bisnis. Tindakan ini tidak dapat dibatalkan. Lanjutkan?';
            confirmIcon = 'warning';
        }
        
        window.showConfirmation({
            title: confirmTitle,
            text: confirmText,
            icon: confirmIcon,
            confirmButtonText: 'Ya, Selesaikan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('resolve-form').submit();
            }
        });
    }
</script>
@endpush
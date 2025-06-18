@extends('user.layouts.app')

@section('title', 'Testimoni')

@section('page-title', 'Testimoni')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
            Testimoni Pelanggan
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 transition-colors duration-300">
            Kelola testimoni dan ulasan dari pelanggan Anda
        </p>
    </div>
    <div class="flex items-center space-x-3 mt-4 sm:mt-0">
        <!-- Add Testimonial Button -->
        <button onclick="openTestimonialModal()" 
                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Testimoni</span>
        </button>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Testimonials Table -->
    <div class="lg:col-span-3">
        @if($testimonials->count() > 0)
        <!-- Testimonials Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden animate-slide-up">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nama & Posisi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Testimoni
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($testimonials as $testimonial)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200" data-testimonial-id="{{ $testimonial->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $testimonial->testimonial_name }}
                                    </div>
                                    @if($testimonial->testimonial_position)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $testimonial->testimonial_position }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white line-clamp-3 max-w-md">
                                    "{{ $testimonial->testimonial_content }}"
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $testimonial->formatted_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="editTestimonial('{{ $testimonial->id }}')" 
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteTestimonial('{{ $testimonial->id }}', '{{ $testimonial->testimonial_name }}')" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($testimonials->hasPages())
        <div class="mt-8">
            {{ $testimonials->links() }}
        </div>
        @endif
        
        @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center animate-slide-up">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-quote-right text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Testimoni</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                Tambahkan testimoni pertama dari pelanggan yang puas dengan produk atau layanan Anda.
            </p>
            <button onclick="openTestimonialModal()" 
                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <i class="fas fa-plus mr-2"></i>
                Tambah Testimoni Pertama
            </button>
        </div>
        @endif
    </div>

    <!-- Right Sidebar - Stats & Tips -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Testimonial Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Testimoni</h3>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Testimoni</span>
                    <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $testimonialStats['total'] }}</span>
                </div>
                @if($testimonialStats['total'] > 0)
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Bulan Ini</span>
                    <span class="text-lg font-semibold text-blue-600 dark:text-blue-400">{{ $testimonialStats['this_month'] }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Tips -->
        <div class="bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800 p-6 animate-slide-up">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-lightbulb text-white text-sm"></i>
                </div>
                <h4 class="font-semibold text-emerald-800 dark:text-emerald-200">Tips Testimoni</h4>
            </div>
            <ul class="space-y-2 text-sm text-emerald-700 dark:text-emerald-300">
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-emerald-500 mt-0.5 flex-shrink-0"></i>
                    <span>Minta testimoni dari pelanggan yang puas</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-emerald-500 mt-0.5 flex-shrink-0"></i>
                    <span>Cantumkan nama dan posisi untuk kredibilitas</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-emerald-500 mt-0.5 flex-shrink-0"></i>
                    <span>Testimoni spesifik lebih meyakinkan</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-emerald-500 mt-0.5 flex-shrink-0"></i>
                    <span>Update testimoni secara berkala</span>
                </li>
            </ul>
        </div>

        <!-- Back to Dashboard -->
        <a href="{{ route('user.dashboard') }}" 
           class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-center flex items-center justify-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>
</div>

<!-- Testimonial Modal -->
<div id="testimonialModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true" onclick="closeTestimonialModal()"></div>

        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">
                    Tambah Testimoni Baru
                </h3>
                <button onclick="closeTestimonialModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="testimonialForm" class="space-y-6">
                @csrf
                <input type="hidden" id="testimonialId" name="testimonial_id">
                
                <!-- Customer Name -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Nama Pelanggan <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" id="testimonialName" name="testimonial_name" required
                           class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200"
                           placeholder="Nama lengkap pelanggan">
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Customer Position -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-briefcase mr-2 text-blue-500"></i>
                        Posisi/Jabatan
                    </label>
                    <input type="text" id="testimonialPosition" name="testimonial_position"
                           class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200"
                           placeholder="CEO, Manager, Pelanggan, dll.">
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Testimonial Content -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-quote-right mr-2 text-blue-500"></i>
                        Isi Testimoni <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea id="testimonialContent" name="testimonial_content" rows="5" required
                              class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200"
                              placeholder="Tulis testimoni pelanggan di sini..."></textarea>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Minimal 10 karakter, maksimal 1000 karakter
                    </div>
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Modal Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" id="submitBtn"
                            class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Testimoni</span>
                    </button>
                    <button type="button" onclick="closeTestimonialModal()"
                            class="flex-1 sm:flex-none bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let isEditMode = false;
let currentTestimonialId = null;

// Build URLs dynamically
function buildUrl(action, id = null) {
    const baseUrl = '{{ url("user/testimonials") }}';
    
    switch(action) {
        case 'index':
        case 'store':
            return baseUrl;
        case 'show':
        case 'update':
        case 'destroy':
            return `${baseUrl}/${id}`;
        default:
            return baseUrl;
    }
}

// Get CSRF token
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

// Open testimonial modal
function openTestimonialModal(testimonialData = null) {
    const modal = document.getElementById('testimonialModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('testimonialForm');
    
    // Reset form
    form.reset();
    clearErrors();
    
    if (testimonialData) {
        // Edit mode
        isEditMode = true;
        currentTestimonialId = testimonialData.id;
        modalTitle.textContent = 'Edit Testimoni';
        
        // Fill form with testimonial data
        document.getElementById('testimonialId').value = testimonialData.id;
        document.getElementById('testimonialName').value = testimonialData.testimonial_name;
        document.getElementById('testimonialPosition').value = testimonialData.testimonial_position || '';
        document.getElementById('testimonialContent').value = testimonialData.testimonial_content;
    } else {
        // Add mode
        isEditMode = false;
        currentTestimonialId = null;
        modalTitle.textContent = 'Tambah Testimoni Baru';
    }
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus first input
    setTimeout(() => {
        document.getElementById('testimonialName').focus();
    }, 100);
}

// Close testimonial modal
function closeTestimonialModal() {
    const modal = document.getElementById('testimonialModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
    isEditMode = false;
    currentTestimonialId = null;
}

// Edit testimonial
function editTestimonial(testimonialId) {
    fetch(buildUrl('show', testimonialId), {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            openTestimonialModal(data.testimonial);
        } else {
            showToast(data.message || 'Gagal memuat data testimoni', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat memuat data testimoni', 'error');
    });
}

// Delete testimonial
function deleteTestimonial(testimonialId, testimonialName) {
    if (confirm(`Apakah Anda yakin ingin menghapus testimoni dari "${testimonialName}"?`)) {
        fetch(buildUrl('destroy', testimonialId), {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                // Remove testimonial row from DOM
                const testimonialRow = document.querySelector(`[data-testimonial-id="${testimonialId}"]`);
                if (testimonialRow) {
                    testimonialRow.style.opacity = '0';
                    testimonialRow.style.transform = 'translateX(-100%)';
                    setTimeout(() => {
                        testimonialRow.remove();
                        updateTestimonialCount();
                    }, 300);
                }
            } else {
                showToast(data.message || 'Gagal menghapus testimoni', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menghapus testimoni', 'error');
        });
    }
}

// Handle form submission
document.getElementById('testimonialForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalContent = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>Menyimpan...';
    submitBtn.disabled = true;
    
    clearErrors();
    
    const formData = new FormData(this);
    const url = isEditMode 
        ? buildUrl('update', currentTestimonialId)
        : buildUrl('store');
    
    // For PUT request, we need to add method override
    if (isEditMode) {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeTestimonialModal();
            
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Terjadi kesalahan', 'error');
            
            // Handle validation errors
            if (data.errors) {
                displayErrors(data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat menyimpan testimoni', 'error');
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;
    });
});

// Utility functions
function clearErrors() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(msg => {
        msg.classList.add('hidden');
        msg.textContent = '';
    });
    
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.classList.remove('border-red-500');
    });
}

function displayErrors(errors) {
    Object.keys(errors).forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('border-red-500');
            const errorDiv = input.parentElement.querySelector('.error-message');
            if (errorDiv) {
                errorDiv.textContent = errors[field][0];
                errorDiv.classList.remove('hidden');
            }
        }
    });
}

function updateTestimonialCount() {
    const testimonialRows = document.querySelectorAll('[data-testimonial-id]');
    const count = testimonialRows.length;
    
    // Show empty state if no testimonials
    if (count === 0) {
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }
}

// Toast notification function
function showToast(message, type = 'info', duration = 5000) {
    const container = document.getElementById('toast-container') || document.body;
    const toast = document.createElement('div');
    
    const bgColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    toast.className = `fixed bottom-4 right-4 z-[9999] p-4 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full opacity-0 max-w-sm ${bgColors[type] || bgColors.info}`;
    
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${icons[type] || icons.info} mr-3"></i>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 100);
    
    if (duration > 0) {
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }
        }, duration);
    }
}

// Handle escape key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTestimonialModal();
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Testimonials page initialized');
});
</script>
@endpush

@push('styles')
<style>
/* Table styles */
.table-auto th {
    font-weight: 600;
}

/* Line clamp utilities */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Modal animations */
#testimonialModal {
    backdrop-filter: blur(4px);
}

#testimonialModal > div > div {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Table hover effects */
tbody tr {
    transition: all 0.2s ease;
}

tbody tr:hover {
    transform: translateY(-1px);
}

/* Form field focus styles */
input:focus, textarea:focus {
    transform: translateY(-1px);
}

/* Button hover effects */
button {
    transition: all 0.2s ease;
}

/* Responsive table */
@media (max-width: 768px) {
    .table-auto {
        font-size: 0.875rem;
    }
    
    .table-auto th,
    .table-auto td {
        padding: 0.75rem 0.5rem;
    }
}

/* Custom scrollbar for modal */
#testimonialModal .overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

#testimonialModal .overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
}

#testimonialModal .overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.5);
    border-radius: 3px;
}
</style>
@endpush
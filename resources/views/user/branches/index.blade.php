@extends('user.layouts.app')

@section('title', 'Cabang')

@section('page-title', 'Cabang')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
            Cabang Usaha
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 transition-colors duration-300">
            Kelola lokasi cabang dan informasi kontak
        </p>
    </div>
    <div class="flex items-center space-x-3 mt-4 sm:mt-0">
        <!-- Add Branch Button -->
        <button onclick="openBranchModal()" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Cabang</span>
        </button>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column - Branch List -->
    <div class="lg:col-span-2 space-y-6">
        @if($branches->count() > 0)
        <!-- Branches Grid/List -->
        <div class="space-y-4" id="branches-container">
            @foreach($branches as $branch)
            <div class="branch-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200 animate-slide-up" data-branch-id="{{ $branch->id }}">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                    <!-- Branch Info -->
                    <div class="flex-1">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-white text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    {{ $branch->branch_name }}
                                </h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-start space-x-2">
                                        <i class="fas fa-map-marker-alt text-gray-400 mt-0.5 flex-shrink-0"></i>
                                        <span class="text-gray-600 dark:text-gray-400">{{ $branch->branch_address }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-clock text-gray-400 flex-shrink-0"></i>
                                        <span class="text-gray-600 dark:text-gray-400">{{ $branch->branch_operational_hours }}</span>
                                    </div>
                                    @if($branch->branch_phone)
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-phone text-gray-400 flex-shrink-0"></i>
                                        <span class="text-gray-600 dark:text-gray-400">{{ $branch->branch_phone }}</span>
                                        <a href="{{ whatsapp_link($branch->branch_phone) }}" target="_blank" class="text-green-500 hover:text-green-600 transition-colors duration-200">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </div>
                                    @endif
                                    @if($branch->branch_google_maps_link)
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-map text-gray-400 flex-shrink-0"></i>
                                        <a href="{{ $branch->branch_google_maps_link }}" target="_blank" class="text-blue-500 hover:text-blue-600 transition-colors duration-200">
                                            Lihat di Google Maps
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2 mt-4 md:mt-0 ml-16 md:ml-4">
                        <button onclick="editBranch('{{ $branch->id }}')" class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteBranch('{{ $branch->id }}', '{{ $branch->branch_name }}')" class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center animate-slide-up">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-map-marker-alt text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Cabang</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                Tambahkan cabang usaha Anda untuk mempermudah pelanggan menemukan lokasi bisnis.
            </p>
            <button onclick="openBranchModal()" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <i class="fas fa-plus mr-2"></i>
                Tambah Cabang Pertama
            </button>
        </div>
        @endif
    </div>

    <!-- Right Column - Info & Tips -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Branch Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 animate-slide-up">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chart-bar text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Cabang</h3>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Cabang</span>
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $branches->count() }}</span>
                </div>
                @if($branches->count() > 0)
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Dengan Kontak WhatsApp</span>
                    <span class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $branches->whereNotNull('branch_phone')->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Dengan Google Maps</span>
                    <span class="text-lg font-semibold text-purple-600 dark:text-purple-400">{{ $branches->whereNotNull('branch_google_maps_link')->count() }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Tips -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl border border-amber-200 dark:border-amber-800 p-6 animate-slide-up">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-lightbulb text-white text-sm"></i>
                </div>
                <h4 class="font-semibold text-amber-800 dark:text-amber-200">Tips Cabang</h4>
            </div>
            <ul class="space-y-2 text-sm text-amber-700 dark:text-amber-300">
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                    <span>Tambahkan link Google Maps untuk memudahkan navigasi</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                    <span>Sertakan nomor WhatsApp untuk kontak langsung</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                    <span>Cantumkan jam operasional yang jelas</span>
                </li>
                <li class="flex items-start space-x-2">
                    <i class="fas fa-check-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                    <span>Gunakan nama cabang yang mudah diingat</span>
                </li>
            </ul>
        </div>

        <!-- Back to Dashboard -->
        <a href="{{ route('user.dashboard') }}" class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 text-center flex items-center justify-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>
</div>

<!-- Branch Modal -->
<div id="branchModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true" onclick="closeBranchModal()"></div>

        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">
                    Tambah Cabang Baru
                </h3>
                <button onclick="closeBranchModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="branchForm" class="space-y-6">
                @csrf
                <input type="hidden" id="branchId" name="branch_id">

                <!-- Branch Name -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-store mr-2 text-blue-500"></i>
                        Nama Cabang <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" id="branchName" name="branch_name" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Contoh: Cabang Malioboro, Cabang Yogya">
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Branch Address -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                        Alamat Cabang <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea id="branchAddress" name="branch_address" rows="3" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Masukkan alamat lengkap cabang"></textarea>
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Operational Hours -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-clock mr-2 text-blue-500"></i>
                            Jam Operasional <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" id="branchHours" name="branch_operational_hours" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Senin-Sabtu 08:00-17:00">
                        <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2 text-blue-500"></i>
                            Nomor Telepon
                        </label>
                        <input type="text" id="branchPhone" name="branch_phone" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="08123456789">
                        <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                    </div>
                </div>

                <!-- Google Maps Link -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-map mr-2 text-blue-500"></i>
                        Link Google Maps
                    </label>
                    <input type="url" id="branchMapsLink" name="branch_google_maps_link" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="https://maps.google.com/...">
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Modal Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6">
                    <button type="submit" id="submitBtn" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 flex items-center justify-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Cabang</span>
                    </button>
                    <button type="button" onclick="closeBranchModal()" class="flex-1 sm:flex-none bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
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
    let currentBranchId = null;

    function buildUrl(action, id = null) {
        const baseUrl = '{{ url("user/branches") }}';
        switch (action) {
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

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function openBranchModal(branchData = null) {
        const modal = document.getElementById('branchModal');
        const modalTitle = document.getElementById('modalTitle');
        const form = document.getElementById('branchForm');
        form.reset();
        clearErrors();

        if (branchData) {
            isEditMode = true;
            currentBranchId = branchData.id;
            modalTitle.textContent = 'Edit Cabang';
            document.getElementById('branchId').value = branchData.id;
            document.getElementById('branchName').value = branchData.branch_name;
            document.getElementById('branchAddress').value = branchData.branch_address;
            document.getElementById('branchHours').value = branchData.branch_operational_hours;
            document.getElementById('branchPhone').value = branchData.branch_phone || '';
            document.getElementById('branchMapsLink').value = branchData.branch_Maps_link || '';
            showToast('Data cabang dimuat', 'info', 2000);
        } else {
            isEditMode = false;
            currentBranchId = null;
            modalTitle.textContent = 'Tambah Cabang Baru';
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            document.getElementById('branchName').focus();
        }, 100);
    }

    function closeBranchModal() {
        const modal = document.getElementById('branchModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        isEditMode = false;
        currentBranchId = null;
    }

    function editBranch(branchId) {
        showToast('Memuat data cabang...', 'info', 1000);
        fetch(buildUrl('show', branchId), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    openBranchModal(data.branch);
                } else {
                    showToast(data.message || 'Gagal memuat data cabang', 'error');
                }
            })
            .catch(error => {
                showToast('Terjadi kesalahan saat memuat data cabang', 'error');
            });
    }

    function deleteBranch(branchId, branchName) {
        showConfirmation({
            title: 'Hapus Cabang Ini?',
            text: `Cabang "${branchName}" akan dihapus secara permanen.`,
            icon: 'warning',
            confirmButtonText: 'Ya, Hapus!'
        }, () => {
            showToast('Menghapus cabang...', 'info', 0);
            fetch(buildUrl('destroy', branchId), {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    }
                })
                .then(response => response.json())
                .then(data => {
                    window.clearAllToasts();
                    if (data.success) {
                        showToast(data.message || 'Cabang berhasil dihapus', 'success');
                        const branchCard = document.querySelector(`[data-branch-id="${branchId}"]`);
                        if (branchCard) {
                            branchCard.style.transform = 'translateX(-100%)';
                            branchCard.style.opacity = '0';
                            setTimeout(() => {
                                branchCard.remove();
                                updateBranchCount();
                            }, 300);
                        }
                    } else {
                        showToast(data.message || 'Gagal menghapus cabang', 'error');
                    }
                })
                .catch(error => {
                    window.clearAllToasts();
                    showToast('Terjadi kesalahan saat menghapus cabang', 'error');
                });
        });
    }

    document.getElementById('branchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submitBtn');
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>Menyimpan...';
        submitBtn.disabled = true;
        clearErrors();

        const formData = new FormData(this);
        const url = isEditMode ? buildUrl('update', currentBranchId) : buildUrl('store');
        const method = isEditMode ? 'PUT' : 'POST';

        const data = {};
        for (let [key, value] of formData.entries()) {
            if (key !== 'branch_id') {
                data[key] = value;
            }
        }

        showToast('Menyimpan data cabang...', 'info', 0);

        fetch(url, {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                window.clearAllToasts();
                if (data.success) {
                    showToast(data.message || 'Cabang berhasil disimpan', 'success');
                    closeBranchModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan', 'error');
                    if (data.errors) {
                        displayErrors(data.errors);
                    }
                }
            })
            .catch(error => {
                window.clearAllToasts();
                showToast('Terjadi kesalahan saat menyimpan cabang', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            });
    });

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(msg => {
            msg.classList.add('hidden');
            msg.textContent = '';
        });
        document.querySelectorAll('input, textarea').forEach(input => {
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
        showToast('Periksa kembali form Anda', 'warning');
    }

    function updateBranchCount() {
        const branchCards = document.querySelectorAll('.branch-card');
        const count = branchCards.length;
        const countElement = document.querySelector('.text-2xl.font-bold.text-blue-600');
        if (countElement) {
            countElement.textContent = count;
        }
        if (count === 0) {
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBranchModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const branchCards = document.querySelectorAll('.branch-card');
        if (branchCards.length === 0) {
            showToast('Tambahkan cabang pertama Anda untuk memulai', 'info', 5000);
        }
    });
</script>
@endpush
@push('styles')
<style>
    #branchModal {
        backdrop-filter: blur(4px);
    }

    #branchModal>div>div {
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

    .branch-card {
        transition: all 0.3s ease;
    }

    .branch-card:hover {
        transform: translateY(-2px);
    }

    .loading {
        pointer-events: none;
        opacity: 0.7;
    }

    input:focus,
    textarea:focus {
        transform: translateY(-1px);
    }

    #branchModal .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    #branchModal .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    #branchModal .overflow-y-auto::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.5);
        border-radius: 3px;
    }

    .empty-state {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        #branchModal>div>div {
            margin: 1rem;
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
        }
    }
</style>
@endpush
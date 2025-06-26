<!-- resources/views/user/contacts/index.blade.php -->
@extends('user.layouts.app')

@section('title', 'Kelola Kontak Bisnis')

@section('content')
<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
            Kontak Bisnis
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
            Tambahkan kontak bisnis Anda untuk memudahkan pelanggan menghubungi Anda
        </p>
    </div>
    <button onclick="openContactModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
        <i class="fas fa-plus mr-2"></i>Tambah Kontak
    </button>
</div>

<!-- Contact List -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
    <div class="flex items-center mb-6">
        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
            <i class="fas fa-address-book text-white"></i>
        </div>
        <h2 class="text-xl font-semibold">Daftar Kontak</h2>
    </div>

    @if($contacts->count() > 0)
    <!-- Contact Cards with Drag & Drop -->
    <div class="space-y-4" id="contactsList">
        @foreach($contacts as $contact)
        <div class="contact-card bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border {{ $contact->is_active ? '' : 'opacity-60' }}" 
             data-contact-id="{{ $contact->id }}">
            <div class="flex items-start">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                    {!! $contact->icon_html !!}
                </div>
                <div class="flex-grow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $contact->contact_title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $contact->contact_description }}</p>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded">
                                    {{ $availableTypes[$contact->contact_type]['name'] ?? $contact->contact_type }}
                                </span>
                                <a href="{{ $contact->contact_url }}" target="_blank" class="ml-3 text-blue-500 hover:underline">
                                    {{ $contact->contact_value }}
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="toggleContactActive('{{ $contact->id }}', {{ $contact->is_active ? 'false' : 'true' }})" 
                                    class="p-2 rounded-lg text-gray-500 hover:text-{{ $contact->is_active ? 'red' : 'green' }}-500 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <i class="fas fa-{{ $contact->is_active ? 'eye-slash' : 'eye' }}"></i>
                            </button>
                            <button onclick="editContact('{{ $contact->id }}')" 
                                    class="p-2 rounded-lg text-gray-500 hover:text-blue-500 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteContact('{{ $contact->id }}', '{{ $contact->contact_title }}')" 
                                    class="p-2 rounded-lg text-gray-500 hover:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <i class="fas fa-trash"></i>
                            </button>
                            <div class="cursor-move text-gray-400 hover:text-gray-600 p-2 handle">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-address-book text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Kontak</h3>
        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-6">
            Tambahkan kontak bisnis seperti WhatsApp, Instagram, atau Shopee untuk memudahkan pelanggan menghubungi bisnis Anda.
        </p>
        <button onclick="openContactModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah Kontak Pertama
        </button>
    </div>
    @endif
</div>

<!-- Tips Card -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mt-6">
    <div class="flex items-center mb-4">
        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
            <i class="fas fa-lightbulb text-white"></i>
        </div>
        <h2 class="text-xl font-semibold">Tips</h2>
    </div>
    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
        <li class="flex items-start">
            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
            <span>Tambahkan kontak WhatsApp bisnis Anda untuk memudahkan komunikasi langsung dengan pelanggan</span>
        </li>
        <li class="flex items-start">
            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
            <span>Pastikan link marketplace (Shopee, Tokopedia) sudah benar untuk meningkatkan konversi penjualan</span>
        </li>
        <li class="flex items-start">
            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
            <span>Tambahkan link Google Maps agar pelanggan dapat dengan mudah menemukan lokasi fisik toko Anda</span>
        </li>
        <li class="flex items-start">
            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
            <span>Urutkan kontak berdasarkan prioritas - platform yang paling sering dikunjungi sebaiknya berada di urutan atas</span>
        </li>
    </ul>
</div>

<!-- Contact Modal -->
<div id="contactModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true" onclick="closeContactModal()"></div>

        <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="contactModalTitle">
                    Tambah Kontak Baru
                </h3>
                <button onclick="closeContactModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="contactForm" class="space-y-4">
                @csrf
                <input type="hidden" id="contactId" name="contact_id">

                <!-- Contact Type -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-tag mr-2 text-blue-500"></i>
                        Jenis Kontak <span class="text-red-500 ml-1">*</span>
                    </label>
                    <select id="contactType" name="contact_type" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200">
                        <option value="">Pilih Jenis Kontak</option>
                        @foreach($availableTypes as $type => $config)
                        <option value="{{ $type }}">{{ $config['name'] }}</option>
                        @endforeach
                    </select>
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Contact Title -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-heading mr-2 text-blue-500"></i>
                        Judul Kontak <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" id="contactTitle" name="contact_title" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Misalnya: Kunjungi Shopee Kami">
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Contact Description -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-align-left mr-2 text-blue-500"></i>
                        Deskripsi Kontak
                    </label>
                    <input type="text" id="contactDescription" name="contact_description" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="Misalnya: Lihat produk lain">
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Contact Value -->
                <div>
                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-link mr-2 text-blue-500"></i>
                        URL/Username/Nomor Telepon <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <span id="valuePrefix" class="absolute left-0 top-0 px-4 py-3 text-gray-500 dark:text-gray-400"></span>
                        <input type="text" id="contactValue" name="contact_value" required class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:outline-none transition-all duration-200" placeholder="">
                    </div>
                    <p id="valueHelper" class="text-xs text-gray-500 dark:text-gray-400 mt-1">Masukkan URL lengkap atau username sesuai jenis kontak</p>
                    <div class="error-message hidden mt-1 text-sm text-red-600 dark:text-red-400"></div>
                </div>

                <!-- Is Active -->
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="isActive" name="is_active" value="1" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="isActive" class="text-gray-700 dark:text-gray-300">Aktif</label>
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" id="contactSubmitBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-save mr-2"></i>Simpan Kontak
                    </button>
                    <button type="button" onclick="closeContactModal()" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    // Variabel untuk mode edit atau tambah baru
    let isEditMode = false;
    
    // Data jenis kontak
    const contactTypes = @json($availableTypes);
    
    // Inisialisasi sortable untuk drag & drop
    document.addEventListener('DOMContentLoaded', function() {
        const contactsList = document.getElementById('contactsList');
        if (contactsList) {
            new Sortable(contactsList, {
                handle: '.handle',
                animation: 150,
                ghostClass: 'bg-blue-50',
                onEnd: function() {
                    updateContactsOrder();
                }
            });
        }
        
        // Setup event listener untuk jenis kontak
        const contactTypeSelect = document.getElementById('contactType');
        contactTypeSelect.addEventListener('change', function() {
            updateContactValueUI(this.value);
        });
    });
    
    function updateContactValueUI(type) {
        const valueField = document.getElementById('contactValue');
        const prefixSpan = document.getElementById('valuePrefix');
        const helperText = document.getElementById('valueHelper');
        
        if (!contactTypes[type]) return;
        
        const prefix = contactTypes[type].prefix || '';
        
        // Update UI
        prefixSpan.textContent = prefix;
        valueField.style.paddingLeft = prefix ? (prefix.length * 8 + 16) + 'px' : '16px';
        
        // Update placeholder and helper text based on type
        switch(type) {
            case 'instagram':
                valueField.placeholder = 'username';
                helperText.textContent = 'Masukkan username Instagram tanpa @';
                break;
            case 'whatsapp':
                valueField.placeholder = '628123456789';
                helperText.textContent = 'Masukkan nomor WhatsApp dengan format 628xxx';
                break;
            case 'shopee':
            case 'tokopedia':
                valueField.placeholder = 'nama_toko';
                helperText.textContent = 'Masukkan nama toko di ' + contactTypes[type].name;
                break;
            case 'maps':
                valueField.placeholder = 'https://goo.gl/maps/xxxxx';
                helperText.textContent = 'Masukkan URL Google Maps lengkap';
                break;
            case 'website':
                valueField.placeholder = 'https://www.example.com';
                helperText.textContent = 'Masukkan URL website lengkap dengan https://';
                break;
            case 'custom':
                valueField.placeholder = 'https://www.example.com';
                helperText.textContent = 'Masukkan URL lengkap';
                break;
            default:
                valueField.placeholder = '';
                helperText.textContent = 'Masukkan nilai sesuai jenis kontak';
        }
    }
    
    function openContactModal(contactData = null) {
        const modal = document.getElementById('contactModal');
        const modalTitle = document.getElementById('contactModalTitle');
        const form = document.getElementById('contactForm');
        form.reset();
        clearErrors();
        
        if (contactData) {
            isEditMode = true;
            modalTitle.textContent = 'Edit Kontak';
            document.getElementById('contactId').value = contactData.id;
            document.getElementById('contactType').value = contactData.contact_type;
            document.getElementById('contactTitle').value = contactData.contact_title;
            document.getElementById('contactDescription').value = contactData.contact_description || '';
            document.getElementById('contactValue').value = contactData.contact_value;
            document.getElementById('isActive').checked = contactData.is_active;
            
            // Update UI for value field
            updateContactValueUI(contactData.contact_type);
        } else {
            isEditMode = false;
            modalTitle.textContent = 'Tambah Kontak Baru';
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeContactModal() {
        const modal = document.getElementById('contactModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        isEditMode = false;
    }
    
    function editContact(contactId) {
        // Tampilkan loading
        showToast('Memuat data kontak...', 'info');
        
        fetch(`{{ route('user.contacts.index') }}/${contactId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                openContactModal(data.contact);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat memuat data kontak', 'error');
        });
    }
    
    function deleteContact(contactId, contactTitle) {
        showConfirmation({
            title: 'Hapus Kontak?',
            text: `Apakah Anda yakin ingin menghapus kontak "${contactTitle}"?`,
            icon: 'warning',
            confirmButtonText: 'Ya, Hapus!'
        }, () => {
            // Tampilkan loading
            showToast('Menghapus kontak...', 'info');
            
            fetch(`{{ route('user.contacts.index') }}/${contactId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    // Hapus element dari DOM
                    const contactElement = document.querySelector(`[data-contact-id="${contactId}"]`);
                    if (contactElement) {
                        contactElement.remove();
                    }
                    
                    // Jika tidak ada kontak lagi, reload halaman
                    const contactsList = document.getElementById('contactsList');
                    if (contactsList && contactsList.children.length === 0) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menghapus kontak', 'error');
            });
        });
    }
    
    function toggleContactActive(contactId, setActive) {
        fetch(`{{ route('user.contacts.index') }}/${contactId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                
                // Update UI
                const contactElement = document.querySelector(`[data-contact-id="${contactId}"]`);
                if (contactElement) {
                    const iconElement = contactElement.querySelector('.fa-eye, .fa-eye-slash');
                    const buttonElement = iconElement.parentElement;
                    
                    if (data.is_active) {
                        contactElement.classList.remove('opacity-60');
                        iconElement.classList.replace('fa-eye', 'fa-eye-slash');
                        buttonElement.classList.replace('hover:text-green-500', 'hover:text-red-500');
                    } else {
                        contactElement.classList.add('opacity-60');
                        iconElement.classList.replace('fa-eye-slash', 'fa-eye');
                        buttonElement.classList.replace('hover:text-red-500', 'hover:text-green-500');
                    }
                }
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat mengubah status kontak', 'error');
        });
    }
    
    function updateContactsOrder() {
        const contactsList = document.getElementById('contactsList');
        const contacts = contactsList.querySelectorAll('.contact-card');
        
        const orderData = [];
        contacts.forEach((contact, index) => {
            orderData.push(contact.dataset.contactId);
        });
        
        fetch(`{{ route('user.contacts.order') }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ order: orderData })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat mengubah urutan kontak', 'error');
        });
    }
    
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = isEditMode 
            ? `{{ route('user.contacts.index') }}/${formData.get('contact_id')}`
            : `{{ route('user.contacts.index') }}`;
        const method = isEditMode ? 'PUT' : 'POST';
        
        // Tampilkan loading
        const submitBtn = document.getElementById('contactSubmitBtn');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        submitBtn.disabled = true;
        
        // Clear previous errors
        clearErrors();
        
        // Prepare data for JSON submission
        const jsonData = {};
        formData.forEach((value, key) => {
            if (key === 'is_active') {
                jsonData[key] = true;
            } else {
                jsonData[key] = value;
            }
        });
        
        // If is_active checkbox is unchecked, it won't be in the FormData
        if (!formData.has('is_active')) {
            jsonData['is_active'] = false;
        }
        
        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                closeContactModal();
                
                // Reload the page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(data.message, 'error');
                
                // Display validation errors if any
                if (data.errors) {
                    displayErrors(data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menyimpan kontak', 'error');
        })
        .finally(() => {
            // Reset button state
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
    });
    
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        document.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300', 'dark:border-gray-600');
        });
    }
    
    function displayErrors(errors) {
        for (const field in errors) {
            const inputField = document.getElementById(field.replace('_', ''));
            if (inputField) {
                inputField.classList.remove('border-gray-300', 'dark:border-gray-600');
                inputField.classList.add('border-red-500');
                
                const errorElement = inputField.parentElement.querySelector('.error-message') || 
                                      inputField.parentElement.parentElement.querySelector('.error-message');
                
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                    errorElement.classList.remove('hidden');
                }
            }
        }
    }
</script>
@endpush
/**
 * Exposia Utils - Reusable JavaScript utilities
 * A collection of reusable functions for the Exposia platform
 */
const ExposiaUtils = (function() {
    /**
     * Toast notification system
     */
    const toast = {
        show: function(message, type = 'info', duration = 5000) {
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
            
            return toast;
        }
    };

    /**
     * Form utilities for handling validation, errors, and loading states
     */
    const form = {
        clearErrors: function(formElement) {
            const errorMessages = formElement.querySelectorAll('.error-message');
            errorMessages.forEach(msg => {
                msg.classList.add('hidden');
                msg.textContent = '';
            });
            
            const inputs = formElement.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.classList.remove('border-red-500');
            });
        },
        
        displayErrors: function(errors, formElement) {
            Object.keys(errors).forEach(field => {
                const input = formElement.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('border-red-500');
                    const errorDiv = input.parentElement.querySelector('.error-message');
                    if (errorDiv) {
                        errorDiv.textContent = errors[field][0];
                        errorDiv.classList.remove('hidden');
                    }
                }
            });
        },
        
        setLoading: function(button, isLoading, originalHTML = null) {
            const btnHTML = originalHTML || button.innerHTML;
            
            if (isLoading) {
                button.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>Menyimpan...';
                button.disabled = true;
            } else {
                button.innerHTML = btnHTML;
                button.disabled = false;
            }
        },
        
        getCsrfToken: function() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        },
        
        setupCharCounter: function(inputId, counterId, maxLength = 160) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            
            if (!input || !counter) return;
            
            const updateCount = () => {
                const count = input.value.length;
                counter.textContent = `${count}/${maxLength}`;
                
                if (count > maxLength) {
                    counter.classList.add('text-red-500');
                } else {
                    counter.classList.remove('text-red-500');
                }
            };
            
            // Initial count
            updateCount();
            
            // Update on input
            input.addEventListener('input', updateCount);
        }
    };

    /**
     * Modal manager for handling modal dialogs
     */
    class ModalManager {
        constructor(modalId, options = {}) {
            this.modalId = modalId;
            this.options = {
                closeOnOutsideClick: true,
                escapeToClose: true,
                onOpen: null,
                onClose: null,
                ...options
            };
            
            this.init();
        }
        
        init() {
            const modal = document.getElementById(this.modalId);
            if (!modal) return;
            
            // Close on outside click
            if (this.options.closeOnOutsideClick) {
                const overlay = modal.querySelector('[aria-hidden="true"]');
                if (overlay) {
                    overlay.addEventListener('click', () => this.close());
                }
            }
            
            // Close on ESC key
            if (this.options.escapeToClose) {
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        this.close();
                    }
                });
            }
        }
        
        open(data = null) {
            const modal = document.getElementById(this.modalId);
            if (!modal) return;
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            if (this.options.onOpen) {
                this.options.onOpen(data);
            }
            
            return this;
        }
        
        close() {
            const modal = document.getElementById(this.modalId);
            if (!modal) return;
            
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            
            if (this.options.onClose) {
                this.options.onClose();
            }
            
            return this;
        }
    }

    /**
     * File upload handler for image uploads
     */
    class FileUploadHandler {
        constructor(options) {
            this.options = {
                dropzoneId: 'file-dropzone',
                inputId: 'file-input',
                placeholderId: null,
                progressId: null,
                previewContainerId: 'preview-container',
                previewImageId: 'preview-image',
                currentContainerId: null,
                maxSize: 2 * 1024 * 1024, // 2MB
                allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
                multiple: false,
                ...options
            };
            
            this.init();
        }
        
        init() {
            const dropzone = document.getElementById(this.options.dropzoneId);
            const fileInput = document.getElementById(this.options.inputId);
            
            if (!dropzone || !fileInput) return;
            
            // Click to upload
            dropzone.addEventListener('click', (e) => {
                if (e.target !== fileInput) {
                    fileInput.click();
                }
            });
            
            // File input change
            fileInput.addEventListener('change', (e) => {
                if (this.options.multiple) {
                    const files = Array.from(e.target.files);
                    if (files.length > 0) {
                        this.handleMultipleFiles(files);
                    }
                } else {
                    const file = e.target.files[0];
                    if (file && this.validateFile(file)) {
                        this.handleFileUpload(file);
                    }
                }
            });
            
            // Drag and drop events
            this.setupDragAndDrop(dropzone, fileInput);
        }
        
        setupDragAndDrop(dropzone, fileInput) {
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            });
            
            dropzone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                if (!dropzone.contains(e.relatedTarget)) {
                    dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                }
            });
            
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    if (this.options.multiple) {
                        this.handleMultipleFiles(Array.from(files));
                    } else {
                        const file = files[0];
                        if (this.validateFile(file)) {
                            fileInput.files = files;
                            this.handleFileUpload(file);
                        }
                    }
                }
            });
        }
        
        validateFile(file) {
            if (!this.options.allowedTypes.includes(file.type)) {
                toast.show('Format file tidak didukung. Silakan pilih gambar valid (JPG, PNG, WebP)', 'error');
                return false;
            }
            
            if (file.size > this.options.maxSize) {
                toast.show(`Ukuran file terlalu besar (maksimal ${this.options.maxSize / (1024 * 1024)}MB)`, 'error');
                return false;
            }
            
            return true;
        }
        
        handleFileUpload(file) {
            // Show progress if configured
            if (this.options.placeholderId && this.options.progressId) {
                document.getElementById(this.options.placeholderId).classList.add('hidden');
                document.getElementById(this.options.progressId).classList.remove('hidden');
            }
            
            // Hide current image if needed
            if (this.options.currentContainerId) {
                document.getElementById(this.options.currentContainerId).classList.add('hidden');
            }
            
            // Create preview
            const reader = new FileReader();
            reader.onload = (e) => {
                const previewContainer = document.getElementById(this.options.previewContainerId);
                const preview = document.getElementById(this.options.previewImageId);
                
                if (preview) {
                    preview.src = e.target.result;
                }
                
                // Show success state
                setTimeout(() => {
                    if (this.options.progressId) {
                        document.getElementById(this.options.progressId).classList.add('hidden');
                    }
                    if (previewContainer) {
                        previewContainer.classList.remove('hidden');
                    }
                    toast.show('Gambar siap untuk diupload!', 'success');
                }, 500);
                
                if (this.options.onPreviewReady) {
                    this.options.onPreviewReady(e.target.result);
                }
            };
            
            reader.readAsDataURL(file);
        }
        
        handleMultipleFiles(files) {
            // Filter valid files
            const validFiles = files.filter(file => this.validateFile(file));
            
            if (validFiles.length === 0) {
                return;
            }
            
            if (this.options.onMultipleFilesSelected) {
                this.options.onMultipleFilesSelected(validFiles);
            }
        }
        
        reset() {
            const fileInput = document.getElementById(this.options.inputId);
            if (fileInput) {
                fileInput.value = '';
            }
            
            if (this.options.placeholderId) {
                document.getElementById(this.options.placeholderId).classList.remove('hidden');
            }
            
            if (this.options.previewContainerId) {
                document.getElementById(this.options.previewContainerId).classList.add('hidden');
            }
            
            if (this.options.currentContainerId) {
                document.getElementById(this.options.currentContainerId).classList.remove('hidden');
            }
            
            if (this.options.onReset) {
                this.options.onReset();
            }
        }
    }

    /**
     * Helper function to copy text to clipboard
     */
    function copyToClipboard(text) {
        // If text is an element ID, get its value
        if (document.getElementById(text)) {
            const element = document.getElementById(text);
            if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                text = element.value;
            } else {
                text = element.textContent;
            }
            element.select && element.select();
        }
        
        // Use modern Clipboard API if available
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                toast.show('Teks berhasil disalin!', 'success', 2000);
            }).catch(() => {
                // Fallback to legacy method
                legacyCopy(text);
            });
        } else {
            // Use legacy method for older browsers
            legacyCopy(text);
        }
    }
    
    function legacyCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = 0;
        document.body.appendChild(textarea);
        textarea.select();
        
        try {
            document.execCommand('copy');
            toast.show('Teks berhasil disalin!', 'success', 2000);
        } catch (err) {
            toast.show('Gagal menyalin teks', 'error', 2000);
            console.error('Failed to copy text:', err);
        }
        
        document.body.removeChild(textarea);
    }

    /**
     * Helper function to format currency
     */
    function formatCurrency(amount) {
        if (!amount) return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    /**
     * Basic HTTP request with fetch API
     */
    const http = {
        get: async function(url, options = {}) {
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form.getCsrfToken(),
                        ...(options.headers || {})
                    },
                    ...options
                });
                
                return await response.json();
            } catch (error) {
                console.error('HTTP GET Error:', error);
                throw error;
            }
        },
        
        post: async function(url, data = {}, options = {}) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': form.getCsrfToken(),
                        ...(options.headers || {})
                    },
                    body: JSON.stringify(data),
                    ...options
                });
                
                return await response.json();
            } catch (error) {
                console.error('HTTP POST Error:', error);
                throw error;
            }
        },
        
        postForm: async function(url, formData, options = {}) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form.getCsrfToken(),
                        ...(options.headers || {})
                    },
                    body: formData,
                    ...options
                });
                
                return await response.json();
            } catch (error) {
                console.error('HTTP POST Form Error:', error);
                throw error;
            }
        },
        
        put: async function(url, data = {}, options = {}) {
            data._method = 'PUT'; // Laravel method spoofing
            return this.post(url, data, options);
        },
        
        delete: async function(url, options = {}) {
            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form.getCsrfToken(),
                        ...(options.headers || {})
                    },
                    ...options
                });
                
                return await response.json();
            } catch (error) {
                console.error('HTTP DELETE Error:', error);
                throw error;
            }
        }
    };

    // Confirm dialog helper
    function confirmAction(message, callback) {
        if (confirm(message)) {
            callback();
        }
    }

    // Dark mode toggle functionality
    function initDarkMode() {
        const toggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        
        if (!toggleBtn || !themeIcon) return;
        
        // Check for saved theme preference or system preference
        const savedTheme = localStorage.getItem('theme');
        const systemDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Set initial theme
        if (savedTheme === 'dark' || (!savedTheme && systemDarkMode)) {
            document.documentElement.classList.add('dark');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        } else {
            document.documentElement.classList.remove('dark');
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        }
        
        // Toggle theme on click
        toggleBtn.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        });
    }

    // Initialize sidebar and mobile menu functionality
    function initSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobile-menu-button');
        const closeSidebarBtn = document.getElementById('close-sidebar');
        
        if (!sidebar || !mobileMenuBtn || !closeSidebarBtn) return;
        
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
        });
        
        closeSidebarBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });
    }

    // Initialize dropdown menu
    function initDropdowns() {
        const userProfileBtn = document.getElementById('user-profile-button');
        const profileDropdown = document.getElementById('profile-dropdown');
        const dropdownArrow = document.getElementById('dropdown-arrow');
        
        if (!userProfileBtn || !profileDropdown || !dropdownArrow) return;
        
        function toggleProfileDropdown() {
            profileDropdown.classList.toggle('hidden');
            profileDropdown.classList.toggle('show');
            dropdownArrow.classList.toggle('transform');
            dropdownArrow.classList.toggle('rotate-180');
        }
        
        userProfileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleProfileDropdown();
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!userProfileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
                profileDropdown.classList.remove('show');
                dropdownArrow.classList.remove('rotate-180');
            }
        });
        
        // Expose the function for external use
        window.toggleProfileDropdown = toggleProfileDropdown;
    }

    // Logout functionality
    function initLogout() {
        window.logout = function() {
            confirmAction('Apakah Anda yakin ingin keluar?', () => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = form.getCsrfToken();
                
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            });
        };
    }

    // Initialize common functionality on page load
    function initCommon() {
        initDarkMode();
        initSidebar();
        initDropdowns();
        initLogout();
    }

    // Return public API
    return {
        toast,
        form,
        modal: {
            create: function(modalId, options) {
                return new ModalManager(modalId, options);
            }
        },
        fileUpload: {
            create: function(options) {
                return new FileUploadHandler(options);
            }
        },
        copyToClipboard,
        formatCurrency,
        http,
        confirmAction,
        initCommon
    };
})();

// Initialize common functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    ExposiaUtils.initCommon();
});
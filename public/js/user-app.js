/* ===================================
   USER DASHBOARD - GENERAL JAVASCRIPT
   Reusable components for user dashboard
   =================================== */

// ===================================
// GLOBAL NOTIFICATION MANAGER
// ===================================
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.container = null;
        this.createContainer();
    }

    createContainer() {
        this.container = document.createElement('div');
        this.container.id = 'notification-container';
        this.container.className = 'fixed top-4 right-4 z-[9999] space-y-2';
        this.container.style.pointerEvents = 'none';
        document.body.appendChild(this.container);
    }

    show(message, type = 'info', duration = 5000) {
        const notification = this.createNotification(message, type);
        this.container.appendChild(notification);
        this.notifications.push(notification);

        notification.style.pointerEvents = 'auto';

        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
            notification.classList.add('translate-x-0', 'opacity-100');
        }, 10);

        if (duration > 0) {
            setTimeout(() => {
                this.remove(notification);
            }, duration);
        }

        return notification;
    }

    createNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification-item transform translate-x-full opacity-0 transition-all duration-300 ease-out max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden`;

        const bgColor = this.getBackgroundColor(type);
        const icon = this.getIcon(type);
        const textColor = type === 'warning' ? 'text-yellow-800 dark:text-yellow-200' : 'text-white';

        notification.innerHTML = `
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 ${bgColor} rounded-full flex items-center justify-center">
                            <i class="${icon} ${textColor} text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            ${message}
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="bg-white dark:bg-gray-800 rounded-md inline-flex text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none transition-colors duration-200" onclick="window.notificationManager.remove(this.closest('.notification-item'))">
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3">
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    ${new Date().toLocaleTimeString()}
                </div>
            </div>
        `;

        return notification;
    }

    getBackgroundColor(type) {
        switch (type) {
            case 'success': return 'bg-green-500';
            case 'error': return 'bg-red-500';
            case 'warning': return 'bg-yellow-500';
            default: return 'bg-blue-500';
        }
    }

    getIcon(type) {
        switch (type) {
            case 'success': return 'fas fa-check-circle';
            case 'error': return 'fas fa-exclamation-circle';
            case 'warning': return 'fas fa-exclamation-triangle';
            default: return 'fas fa-info-circle';
        }
    }

    remove(notification) {
        if (!notification || !notification.parentNode) return;

        notification.classList.remove('translate-x-0', 'opacity-100');
        notification.classList.add('translate-x-full', 'opacity-0');

        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }

            const index = this.notifications.indexOf(notification);
            if (index > -1) {
                this.notifications.splice(index, 1);
            }
        }, 300);
    }

    clear() {
        this.notifications.forEach(notification => {
            this.remove(notification);
        });
    }
}

// ===================================
// THEME MANAGER
// ===================================
class ThemeManager {
    constructor() {
        this.currentTheme = 'light';
        this.init();
    }

    init() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        this.setTheme(savedTheme);

        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }

    setTheme(theme) {
        this.currentTheme = theme;
        const html = document.documentElement;
        const themeIcon = document.getElementById('theme-icon');
        
        if (theme === 'dark') {
            html.classList.add('dark');
            if (themeIcon) {
                themeIcon.className = 'fas fa-sun text-yellow-400';
            }
        } else {
            html.classList.remove('dark');
            if (themeIcon) {
                themeIcon.className = 'fas fa-moon text-gray-600';
            }
        }
        
        localStorage.setItem('theme', theme);
        
        // Trigger theme change event for other components
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    }

    toggleTheme() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.setTheme(newTheme);
    }
}

// ===================================
// SIDEBAR MANAGER
// ===================================
class SidebarManager {
    constructor() {
        this.sidebarOpen = false;
        this.initializeSidebar();
    }

    initializeSidebar() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeSidebar = document.getElementById('close-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.openSidebar();
            });
        }

        if (closeSidebar) {
            closeSidebar.addEventListener('click', (e) => {
                e.preventDefault();
                this.closeSidebar();
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                this.closeSidebar();
            });
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                this.closeSidebar();
            }
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.sidebarOpen) {
                this.closeSidebar();
            }
        });

        // Touch support for mobile
        this.initializeTouchSupport();
    }

    openSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (sidebar && overlay) {
            this.sidebarOpen = true;
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('show');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Add touch prevention to background
            document.addEventListener('touchmove', this.preventBackgroundScroll, { passive: false });
        }
    }

    closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (sidebar && overlay) {
            this.sidebarOpen = false;
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('show');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';

            // Remove touch prevention
            document.removeEventListener('touchmove', this.preventBackgroundScroll);
        }
    }

    preventBackgroundScroll(e) {
        e.preventDefault();
    }

    initializeTouchSupport() {
        // Add touch support for better mobile interaction
        const touchElements = document.querySelectorAll('.nav-item, .dropdown-item');

        touchElements.forEach(element => {
            element.addEventListener('touchstart', function () {
                this.style.transform = 'scale(0.95)';
            }, { passive: true });

            element.addEventListener('touchend', function () {
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            }, { passive: true });
        });
    }
}

// ===================================
// DROPDOWN MANAGER
// ===================================
class DropdownManager {
    constructor() {
        this.initializeDropdowns();
    }

    initializeDropdowns() {
        document.addEventListener('click', (event) => {
            const profileDropdown = document.getElementById('profile-dropdown');
            const profileButton = document.getElementById('user-profile-button');
            const notificationDropdown = document.getElementById('notification-dropdown');
            const notificationButton = document.getElementById('notification-btn');
            const quickActionsDropdown = document.getElementById('quick-actions-dropdown');
            const quickActionsButton = document.getElementById('quick-actions-btn');

            // Close profile dropdown if clicked outside
            if (profileDropdown && profileButton) {
                if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                    closeProfileDropdown();
                }
            }

            // Close notification dropdown if clicked outside
            if (notificationDropdown && notificationButton) {
                if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
                    closeNotificationDropdown();
                }
            }

            // Close quick actions dropdown if clicked outside
            if (quickActionsDropdown && quickActionsButton) {
                if (!quickActionsButton.contains(event.target) && !quickActionsDropdown.contains(event.target)) {
                    closeQuickActionsDropdown();
                }
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeProfileDropdown();
                closeNotificationDropdown();
                closeQuickActionsDropdown();
            }
        });
    }
}

// ===================================
// PROGRESS TRACKER
// ===================================
class ProgressTracker {
    constructor() {
        this.updateProgress();
    }

    updateProgress() {
        // Animate progress bars on page load
        setTimeout(() => {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        }, 500);
    }

    calculateBusinessProgress(business) {
        let progress = 0;
        const steps = [
            { check: business.business_name, points: 20 },
            { check: business.main_address, points: 15 },
            { check: business.short_description, points: 15 },
            { check: business.products_count > 0, points: 25 },
            { check: business.galleries_count > 0, points: 15 },
            { check: business.publish_status, points: 10 }
        ];

        steps.forEach(step => {
            if (step.check) {
                progress += step.points;
            }
        });

        return Math.min(progress, 100);
    }
}

// ===================================
// GLOBAL FUNCTIONS (for onclick handlers)
// ===================================

// Profile dropdown functions
function toggleProfileDropdown() {
    const dropdown = document.getElementById('profile-dropdown');
    const arrow = document.getElementById('dropdown-arrow');
    const isMobile = window.innerWidth <= 768;

    if (dropdown) {
        if (dropdown.classList.contains('hidden')) {
            closeNotificationDropdown();
            closeQuickActionsDropdown();

            dropdown.classList.remove('hidden');

            if (isMobile) {
                document.body.style.overflow = 'hidden';
            }

            setTimeout(() => {
                dropdown.classList.remove('opacity-0', 'scale-95');
                dropdown.classList.add('opacity-100', 'scale-100');
            }, 10);

            if (arrow) {
                arrow.style.transform = 'rotate(180deg)';
            }
        } else {
            closeProfileDropdown();
        }
    }
}

function closeProfileDropdown() {
    const dropdown = document.getElementById('profile-dropdown');
    const arrow = document.getElementById('dropdown-arrow');

    if (dropdown && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('opacity-0', 'scale-95');
        dropdown.classList.remove('opacity-100', 'scale-100');

        document.body.style.overflow = '';

        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 200);

        if (arrow) {
            arrow.style.transform = 'rotate(0deg)';
        }
    }
}

// Notification dropdown functions
function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notification-dropdown');
    const isMobile = window.innerWidth <= 768;

    if (dropdown) {
        if (dropdown.classList.contains('hidden')) {
            closeProfileDropdown();
            closeQuickActionsDropdown();

            dropdown.classList.remove('hidden');

            if (isMobile) {
                document.body.style.overflow = 'hidden';
            }

            setTimeout(() => {
                dropdown.classList.remove('opacity-0', 'scale-95');
                dropdown.classList.add('opacity-100', 'scale-100');
            }, 10);
        } else {
            closeNotificationDropdown();
        }
    }
}

function closeNotificationDropdown() {
    const dropdown = document.getElementById('notification-dropdown');

    if (dropdown && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('opacity-0', 'scale-95');
        dropdown.classList.remove('opacity-100', 'scale-100');

        document.body.style.overflow = '';

        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 200);
    }
}

// Quick Actions dropdown functions
function toggleQuickActionsDropdown() {
    const dropdown = document.getElementById('quick-actions-dropdown');
    const isMobile = window.innerWidth <= 768;

    if (dropdown) {
        if (dropdown.classList.contains('hidden')) {
            closeProfileDropdown();
            closeNotificationDropdown();

            dropdown.classList.remove('hidden');

            if (isMobile) {
                document.body.style.overflow = 'hidden';
            }

            setTimeout(() => {
                dropdown.classList.remove('opacity-0', 'scale-95');
                dropdown.classList.add('opacity-100', 'scale-100');
            }, 10);
        } else {
            closeQuickActionsDropdown();
        }
    }
}

function closeQuickActionsDropdown() {
    const dropdown = document.getElementById('quick-actions-dropdown');

    if (dropdown && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('opacity-0', 'scale-95');
        dropdown.classList.remove('opacity-100', 'scale-100');

        document.body.style.overflow = '';

        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 200);
    }
}

// Notification actions
function markAllAsRead() {
    console.log('Marking all notifications as read');

    const badge = document.getElementById('notification-badge');
    const countBadge = document.getElementById('notification-count');

    if (badge) badge.style.display = 'none';
    if (countBadge) countBadge.classList.add('hidden');

    const notificationItems = document.querySelectorAll('#notification-list > div');
    notificationItems.forEach(item => {
        item.classList.remove('border-blue-500', 'bg-blue-50');
        item.classList.add('border-transparent');

        const unreadDot = item.querySelector('.bg-blue-500.rounded-full');
        if (unreadDot && unreadDot.classList.contains('w-2')) {
            unreadDot.remove();
        }
    });

    if (window.notificationManager) {
        window.notificationManager.show('All notifications marked as read', 'success');
    }
}

function viewAllNotifications() {
    closeNotificationDropdown();
    if (window.notificationManager) {
        window.notificationManager.show('Redirecting to notifications page...', 'info');
    }
    console.log('Viewing all notifications');
}

// Logout function
function logout() {
    closeProfileDropdown();
    closeNotificationDropdown();
    closeQuickActionsDropdown();

    if (confirm('Are you sure you want to logout?')) {
        const loadingNotification = window.notificationManager ? 
            window.notificationManager.show('Logging out...', 'info', 0) : null;

        // For Laravel, we would submit a logout form
        const logoutForm = document.createElement('form');
        logoutForm.method = 'POST';
        logoutForm.action = '/logout'; // Laravel logout route
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.content;
            logoutForm.appendChild(csrfInput);
        }

        document.body.appendChild(logoutForm);
        logoutForm.submit();
    }
}

// ===================================
// UTILITY FUNCTIONS
// ===================================
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function formatDate(date) {
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    }).format(new Date(date));
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function generateSlug(text) {
    return text
        .toLowerCase()
        .replace(/[^\w\s-]/g, '') // Remove special characters
        .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
        .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
}

function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            if (window.notificationManager) {
                window.notificationManager.show('Copied to clipboard!', 'success');
            }
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        if (window.notificationManager) {
            window.notificationManager.show('Copied to clipboard!', 'success');
        }
    }
}

// Mobile detection utilities
function isMobile() {
    return window.innerWidth <= 768;
}

function isTablet() {
    return window.innerWidth > 768 && window.innerWidth <= 1024;
}

function isTouchDevice() {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}

// ===================================
// AJAX HELPERS FOR LARAVEL
// ===================================
function makeRequest(url, options = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    if (csrfToken) {
        defaultOptions.headers['X-CSRF-TOKEN'] = csrfToken.content;
    }

    return fetch(url, { ...defaultOptions, ...options });
}

function handleFormSubmit(form, onSuccess, onError) {
    const formData = new FormData(form);
    const url = form.action;
    const method = form.method;

    makeRequest(url, {
        method: method,
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.status === 'success') {
            if (onSuccess) onSuccess(data);
            if (window.notificationManager) {
                window.notificationManager.show(data.message || 'Operation successful!', 'success');
            }
        } else {
            throw new Error(data.message || 'Operation failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (onError) onError(error);
        if (window.notificationManager) {
            window.notificationManager.show(error.message || 'An error occurred', 'error');
        }
    });
}

// ===================================
// INITIALIZATION
// ===================================
document.addEventListener('DOMContentLoaded', () => {
    // Initialize global managers
    window.notificationManager = new NotificationManager();
    window.themeManager = new ThemeManager();
    window.sidebarManager = new SidebarManager();
    window.dropdownManager = new DropdownManager();
    window.progressTracker = new ProgressTracker();

    // Global event listeners
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeNotificationDropdown();
            closeProfileDropdown();
            closeQuickActionsDropdown();
        }
    });

    // Initialize tooltips (if using a tooltip library)
    // initializeTooltips();

    // Initialize form validation
    // initializeFormValidation();

    console.log('User Dashboard - General components initialized');
});
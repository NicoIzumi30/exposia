/* ===================================
   ADMIN DASHBOARD - GENERAL JAVASCRIPT ONLY
   Page-specific logic moved to respective pages
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
        const touchElements = document.querySelectorAll('.modern-btn, .btn-icon, .nav-item, .dropdown-item');

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

            if (profileDropdown && profileButton) {
                if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                    closeProfileDropdown();
                }
            }

            if (notificationDropdown && notificationButton) {
                if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
                    closeNotificationDropdown();
                }
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeProfileDropdown();
                closeNotificationDropdown();
            }
        });
    }
}

// ===================================
// USER INFO MANAGER
// ===================================
class UserInfoManager {
    constructor() {
        this.updateUserInfo();
    }

    updateUserInfo() {
        const userEmail = localStorage.getItem('userEmail');
        if (userEmail) {
            const userName = userEmail.split('@')[0] || 'Admin User';
            const userNameElements = document.querySelectorAll('#user-name, #dropdown-user-name');
            const userEmailElements = document.querySelectorAll('#dropdown-user-email');
            
            userNameElements.forEach(el => {
                if (el) el.textContent = userName;
            });
            
            userEmailElements.forEach(el => {
                if (el) el.textContent = userEmail;
            });
        }
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

    if (confirm('Are you sure you want to logout?')) {
        const loadingNotification = window.notificationManager ? 
            window.notificationManager.show('Logging out...', 'info', 0) : null;

        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('userEmail');
        localStorage.removeItem('loginMethod');

        setTimeout(() => {
            if (loadingNotification && window.notificationManager) {
                window.notificationManager.remove(loadingNotification);
                window.notificationManager.show('Logged out successfully!', 'success', 2000);
            }

            setTimeout(() => {
                window.location.href = 'login.html';
            }, 1000);
        }, 1000);
    }
}

// ===================================
// UTILITY FUNCTIONS
// ===================================
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

function formatDate(date) {
    return new Intl.DateTimeFormat('en-US', {
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
// INITIALIZATION
// ===================================
document.addEventListener('DOMContentLoaded', () => {
    // Initialize global managers
    window.notificationManager = new NotificationManager();
    window.themeManager = new ThemeManager();
    window.sidebarManager = new SidebarManager();
    window.dropdownManager = new DropdownManager();
    window.userInfoManager = new UserInfoManager();

    // Global event listeners
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeNotificationDropdown();
            closeProfileDropdown();
        }
    });

    console.log('Admin Dashboard - General components initialized');
});
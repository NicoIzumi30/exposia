class ToastNotification {
    constructor() {
        this.container = null;
        this.notifications = [];
        this.init();
    }

    init() {
        if (!document.getElementById('toast-container')) {
            this.createContainer();
        } else {
            this.container = document.getElementById('toast-container');
        }
    }

    createContainer() {
        this.container = document.createElement('div');
        this.container.id = 'toast-container';
        this.container.className = 'fixed top-4 right-4 z-50 space-y-3 max-w-sm';
        document.body.appendChild(this.container);
    }

    show(message, type = 'info', duration = 5000) {
        const toast = this.createToast(message, type, duration);
        this.container.appendChild(toast);

        this.notifications.push(toast);

        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 10);

        if (duration > 0) {
            setTimeout(() => {
                this.dismiss(toast);
            }, duration);
        }

        return toast;
    }

    createToast(message, type, duration) {
        const toast = document.createElement('div');
        const config = this.getTypeConfig(type);

        toast.className = `
            transform translate-x-full opacity-0 transition-all duration-300 ease-in-out
            bg-white dark:bg-gray-800 border-l-4 rounded-lg shadow-xl overflow-hidden
            max-w-sm w-full ${config.borderColor} backdrop-blur-sm
        `;

        toast.innerHTML = `
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center ${config.bgColor}">
                            <i class="${config.icon} text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    ${config.title}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                    ${message}
                                </p>
                            </div>
                            <button 
                                type="button" 
                                class="ml-4 inline-flex text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none transition-colors duration-200"
                                onclick="window.toast.dismiss(this.closest('.transform'))"
                            >
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
                ${duration > 0 ? this.createProgressBar(duration, config.bgColor) : ''}
            </div>
        `;

        toast.id = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
        return toast;
    }

    createProgressBar(duration, bgColor) {
        return `
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gray-200 dark:bg-gray-700">
                <div class="h-full ${bgColor} transition-all ease-linear" 
                     style="width: 100%; animation: progress ${duration}ms linear forwards;">
                </div>
            </div>
        `;
    }

    getTypeConfig(type) {
        const configs = {
            success: {
                title: 'Success!'
                , icon: 'fas fa-check'
                , bgColor: 'bg-green-500'
                , borderColor: 'border-green-500'
            }
            , error: {
                title: 'Error'
                , icon: 'fas fa-exclamation-triangle'
                , bgColor: 'bg-red-500'
                , borderColor: 'border-red-500'
            }
            , warning: {
                title: 'Warning'
                , icon: 'fas fa-exclamation'
                , bgColor: 'bg-yellow-500'
                , borderColor: 'border-yellow-500'
            }
            , info: {
                title: 'Info'
                , icon: 'fas fa-info'
                , bgColor: 'bg-blue-500'
                , borderColor: 'border-blue-500'
            }
        };

        return configs[type] || configs.info;
    }

    dismiss(toast) {
        if (!toast || !toast.parentNode) return;

        toast.classList.remove('translate-x-0', 'opacity-100');
        toast.classList.add('translate-x-full', 'opacity-0');

        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
            this.notifications = this.notifications.filter(n => n !== toast);
        }, 300);
    }

    success(message, duration = 5000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 7000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 6000) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 5000) {
        return this.show(message, 'info', duration);
    }
}

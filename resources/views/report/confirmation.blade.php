@extends('layouts.public')

@section('title', 'Laporan Terkirim')

@section('content')
<div class="relative min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 via-transparent to-accent-400/10"></div>
    
    <!-- Success Animation Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-32 h-32 bg-green-400/10 rounded-full top-[20%] left-[10%] animate-pulse"></div>
        <div class="absolute w-24 h-24 bg-accent-400/10 rounded-full top-[60%] right-[15%] animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute w-40 h-40 bg-green-300/5 rounded-full bottom-[10%] left-[20%] animate-pulse" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="relative w-full max-w-2xl mx-auto">
        <!-- Success Card -->
        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl border border-gray-200 dark:border-gray-700 overflow-hidden backdrop-blur-sm bg-white/95 dark:bg-gray-800/95 animate-scale-in">
            
            <!-- Success Header -->
            <div class="bg-gradient-to-r from-green-400 to-green-600 p-8 text-center relative overflow-hidden">
                <!-- Decorative elements -->
                <div class="absolute inset-0 bg-white/10 transform rotate-12 scale-150"></div>
                <div class="absolute inset-0 bg-white/5 transform -rotate-12 scale-150"></div>
                
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6 backdrop-blur-sm animate-bounce">
                        <span class="material-icons-outlined text-white text-4xl">check_circle</span>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-3">
                        Laporan Berhasil Dikirim!
                    </h2>
                    <p class="text-green-50 text-lg">
                        Terima kasih telah membantu kami menjaga kualitas platform
                    </p>
                </div>
            </div>
            
            <!-- Content Section -->
            <div class="p-8 space-y-8">
                <!-- Report Code Display -->
                <div class="bg-gradient-to-br from-accent-50 to-accent-100 dark:from-accent-900/20 dark:to-accent-800/20 rounded-2xl p-6 border border-accent-200 dark:border-accent-700/50">
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-4">
                            <span class="material-icons-outlined text-accent-500 text-3xl mr-3">confirmation_number</span>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                Kode Laporan Anda
                            </h3>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-700 rounded-xl p-4 mb-4 border-2 border-dashed border-accent-300 dark:border-accent-600">
                            <div class="flex items-center justify-center space-x-3">
                                <span class="text-2xl font-mono font-bold text-accent-600 dark:text-accent-400 tracking-wider">
                                    {{ $report->report_code }}
                                </span>
                                <button type="button" 
                                        onclick="copyToClipboard('{{ $report->report_code }}')" 
                                        class="p-2 text-gray-400 dark:text-gray-500 hover:text-accent-500 dark:hover:text-accent-400 focus:outline-none transition-colors duration-200 transform hover:scale-110">
                                    <span class="material-icons-outlined">content_copy</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-center text-sm text-gray-600 dark:text-gray-400">
                            <span class="material-icons-outlined text-sm mr-2">info</span>
                            Simpan kode ini untuk menanyakan status laporan Anda
                        </div>
                    </div>
                </div>
                
                <!-- Process Information -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                                <span class="material-icons-outlined text-blue-600 dark:text-blue-400">schedule</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-400 mb-2">
                                Proses Selanjutnya
                            </h4>
                            <p class="text-blue-800 dark:text-blue-300 text-sm leading-relaxed">
                                Tim moderasi kami akan meninjau laporan Anda dalam waktu <strong>1-3 hari kerja</strong>. 
                                Anda akan mendapat notifikasi melalui kode laporan ini ketika proses selesai.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="/" 
                           class="group inline-flex items-center justify-center px-6 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-300 hover:border-gray-400 dark:hover:border-gray-500 transform hover:scale-105">
                            <span class="material-icons-outlined mr-3 group-hover:scale-110 transition-transform duration-200">home</span>
                            <span class="font-semibold">Kembali ke Beranda</span>
                        </a>
                        
                        <a href="{{ route('report.check') }}" 
                           class="group inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-accent-400 to-accent-600 hover:from-accent-500 hover:to-accent-700 text-white rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <span class="material-icons-outlined mr-3 group-hover:scale-110 transition-transform duration-200">search</span>
                            <span class="font-semibold">Cek Status Laporan</span>
                        </a>
                    </div>
                    
                    <div class="text-center pt-4">
                        <a href="{{ route('report.create') }}" 
                           class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm font-medium transition-colors duration-200">
                            <span class="material-icons-outlined mr-2 text-sm">add_circle_outline</span>
                            Laporkan website lain
                        </a>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6 border border-gray-200 dark:border-gray-600">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <span class="material-icons-outlined mr-2 text-accent-500">lightbulb</span>
                        Tahukah Anda?
                    </h4>
                    <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-start space-x-3">
                            <span class="material-icons-outlined text-xs mt-1 text-green-500">check</span>
                            <p>Laporan Anda membantu menjaga ekosistem internet yang aman dan berkualitas</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="material-icons-outlined text-xs mt-1 text-green-500">check</span>
                            <p>Setiap laporan akan ditinjau oleh tim ahli dengan standar yang ketat</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="material-icons-outlined text-xs mt-1 text-green-500">check</span>
                            <p>Identitas pelapor dijaga kerahasiaannya sesuai kebijakan privasi kami</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Floating Action -->
        <div class="absolute -bottom-6 left-1/2 transform -translate-x-1/2">
            <div class="bg-white dark:bg-gray-800 rounded-full p-3 shadow-lg border border-gray-200 dark:border-gray-700 animate-bounce">
                <span class="material-icons-outlined text-accent-500 text-xl">keyboard_arrow_down</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(text) {
        // Modern clipboard API
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                showCopySuccess();
            }, function() {
                fallbackCopyTextToClipboard(text);
            });
        } else {
            fallbackCopyTextToClipboard(text);
        }
    }
    
    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showCopySuccess();
            } else {
                showCopyError();
            }
        } catch (err) {
            showCopyError();
        }
        
        document.body.removeChild(textArea);
    }
    
    function showCopySuccess() {
        window.showToast('Kode laporan berhasil disalin!', 'success', 3000, {
            title: 'Berhasil'
        });
        
        // Visual feedback on button
        const button = event.target.closest('button');
        const originalIcon = button.innerHTML;
        button.innerHTML = '<span class="material-icons-outlined text-green-500">check</span>';
        button.classList.add('bg-green-50', 'dark:bg-green-900/20');
        
        setTimeout(() => {
            button.innerHTML = originalIcon;
            button.classList.remove('bg-green-50', 'dark:bg-green-900/20');
        }, 2000);
    }
    
    function showCopyError() {
        window.showToast('Gagal menyalin kode laporan. Silakan salin secara manual.', 'error', 5000, {
            title: 'Gagal'
        });
    }
    
    // Auto-focus copy button on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add entrance animation delay for elements
        const animatedElements = document.querySelectorAll('[class*="animate-"]');
        animatedElements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
        });
        
        // Add confetti effect (optional)
        setTimeout(() => {
            createConfetti();
        }, 1000);
    });
    
    function createConfetti() {
        const colors = ['#fcab1b', '#f59e0b', '#10b981', '#6366f1'];
        const confettiCount = 50;
        
        for (let i = 0; i < confettiCount; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.width = '6px';
                confetti.style.height = '6px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-10px';
                confetti.style.zIndex = '9999';
                confetti.style.borderRadius = '50%';
                confetti.style.pointerEvents = 'none';
                confetti.style.animation = `confetti-fall ${2 + Math.random() * 3}s linear forwards`;
                
                document.body.appendChild(confetti);
                
                setTimeout(() => {
                    confetti.remove();
                }, 5000);
            }, i * 50);
        }
    }
    
    // Add confetti animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
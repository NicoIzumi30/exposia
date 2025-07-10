/**
 * Enhanced AI Chat Widget with Monochrome Theme
 * @description Advanced chat widget with improved UX and responsive design
 * @version 2.0.0
 * @author Exposia Platform
 */

class AIChatWidget {
    constructor(config) {
        this.config = {
            businessSlug: config.businessSlug,
            csrfToken: config.csrfToken,
            whatsappNumber: config.whatsappNumber,
            businessName: config.businessName,
            apiEndpoints: config.apiEndpoints,
            debug: config.debug || false,
            maxMessages: config.maxMessages || 50,
            typingDelay: config.typingDelay || 1000,
            ...config
        };
        
        this.state = {
            session: null,
            businessInfo: null,
            isOpen: false,
            isLoading: false,
            messageCount: 0,
            messages: [],
            isInitialized: false,
            thinkingMessageId: null
        };
        
        this.elements = {};
        this.eventListeners = [];
        
        this.init();
    }

    /**
     * Initialize the chat widget
     */
    async init() {
        try {
            this.log('Initializing Enhanced AI Chat Widget...');
            
            await this.initializeElements();
            await this.loadBusinessInfo();
            this.setupEventListeners();
            this.generateQuickActions();
            this.setupKeyboardShortcuts();
            
            this.state.isInitialized = true;
            this.log('Enhanced AI Chat Widget initialized successfully');
            
        } catch (error) {
            this.error('Failed to initialize chat widget:', error);
            this.showFallbackWelcome();
        }
    }

    /**
     * Initialize DOM elements
     */
    async initializeElements() {
        const elementIds = [
            'aiChatWidget', 'chatToggleBtn', 'chatInterface', 'chatIcon',
            'chatMessages', 'chatInput', 'sendBtn', 'chatCloseBtn',
            'chatLoading', 'chatError', 'chatErrorMessage', 'welcomeMessage',
            'quickActions'
        ];
        
        elementIds.forEach(id => {
            this.elements[id] = document.getElementById(id);
            if (!this.elements[id]) {
                this.warn(`Element with ID '${id}' not found`);
            }
        });
        
        // Validate critical elements
        const criticalElements = ['chatToggleBtn', 'chatInterface', 'chatMessages'];
        const missingElements = criticalElements.filter(id => !this.elements[id]);
        
        if (missingElements.length > 0) {
            throw new Error(`Critical elements missing: ${missingElements.join(', ')}`);
        }
    }

    /**
     * Load business information from API
     */
    async loadBusinessInfo() {
        try {
            const response = await this.apiRequest('GET', this.config.apiEndpoints.chatInfo);
            
            if (response.success) {
                this.state.businessInfo = response.business;
                this.displayWelcomeMessage(response.welcome_message);
                this.log('Business info loaded:', response.business);
            } else {
                throw new Error(response.message || 'Failed to load business info');
            }
            
        } catch (error) {
            this.error('Failed to load business info:', error);
            this.showFallbackWelcome();
        }
    }

    /**
     * Setup enhanced event listeners
     */
    setupEventListeners() {
        // Chat toggle
        this.addEventListener(this.elements.chatToggleBtn, 'click', () => this.toggleChat());
        this.addEventListener(this.elements.chatCloseBtn, 'click', () => this.closeChat());
        
        // Chat input
        this.addEventListener(this.elements.chatInput, 'keypress', (e) => this.handleKeyPress(e));
        this.addEventListener(this.elements.chatInput, 'input', (e) => this.handleInputChange(e));
        this.addEventListener(this.elements.chatInput, 'focus', () => this.handleInputFocus());
        this.addEventListener(this.elements.chatInput, 'blur', () => this.handleInputBlur());
        
        // Send button
        this.addEventListener(this.elements.sendBtn, 'click', () => this.sendMessage());
        
        // Global events
        this.addEventListener(document, 'keydown', (e) => this.handleGlobalKeydown(e));
        this.addEventListener(document, 'visibilitychange', () => this.handleVisibilityChange());
        this.addEventListener(window, 'resize', () => this.handleResize());
        
        // Touch events for mobile
        if ('ontouchstart' in window) {
            this.setupTouchEvents();
        }
        
        this.log('Enhanced event listeners setup complete');
    }

    /**
     * Setup touch events for mobile
     */
    setupTouchEvents() {
        let touchStartY = 0;
        
        this.addEventListener(this.elements.chatInterface, 'touchstart', (e) => {
            touchStartY = e.touches[0].clientY;
        });
        
        this.addEventListener(this.elements.chatInterface, 'touchmove', (e) => {
            const touchY = e.touches[0].clientY;
            const deltaY = touchY - touchStartY;
            
            // Prevent scrolling when at top/bottom
            if (deltaY > 0 && this.elements.chatMessages.scrollTop === 0) {
                e.preventDefault();
            }
        });
    }

    /**
     * Setup keyboard shortcuts
     */
    setupKeyboardShortcuts() {
        this.addEventListener(document, 'keydown', (e) => {
            // Ctrl/Cmd + M to toggle chat
            if ((e.ctrlKey || e.metaKey) && e.key === 'm') {
                e.preventDefault();
                this.toggleChat();
            }
            
            // Ctrl/Cmd + Shift + C to clear chat
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'C') {
                e.preventDefault();
                this.clearHistory();
            }
        });
    }

    /**
     * Handle window resize
     */
    handleResize() {
        if (this.state.isOpen) {
            this.adjustChatPosition();
        }
    }

    /**
     * Adjust chat position based on screen size
     */
    adjustChatPosition() {
        const isMobile = window.innerWidth < 640;
        const isTablet = window.innerWidth >= 640 && window.innerWidth < 768;
        
        if (isMobile && this.elements.chatInterface) {
            this.elements.chatInterface.style.height = '85vh';
        } else if (isTablet && this.elements.chatInterface) {
            this.elements.chatInterface.style.height = '500px';
        }
    }

    /**
     * Enhanced input handling
     */
    handleInputFocus() {
        if (this.elements.chatInput) {
            this.elements.chatInput.parentElement.classList.add('focused');
        }
    }

    handleInputBlur() {
        if (this.elements.chatInput) {
            this.elements.chatInput.parentElement.classList.remove('focused');
        }
    }

    /**
     * Enhanced keyboard handling
     */
    handleKeyPress(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            this.sendMessage();
        } else if (event.key === 'Enter' && event.shiftKey) {
            // Allow line break with Shift+Enter
            return;
        }
    }

    /**
     * Enhanced input change handling
     */
    handleInputChange(event) {
        const hasContent = event.target.value.trim().length > 0;
        this.updateSendButton(hasContent && !this.state.isLoading);
        
        // Auto-resize textarea
        this.autoResizeInput(event.target);
    }

    /**
     * Auto-resize input based on content
     */
    autoResizeInput(input) {
        input.style.height = 'auto';
        const maxHeight = 100; // Max height in pixels
        const newHeight = Math.min(input.scrollHeight, maxHeight);
        input.style.height = newHeight + 'px';
    }

    /**
     * Enhanced chat toggle with animations
     */
    toggleChat() {
        if (this.state.isOpen) {
            this.closeChat();
        } else {
            this.openChat();
        }
    }

    /**
     * Enhanced open chat with better animations
     */
    openChat() {
        if (!this.elements.chatInterface || !this.elements.chatIcon) return;
        
        this.elements.chatInterface.classList.remove('hidden');
        this.elements.chatIcon.className = 'fas fa-times text-xl transition-transform duration-300 group-hover:scale-110';
        
        // Add staggered animation for messages
        this.animateMessagesIn();
        
        this.state.isOpen = true;
        this.adjustChatPosition();
        
        // Focus input with delay for better UX
        setTimeout(() => {
            if (this.elements.chatInput) {
                this.elements.chatInput.focus();
                this.elements.chatInput.select();
            }
        }, 400);
        
        this.trackEvent('chat_opened');
        this.log('Chat opened with enhanced animations');
    }

    /**
     * Animate messages when chat opens
     */
    animateMessagesIn() {
        const messages = this.elements.chatMessages?.querySelectorAll('.message-container');
        if (messages) {
            messages.forEach((message, index) => {
                message.style.opacity = '0';
                message.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    message.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    message.style.opacity = '1';
                    message.style.transform = 'translateY(0)';
                }, 100 + (index * 50));
            });
        }
    }

    /**
     * Enhanced close chat
     */
    closeChat() {
        if (!this.elements.chatInterface || !this.elements.chatIcon) return;
        
        this.elements.chatInterface.style.transform = 'scale(0.95) translateY(20px)';
        this.elements.chatInterface.style.opacity = '0.5';
        
        setTimeout(() => {
            this.elements.chatInterface.classList.add('hidden');
            this.elements.chatInterface.style.transform = '';
            this.elements.chatInterface.style.opacity = '';
        }, 200);
        
        this.elements.chatIcon.className = 'fas fa-comments text-xl transition-transform duration-300 group-hover:scale-110';
        
        this.state.isOpen = false;
        
        this.trackEvent('chat_closed');
        this.log('Chat closed with enhanced animations');
    }

    /**
     * Enhanced welcome message display
     */
    displayWelcomeMessage(message) {
        if (this.elements.welcomeMessage) {
            this.elements.welcomeMessage.innerHTML = this.formatMessage(message);
            
            // Add typing animation effect
            this.typeMessage(this.elements.welcomeMessage, message);
        }
    }

    /**
     * Create typing animation effect
     */
    typeMessage(element, fullText) {
        element.innerHTML = '';
        let index = 0;
        const speed = 30; // typing speed
        
        const typeChar = () => {
            if (index < fullText.length) {
                element.innerHTML += fullText.charAt(index);
                index++;
                setTimeout(typeChar, speed);
            }
        };
        
        typeChar();
    }

    /**
     * Generate quick action buttons (without WhatsApp)
     */
    generateQuickActions() {
        if (!this.elements.quickActions) return;
        
        const actions = [
            { text: 'Jam buka hari ini?', icon: 'fas fa-clock', color: 'blue' },
            { text: 'Alamat lengkap?', icon: 'fas fa-map-marker-alt', color: 'green' },
            { text: 'Produk apa saja?', icon: 'fas fa-shopping-bag', color: 'purple' },
            { text: 'Info kontak?', icon: 'fas fa-info-circle', color: 'indigo' }
        ];
        
        const buttonsHtml = actions.map((action, index) => 
            `<button 
                onclick="window.chatWidget.sendQuickMessage('${action.text}')" 
                class="quick-action-btn" 
                style="animation-delay: ${index * 100}ms"
                data-tooltip="${action.text}">
                <i class="${action.icon}"></i>
                <span>${action.text}</span>
            </button>`
        ).join('');
        
        this.elements.quickActions.innerHTML = buttonsHtml;
    }

    /**
     * Enhanced send message with better UI feedback
     */
    async sendMessage() {
        const message = this.elements.chatInput?.value.trim();
        
        if (!message || this.state.isLoading) {
            this.shakeInput();
            return;
        }
        
        // Validate message
        const validation = this.validateMessage(message);
        if (!validation.isValid) {
            this.showError(validation.error);
            this.shakeInput();
            return;
        }
        
        // Clear input and update UI
        this.elements.chatInput.value = '';
        this.updateSendButton(false);
        this.autoResizeInput(this.elements.chatInput);
        
        // Add user message with animation
        this.appendMessage(message, 'user');
        
        // Show enhanced thinking indicator
        this.showThinkingIndicator();
        
        try {
            const response = await this.apiRequest('POST', this.config.apiEndpoints.chatSend, {
                message: message,
                session_id: this.state.session
            });
            
            this.handleChatResponse(response);
            
        } catch (error) {
            this.error('Chat error:', error);
            this.handleChatError(error);
        } finally {
            this.hideThinkingIndicator();
        }
    }

    /**
     * Shake input animation for validation errors
     */
    shakeInput() {
        if (this.elements.chatInput) {
            this.elements.chatInput.parentElement.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                this.elements.chatInput.parentElement.style.animation = '';
            }, 500);
        }
    }

    /**
     * Show enhanced thinking indicator
     */
    showThinkingIndicator() {
        this.state.isLoading = true;
        
        const thinkingMessages = [
            'AI sedang berpikir...',
            'Memproses pertanyaan Anda...',
            'Mencari jawaban terbaik...',
            'Sedang menganalisis...'
        ];
        
        const randomMessage = thinkingMessages[Math.floor(Math.random() * thinkingMessages.length)];
        
        const thinkingElement = this.createThinkingElement(randomMessage);
        this.elements.chatMessages.appendChild(thinkingElement);
        
        this.state.thinkingMessageId = thinkingElement.id;
        this.scrollToBottom();
        
        this.updateSendButton(false);
        if (this.elements.chatInput) {
            this.elements.chatInput.disabled = true;
        }
    }

    /**
     * Create thinking indicator element
     */
    createThinkingElement(message) {
        const thinkingDiv = document.createElement('div');
        thinkingDiv.id = `thinking-${Date.now()}`;
        thinkingDiv.className = 'message-container';
        
        thinkingDiv.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="ai-avatar w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-xs"></i>
                </div>
                <div class="thinking-indicator">
                    <div class="thinking-dots">
                        <div class="thinking-dot"></div>
                        <div class="thinking-dot"></div>
                        <div class="thinking-dot"></div>
                    </div>
                    <div class="thinking-text">${message}</div>
                </div>
            </div>
        `;
        
        return thinkingDiv;
    }

    /**
     * Hide thinking indicator
     */
    hideThinkingIndicator() {
        this.state.isLoading = false;
        
        if (this.state.thinkingMessageId) {
            const thinkingElement = document.getElementById(this.state.thinkingMessageId);
            if (thinkingElement) {
                thinkingElement.style.transition = 'all 0.3s ease-out';
                thinkingElement.style.opacity = '0';
                thinkingElement.style.transform = 'translateY(-10px)';
                
                setTimeout(() => {
                    thinkingElement.remove();
                }, 300);
            }
            this.state.thinkingMessageId = null;
        }
        
        if (this.elements.chatInput) {
            this.elements.chatInput.disabled = false;
            setTimeout(() => this.elements.chatInput.focus(), 100);
        }
    }

    /**
     * Enhanced message creation with better styling
     */
    createMessageElement(message, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message-container';
        
        if (sender === 'user') {
            messageDiv.innerHTML = this.createUserMessageHtml(message);
        } else {
            messageDiv.innerHTML = this.createAiMessageHtml(message);
        }
        
        return messageDiv;
    }

    /**
     * Enhanced user message HTML
     */
    createUserMessageHtml(message) {
        return `
            <div class="flex justify-end">
                <div class="user-message p-4 text-sm shadow-lg max-w-xs lg:max-w-sm">
                    ${this.escapeHtml(message).replace(/\n/g, '<br>')}
                </div>
            </div>
        `;
    }

    /**
     * Enhanced AI message HTML
     */
    createAiMessageHtml(message) {
        return `
            <div class="flex items-start space-x-3">
                <div class="ai-avatar w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-xs"></i>
                </div>
                <div class="ai-message p-4 text-sm max-w-xs lg:max-w-sm">
                    ${this.formatMessage(message)}
                </div>
            </div>
        `;
    }

    /**
     * Enhanced append message with better HTML handling
     */
    appendMessage(message, sender) {
        if (!this.elements.chatMessages) return;
        
        const messageElement = this.createMessageElement(message, sender);
        
        // Add entrance animation
        messageElement.style.opacity = '0';
        messageElement.style.transform = 'translateY(20px) scale(0.95)';
        
        this.elements.chatMessages.appendChild(messageElement);
        
        // Store message in state
        this.state.messages.push({ message, sender, timestamp: Date.now() });
        
        // Limit message history
        if (this.state.messages.length > this.config.maxMessages) {
            this.state.messages = this.state.messages.slice(-this.config.maxMessages);
            this.removeOldMessages();
        }
        
        // Trigger animation
        setTimeout(() => {
            messageElement.style.transition = 'all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
            messageElement.style.opacity = '1';
            messageElement.style.transform = 'translateY(0) scale(1)';
        }, 50);
        
        // Scroll to bottom with animation
        this.scrollToBottomSmooth();
    }

    /**
     * Remove old messages to maintain performance
     */
    removeOldMessages() {
        const messages = this.elements.chatMessages.querySelectorAll('.message-container');
        const maxVisible = this.config.maxMessages - 10; // Keep some buffer
        
        if (messages.length > maxVisible) {
            for (let i = 0; i < messages.length - maxVisible; i++) {
                messages[i].remove();
            }
        }
    }

    /**
     * Enhanced smooth scroll to bottom
     */
    scrollToBottomSmooth() {
        if (this.elements.chatMessages) {
            this.elements.chatMessages.scrollTo({
                top: this.elements.chatMessages.scrollHeight,
                behavior: 'smooth'
            });
        }
    }

    /**
     * Enhanced error display
     */
    showError(message, duration = 5000) {
        if (this.elements.chatError && this.elements.chatErrorMessage) {
            this.elements.chatErrorMessage.textContent = message;
            this.elements.chatError.classList.remove('hidden');
            
            // Auto-hide with fade out
            setTimeout(() => {
                this.elements.chatError.style.transition = 'all 0.3s ease-out';
                this.elements.chatError.style.opacity = '0';
                this.elements.chatError.style.transform = 'translateX(100%)';
                
                setTimeout(() => {
                    this.elements.chatError.classList.add('hidden');
                    this.elements.chatError.style.opacity = '';
                    this.elements.chatError.style.transform = '';
                }, 300);
            }, duration);
        }
    }

    /**
     * Enhanced send button update
     */
    updateSendButton(enabled) {
        if (this.elements.sendBtn) {
            this.elements.sendBtn.disabled = !enabled;
            
            if (enabled) {
                this.elements.sendBtn.style.transform = 'scale(1)';
                this.elements.sendBtn.style.opacity = '1';
            } else {
                this.elements.sendBtn.style.transform = 'scale(0.9)';
                this.elements.sendBtn.style.opacity = '0.6';
            }
        }
    }

    /**
     * Enhanced API request with retry logic
     */
    async apiRequest(method, url, data = null, retries = 2) {
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.config.csrfToken,
                'Accept': 'application/json'
            }
        };
        
        if (data) {
            options.body = JSON.stringify(data);
        }
        
        for (let attempt = 0; attempt <= retries; attempt++) {
            try {
                const response = await fetch(url, options);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return await response.json();
                
            } catch (error) {
                if (attempt === retries) {
                    throw error;
                }
                
                // Wait before retry
                await new Promise(resolve => setTimeout(resolve, 1000 * (attempt + 1)));
                this.log(`Retrying API request (attempt ${attempt + 2}/${retries + 1})`);
            }
        }
    }

    /**
     * Enhanced clear history with confirmation
     */
    async clearHistory(skipConfirmation = false) {
        if (!skipConfirmation) {
            const confirmed = confirm('Apakah Anda yakin ingin menghapus riwayat chat?');
            if (!confirmed) return;
        }
        
        try {
            if (this.state.session) {
                await this.apiRequest('DELETE', this.config.apiEndpoints.chatClear, {
                    session_id: this.state.session
                });
            }
            
            // Animate messages out
            const messages = this.elements.chatMessages.querySelectorAll('.message-container');
            messages.forEach((message, index) => {
                setTimeout(() => {
                    message.style.transition = 'all 0.3s ease-out';
                    message.style.opacity = '0';
                    message.style.transform = 'translateX(-100%)';
                    
                    setTimeout(() => message.remove(), 300);
                }, index * 50);
            });
            
            // Reset state
            setTimeout(() => {
                this.state.messages = [];
                this.state.messageCount = 0;
                this.state.session = null;
                
                // Show welcome message again
                this.showFallbackWelcome();
            }, messages.length * 50 + 300);
            
            this.log('Chat history cleared with animations');
            
        } catch (error) {
            this.error('Failed to clear chat history:', error);
            this.showError('Gagal menghapus riwayat chat');
        }
    }

    /**
     * Get enhanced widget statistics
     */
    getStats() {
        return {
            isInitialized: this.state.isInitialized,
            isOpen: this.state.isOpen,
            isLoading: this.state.isLoading,
            messageCount: this.state.messageCount,
            sessionId: this.state.session,
            messagesInHistory: this.state.messages.length,
            businessInfo: this.state.businessInfo,
            windowSize: {
                width: window.innerWidth,
                height: window.innerHeight,
                isMobile: window.innerWidth < 640
            }
        };
    }

    // ... (inherit all other methods from the original class)
    // Keep all existing methods for backward compatibility
    
    // Existing methods (copy from original)
    addEventListener(element, event, handler) {
        if (!element) return;
        element.addEventListener(event, handler);
        this.eventListeners.push({ element, event, handler });
    }

    handleGlobalKeydown(event) {
        if (event.key === 'Escape' && this.state.isOpen) {
            this.closeChat();
        }
    }

    handleVisibilityChange() {
        if (!document.hidden && this.state.isOpen) {
            setTimeout(() => {
                if (this.elements.chatInput) {
                    this.elements.chatInput.focus();
                }
            }, 100);
        }
    }

    showFallbackWelcome() {
        const fallbackMessage = `Halo! Selamat datang di ${this.config.businessName}! Ada yang bisa saya bantu?`;
        this.displayWelcomeMessage(fallbackMessage);
        this.generateQuickActions();
    }

    sendQuickMessage(message) {
        if (this.elements.chatInput) {
            this.elements.chatInput.value = message;
            this.sendMessage();
        }
    }

    validateMessage(message) {
        if (!message || message.length === 0) {
            return { isValid: false, error: 'Pesan tidak boleh kosong' };
        }
        
        if (message.length > 1000) {
            return { isValid: false, error: 'Pesan terlalu panjang (maksimal 1000 karakter)' };
        }
        
        if (/(.)\1{10,}/.test(message)) {
            return { isValid: false, error: 'Format pesan tidak valid' };
        }
        
        return { isValid: true };
    }

    validateMessage(message) {
        if (!message || message.length === 0) {
            return { isValid: false, error: 'Pesan tidak boleh kosong' };
        }
        
        if (message.length > 1000) {
            return { isValid: false, error: 'Pesan terlalu panjang (maksimal 1000 karakter)' };
        }
        
        if (/(.)\1{10,}/.test(message)) {
            return { isValid: false, error: 'Format pesan tidak valid' };
        }
        
        return { isValid: true };
    }

    /**
     * Handle chat response (simplified without WhatsApp buttons)
     */
    handleChatResponse(response) {
        if (response.success) {
            this.state.session = response.session_id;
            this.state.messageCount++;
            
            const aiResponse = typeof response.response === 'object' 
                ? response.response.message 
                : response.response;
            
            this.appendMessage(aiResponse, 'ai');
            
            // Skip WhatsApp button handling
            // if (response.response?.suggested_action) {
            //     this.handleSuggestedAction(response.response.suggested_action, response.whatsapp_contact);
            // }
            
            this.trackEvent('message_sent', { 
                message_count: this.state.messageCount,
                session_id: this.state.session 
            });
            
        } else {
            throw new Error(response.message || 'AI response failed');
        }
    }

    /**
     * Append raw HTML message (for system-generated HTML like WhatsApp buttons)
     */
    appendRawHtmlMessage(htmlContent, sender) {
        if (!this.elements.chatMessages) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message-container';
        
        if (sender === 'ai') {
            messageDiv.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="ai-avatar w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="ai-message p-4 text-sm max-w-xs lg:max-w-sm">
                        ${htmlContent}
                    </div>
                </div>
            `;
        }
        
        // Add entrance animation
        messageDiv.style.opacity = '0';
        messageDiv.style.transform = 'translateY(20px) scale(0.95)';
        
        this.elements.chatMessages.appendChild(messageDiv);
        
        // Store message in state
        this.state.messages.push({ message: htmlContent, sender, timestamp: Date.now() });
        
        // Limit message history
        if (this.state.messages.length > this.config.maxMessages) {
            this.state.messages = this.state.messages.slice(-this.config.maxMessages);
            this.removeOldMessages();
        }
        
        // Trigger animation
        setTimeout(() => {
            messageDiv.style.transition = 'all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
            messageDiv.style.opacity = '1';
            messageDiv.style.transform = 'translateY(0) scale(1)';
        }, 50);
        
        this.scrollToBottomSmooth();
    }

    /**
     * Handle chat error (simplified without WhatsApp fallback)
     */
    handleChatError(error) {
        const errorMessage = error.message || 'Maaf, terjadi kesalahan koneksi. Silakan coba lagi.';
        this.appendMessage(errorMessage, 'ai');
        
        // Removed WhatsApp fallback
        // if (this.state.businessInfo?.whatsapp || this.config.whatsappNumber) {
        //     setTimeout(() => this.showWhatsAppFallback(), 1000);
        // }
        
        this.trackEvent('chat_error', { error: error.message });
    }

    handleSuggestedAction(action, whatsappContact) {
        if (action.type === 'whatsapp') {
            const contact = whatsappContact || {
                url: `https://wa.me/${this.config.whatsappNumber}`,
                formatted: this.config.whatsappNumber
            };
            
            setTimeout(() => {
                this.appendWhatsAppButton(contact);
            }, 500);
        }
    }

    appendWhatsAppButton(contact) {
        const buttonHtml = `<div class="flex items-center space-x-2 mt-3">
                <a href="${contact.url}" target="_blank" rel="noopener noreferrer"
                   class="bg-green-500 text-white px-6 py-3 rounded-full text-sm hover:bg-green-600 transition-all duration-200 flex items-center space-x-2 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fab fa-whatsapp text-lg"></i>
                    <span>Chat WhatsApp</span>
                </a>
            </div>`;
        
        // Use raw HTML message to avoid escaping
        this.appendRawHtmlMessage(buttonHtml, 'ai');
    }

    showWhatsAppFallback() {
        const whatsappUrl = this.state.businessInfo?.whatsapp || 
                           `https://wa.me/${this.config.whatsappNumber}`;
        
        if (!whatsappUrl) return;
        
        const fallbackMessage = `Atau Anda bisa langsung hubungi kami via WhatsApp:
            <div class="mt-3">
                <a href="${whatsappUrl}" target="_blank" rel="noopener noreferrer"
                   class="bg-green-500 text-white px-6 py-3 rounded-full text-sm hover:bg-green-600 transition-all duration-200 inline-flex items-center space-x-2 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fab fa-whatsapp text-lg"></i>
                    <span>Chat WhatsApp</span>
                </a>
            </div>`;
        
        // Use raw HTML message to avoid escaping
        this.appendRawHtmlMessage(fallbackMessage, 'ai');
    }

    scrollToBottom() {
        if (this.elements.chatMessages) {
            this.elements.chatMessages.scrollTop = this.elements.chatMessages.scrollHeight;
        }
    }

    formatMessage(message) {
        return message.replace(/\n/g, '<br>');
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    trackEvent(event, data = {}) {
        try {
            if (this.config.debug) {
                this.log('Event tracked:', event, data);
            }
        } catch (error) {
            this.warn('Analytics tracking failed:', error);
        }
    }

    log(...args) {
        if (this.config.debug) {
            console.log('[AIChatWidget Enhanced]', ...args);
        }
    }

    warn(...args) {
        if (this.config.debug) {
            console.warn('[AIChatWidget Enhanced]', ...args);
        }
    }

    error(...args) {
        console.error('[AIChatWidget Enhanced]', ...args);
    }

    destroy() {
        this.eventListeners.forEach(({ element, event, handler }) => {
            element.removeEventListener(event, handler);
        });
        
        this.state = {};
        this.elements = {};
        this.eventListeners = [];
        
        this.log('Enhanced chat widget destroyed');
    }
}

// Add CSS for shake animation
const shakeAnimationCSS = `
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}
`;

// Inject shake animation CSS
const style = document.createElement('style');
style.textContent = shakeAnimationCSS;
document.head.appendChild(style);

// Global initialization
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.AppConfig === 'undefined') {
        console.error('AppConfig not found. Enhanced chat widget cannot initialize.');
        return;
    }
    
    window.chatWidget = new AIChatWidget({
        ...window.AppConfig,
        debug: false // Debug mode disabled
    });
    
    // Global methods for backward compatibility
    window.toggleChat = () => window.chatWidget.toggleChat();
    window.sendQuickMessage = (message) => window.chatWidget.sendQuickMessage(message);
    window.clearChatHistory = () => window.chatWidget.clearHistory();
});

// Enhanced cleanup
window.addEventListener('beforeunload', function() {
    if (window.chatWidget) {
        window.chatWidget.destroy();
    }
});
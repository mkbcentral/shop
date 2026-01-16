import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import Chart from 'chart.js/auto';

// ============================================
// Alpine.js Configuration
// ============================================

// Register Alpine plugins BEFORE making it available
Alpine.plugin(focus);

// Make Alpine available globally for Livewire
// NOTE: Do NOT call Alpine.start() - Livewire 3 handles this automatically
window.Alpine = Alpine;

// Make Chart.js available globally
window.Chart = Chart;

// ============================================
// Livewire Navigation Events
// ============================================

// Before navigation - cleanup
document.addEventListener('livewire:navigating', () => {
    // Reset body overflow (in case a modal was open)
    document.body.style.overflow = '';

    // Close any open dropdowns or tooltips
    document.querySelectorAll('[x-data]').forEach(el => {
        if (el.__x && el.__x.$data.open !== undefined) {
            el.__x.$data.open = false;
        }
    });
});

// After navigation - initialization
document.addEventListener('livewire:navigated', () => {
    // Refresh CSRF token to prevent 419 errors
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        // Update all hidden CSRF inputs in forms
        document.querySelectorAll('input[name="_token"]').forEach(input => {
            input.value = token;
        });

        // Update axios headers if available
        if (window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        }
    }

    // Ensure body overflow is reset after navigation
    document.body.style.overflow = '';

    // Scroll to top on navigation (optional - comment out if not desired)
    // window.scrollTo({ top: 0, behavior: 'instant' });
});

// ============================================
// Global Alpine Components & Stores
// ============================================

// Global toast store for notifications
document.addEventListener('alpine:init', () => {
    Alpine.store('toast', {
        show: false,
        message: '',
        type: 'success',

        success(message) {
            this.showToast(message, 'success');
        },

        error(message) {
            this.showToast(message, 'error');
        },

        warning(message) {
            this.showToast(message, 'warning');
        },

        showToast(message, type = 'success') {
            this.message = message;
            this.type = type;
            this.show = true;

            setTimeout(() => {
                this.show = false;
            }, 5000);
        }
    });
});

// Log for debugging
console.log('STK App loaded - Livewire 3 + Alpine.js');

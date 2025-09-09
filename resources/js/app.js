import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

// Loading state management
window.showLoading = (element) => {
    if (element) {
        element.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
        element.disabled = true;
    }
};

window.hideLoading = (element, originalText) => {
    if (element) {
        element.innerHTML = originalText;
        element.disabled = false;
    }
};

// Toast notification system
window.showToast = (message, type = 'success', duration = 5000) => {
    const toastContainer = document.getElementById('toast-container');

    const toast = document.createElement('div');
    toast.className = `p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full max-w-sm`;

    const colors = {
        success: 'bg-green-500 text-white border-l-4 border-green-600',
        error: 'bg-red-500 text-white border-l-4 border-red-600',
        warning: 'bg-yellow-500 text-white border-l-4 border-yellow-600',
        info: 'bg-blue-500 text-white border-l-4 border-blue-600'
    };

    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    toast.classList.add(...colors[type].split(' '));
    toast.innerHTML = `
        <div class="flex items-start">
            <i class="fas ${icons[type]} mr-3 mt-0.5 flex-shrink-0"></i>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 hover:opacity-75 flex-shrink-0">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <div class="mt-2 bg-black bg-opacity-20 rounded-full h-1">
            <div class="bg-white h-1 rounded-full transition-all duration-${duration}ms" style="width: 100%; animation: shrink ${duration}ms linear forwards;"></div>
        </div>
    `;

    toastContainer.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    // Auto remove
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, duration);
};

// Loading overlay functions
window.showLoading = (message = 'Memproses...') => {
    const overlay = document.getElementById('loading-overlay');
    const messageSpan = overlay.querySelector('span');
    messageSpan.textContent = message;
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
};

window.hideLoading = () => {
    const overlay = document.getElementById('loading-overlay');
    overlay.classList.add('hidden');
    overlay.classList.remove('flex');
};

// Enhanced form validation
window.validateForm = (form) => {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalidField = null;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
            isValid = false;
        } else {
            field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        }
    });

    if (!isValid && firstInvalidField) {
        firstInvalidField.focus();
        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    return isValid;
};

// Add CSS animation for toast progress bar
const style = document.createElement('style');
style.textContent = `
    @keyframes shrink {
        from { width: 100%; }
        to { width: 0%; }
    }
`;
document.head.appendChild(style);

Alpine.start();

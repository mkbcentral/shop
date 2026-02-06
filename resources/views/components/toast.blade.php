<div x-data="{
    show: false,
    message: '',
    type: 'success',
    timeoutId: null,
    init() {
        window.addEventListener('show-toast', (event) => {
            // Clear any existing timeout
            if (this.timeoutId) {
                clearTimeout(this.timeoutId);
            }

            this.message = event.detail.message || event.detail[0]?.message || 'Action effectuée';
            this.type = event.detail.type || event.detail[0]?.type || 'success';
            this.show = true;

            // Set new timeout - 7 seconds for better readability
            this.timeoutId = setTimeout(() => {
                this.show = false;
                this.timeoutId = null;
            }, 7000);
        });
    },
    closeToast() {
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
            this.timeoutId = null;
        }
        this.show = false;
    }
}" x-show="show" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    class="fixed top-4 right-4 z-[9999] max-w-sm w-full" style="display: none;">

    <div class="rounded-xl shadow-2xl p-4 backdrop-blur-sm border-2"
        :class="{
            'bg-green-50/95 border-green-500 text-green-900': type === 'success',
            'bg-red-50/95 border-red-500 text-red-900': type === 'error',
            'bg-blue-50/95 border-blue-500 text-blue-900': type === 'info',
            'bg-yellow-50/95 border-yellow-500 text-yellow-900': type === 'warning'
        }">
        <div class="flex items-center gap-3">
            <!-- Icon -->
            <div class="flex-shrink-0">
                <template x-if="type === 'success'">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </template>
                <template x-if="type === 'error'">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <template x-if="type === 'info'">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <template x-if="type === 'warning'">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </template>
            </div>

            <!-- Message -->
            <p class="flex-1 font-semibold" x-text="message"></p>

            <!-- Close button -->
            <button @click="closeToast()" class="flex-shrink-0 text-current opacity-60 hover:opacity-100 transition-opacity">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</div>

@props(['exception' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('components.layouts.partials.head')
<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-navigation-dynamic/>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <x-header />

            <!-- Subscription Alert Banner -->
            <x-subscription-alert />

            <!-- Page Header Section -->
            @if (isset($header))
                <div class="bg-white border-b border-gray-200 px-6 py-4">
                    {{ $header }}
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Global Search Modal - Only load if not on error page -->
    @if(!isset($exception))
        @livewire('global-search')
    @endif

    <!-- Payment Modal - Shows when organization payment is pending -->
    @auth
        @if(!isset($exception))
            <livewire:organization.payment-modal />
        @endif
    @endauth

    <!-- Toast Notifications -->
    <x-toast />

    @stack('scripts')

    <!-- Handle session flash messages -->
    @if (session()->has('success') || session()->has('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (session()->has('success'))
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: @js(session('success')), type: 'success' }
                    }));
                @endif

                @if (session()->has('error'))
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: @js(session('error')), type: 'error' }
                    }));
                @endif
            });
        </script>
    @endif

    <!-- Handle Livewire navigation flash messages -->
    <script>
        document.addEventListener('livewire:navigated', () => {
            // Check for flash messages after Livewire SPA navigation
            if (typeof Livewire !== 'undefined') {
                Livewire.hook('request', ({ fail }) => {
                    fail(({ status, content }) => {
                        // Handle errors
                    });
                });
            }
        });
    </script>

    <!-- Handle PDF downloads from Livewire events -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('downloadPdf', (event) => {
                const url = event.url;
                const link = document.createElement('a');
                link.href = url;
                link.target = '_blank';
                link.click();
            });
        });
    </script>
</body>
</html>

@props(['count' => 8])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
    @for($i = 0; $i < $count; $i++)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 animate-pulse">
            <!-- Image Skeleton -->
            <div class="relative">
                <div class="w-full h-48 bg-gray-200"></div>
                <div class="absolute top-3 right-3 w-8 h-8 bg-gray-300 rounded-full"></div>
            </div>

            <!-- Content Skeleton -->
            <div class="p-4 space-y-3">
                <!-- Category Badge -->
                <div class="h-5 bg-gray-200 rounded-full w-20"></div>

                <!-- Product Name -->
                <div class="space-y-2">
                    <div class="h-5 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-200 rounded w-full"></div>
                </div>

                <!-- Reference -->
                <div class="h-4 bg-gray-200 rounded w-24"></div>

                <!-- Price Section -->
                <div class="pt-3 border-t border-gray-100 space-y-2">
                    <div class="h-6 bg-gray-200 rounded w-32"></div>
                    <div class="h-4 bg-gray-200 rounded w-28"></div>
                </div>

                <!-- Stock Badge -->
                <div class="h-8 bg-gray-200 rounded-full w-full"></div>

                <!-- Actions -->
                <div class="pt-3 flex items-center space-x-2">
                    <div class="flex-1 h-9 bg-gray-200 rounded-lg"></div>
                    <div class="w-9 h-9 bg-gray-200 rounded-lg"></div>
                </div>
            </div>
        </div>
    @endfor
</div>

<style>
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

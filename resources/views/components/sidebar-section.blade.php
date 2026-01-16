@props(['title'])

<div class="pt-6">
    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $title }}</p>
    <div class="mt-3 space-y-1">
        {{ $slot }}
    </div>
</div>

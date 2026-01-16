@props([
    'label' => '',
    'id' => '',
    'disabled' => false,
])

<label for="{{ $id }}" class="inline-flex items-center cursor-pointer">
    <input 
        type="checkbox" 
        {{ $attributes->merge(['class' => 'sr-only peer']) }}
        id="{{ $id }}"
        @if($disabled) disabled @endif
    >
    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
    @if($label)
        <span class="ms-3 text-sm font-medium text-gray-700">{{ $label }}</span>
    @endif
</label>

@props([
    'for' => '',
    'required' => false,
])

<label
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => 'block text-sm font-medium text-slate-700 mb-2']) }}
>
    {{ $slot }}
    @if($required)
        <span class="text-red-500 ml-1">*</span>
    @endif
</label>

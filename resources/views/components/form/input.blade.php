@props([
    'type' => 'text',
    'name' => '',
    'id' => '',
    'placeholder' => '',
    'required' => false,
    'autofocus' => false,
    'autocomplete' => 'off',
    'value' => '',
    'disabled' => false,
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id ?: $name }}"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($required) required @endif
    @if($autofocus) autofocus @endif
    @if($autocomplete !== 'off') autocomplete="{{ $autocomplete }}" @endif
    @if($value) value="{{ $value }}" @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => 'block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed']) }}
/>

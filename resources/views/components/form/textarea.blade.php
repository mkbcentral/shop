@props([
    'name' => '',
    'id' => '',
    'placeholder' => '',
    'rows' => 4,
    'required' => false,
    'disabled' => false,
])

<textarea
    name="{{ $name }}"
    id="{{ $id ?: $name }}"
    rows="{{ $rows }}"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    {{ $attributes->merge(['class' => 'block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-none disabled:opacity-50 disabled:cursor-not-allowed']) }}
    @if($required) required @endif
    @if($disabled) disabled @endif
>{{ $slot }}</textarea>

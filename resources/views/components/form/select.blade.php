@props([
    'name' => '',
    'id' => '',
    'required' => false,
    'disabled' => false,
])

<select
    name="{{ $name }}"
    id="{{ $id ?: $name }}"
    {{ $attributes->merge(['class' => 'block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed']) }}
    @if($required) required @endif
    @if($disabled) disabled @endif
>
    {{ $slot }}
</select>

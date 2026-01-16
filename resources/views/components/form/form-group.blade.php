@props([
    'label' => '',
    'name' => '',
    'error' => '',
    'required' => false,
    'hint' => '',
])

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @if($label)
        <x-form.label :for="$name" :required="$required">
            {{ $label }}
        </x-form.label>
    @endif

    {{ $slot }}

    @if($error)
        <x-form.input-error :message="$error" />
    @endif

    @if($hint)
        <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
    @endif
</div>

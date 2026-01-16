@props(['name' => null, 'messages' => null])

@php
    $messages = $messages ?? ($name ? $errors->get($name) : []);
@endphp

@if ($messages && count($messages) > 0)
    <div {{ $attributes->merge(['class' => 'mt-2 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <p class="text-sm text-red-600">{{ $message }}</p>
        @endforeach
    </div>
@endif

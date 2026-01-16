@props(['title' => null, 'action' => null])

<div class="flex items-center justify-between">
    @if($title)
        <h3 class="text-xl font-bold text-gray-900">{{ $title }}</h3>
    @else
        <div>{{ $slot }}</div>
    @endif
    
    @if($action)
        <div>{{ $action }}</div>
    @endif
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach($stats as $stat)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</p>
            <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
        </div>
    @endforeach
</div>

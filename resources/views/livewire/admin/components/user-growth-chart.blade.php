<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $title }}</h3>
    <div class="h-64" x-data="{
        labels: @js($labels),
        values: @js($values)
    }" x-init="new Chart($refs.canvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nouveaux utilisateurs',
                data: values,
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    })">
        <canvas x-ref="canvas"></canvas>
    </div>
</div>

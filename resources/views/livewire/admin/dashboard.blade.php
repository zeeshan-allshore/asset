<div>
    @section('title', __('Dashboard'))

    <h1>{{ __('Dashboard') }}</h1>

    <div class="card">
        <div id="chart"></div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('chartDataUpdated', function(chartData) {
                renderChart(chartData);
            });

            function renderChart(chartData) {
                var options = {
                    series: [{
                        data: chartData,
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                    },
                    xaxis: {
                        categories: chartData.map(data => data.name),
                    },
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            }

            Livewire.hook('afterDomUpdate', function() {
                Livewire.emit('chartDataUpdated', @json($chartData));
            });
        });
    </script>
@endpush

@extends('layout')
@section('contenu')
    



<div id="chart"></div>




@endsection
@section('script')
    
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- Inclure LarapexChart -->
<script src="https://cdn.jsdelivr.net/npm/larapex-chart@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var chart = @json($chart);
        var options = chart.getOptions();  // Get options from the chart object

        var apexChart = new ApexCharts(document.querySelector("#chart"), options);
        apexChart.render();
    });
</script>
              
    @endsection

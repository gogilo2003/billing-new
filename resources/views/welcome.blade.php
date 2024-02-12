@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
    <div class="row wow fadeIn">

        <div class="col-md-4 pb-2">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Transactions Summary</h4>
                    <p class="category">The combined all time total transactions</p>
                </div>
                <div class="card-body">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="fa fa-clock-o"></i> All time transactions
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-8 pb-2">

            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Monthly Transactions</h4>
                    <p class="category">Transactions for the past 12 Months</p>
                </div>
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="fa fa-check"></i> Transactions for the past 12 Months. Starting
                        {{ $start->format('j F, Y') }} to {{ $today->format('j F, Y') }}
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts_bottom')
    <script src="{{ asset('white') }}/js/plugins/chartjs.min.js"></script>

    <script type="text/javascript">
        // $(document).ready(function() {

        //     // demo.initDashboardPageCharts();

        // });
        $.post('/api/accounts').then(function(response) {
            var ctxP = document.getElementById("pieChart").getContext('2d');
            var myPieChart = new Chart(ctxP, {
                type: 'pie',
                data: {
                    labels: ["Credits", "Debits", "Balances"],
                    datasets: [{
                        data: response.series,
                        borderWidth: 1,
                        backgroundColor: ["rgba(54, 162, 235, 0.75)", "rgba(255, 99, 132, 0.75)",
                            "rgba(255, 206, 86, 0.75)"
                        ],
                        hoverBackgroundColor: ["rgba(54, 162, 235, 0.4)", "rgba(255, 99, 132, 0.4)",
                            "rgba(255, 206, 86, 0.4)"
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        })
        //pie
        $.post('/api/accounts/months').then(function(data) {
            var ctx = document.getElementById("myChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                            label: 'Credits',
                            data: data.series[0],
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            hoverBackgroundColor: 'rgba(54, 162, 235, 0.75)',
                            borderColor: 'rgba(54, 162, 235,1)',
                            borderWidth: 0
                        },
                        {
                            label: 'Debits',
                            data: data.series[1],
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            hoverBackgroundColor: 'rgba(255, 99, 132, 0.75)',
                            borderColor: 'rgba(255,99,132, 1)',
                            borderWidth: 0
                        },
                        {
                            label: 'Balances',
                            data: data.series[2],
                            backgroundColor: 'rgba(255, 206, 86, 0.5)',
                            hoverBackgroundColor: 'rgba(255, 206, 86, 0.75)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 0
                        }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        })
    </script>
@endpush

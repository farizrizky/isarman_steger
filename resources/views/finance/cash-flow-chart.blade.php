@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Grafik Keuangan</h3>
        </div>
        <div class="ms-md-auto">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                     <form action="" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label class="input-group-text">Dari Tanggal : </label>
                                    <input type="date" class="form-control" name="start_date" value="@if(isset($rent_start_date)){{ date('Y-m-d', strtotime($rent_start_date))}}@endif" placeholder="Tanggal Mulai">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label class="input-group-text">Sampai Tanggal : </label>
                                    <input type="date" class="form-control" name="end_date" value="@if(isset($rent_end_date)){{ date('Y-m-d', strtotime($rent_end_date))}}@endif" placeholder="Tanggal Selesai">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label class="input-group-text">Tipe Data </label>
                                    <select class="form-control" id="data_type">
                                        <option value="Per Day" {{ request()->get('data_type', 'Per Day') == 'Per Day' ? 'selected' : '' }}>Per Hari</option>
                                        <option value="Per Month" {{ request()->get('data_type', 'Per Day') == 'Per Month' ? 'selected' : '' }}>Per Bulan</option>
                                        <option value="Per Year" {{ request()->get('data_type', 'Per Day') == 'Per Year' ? 'selected' : '' }}>Per Tahun</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="/assets/js/plugin/chart.js/chart.min.js"></script>
<script>
    let cashChart;
    $(document).ready(function() {
        $("#basic-datatables").DataTable({
            fixedColumns: {
                left: 2,
                
            }
        });
    });

    $('#data_type').change(function() {
        getData();
    });

    $('input[name="start_date"]').change(function() {
        getData();
    });

    $('input[name="end_date"]').change(function() {
        getData();
    });

    function getData(){
        var start_date = $('input[name="start_date"]').val();
        var end_date = $('input[name="end_date"]').val();
        var data_type = $('#data_type').val();

        $.ajax({
            url: '/keuangan/grafik/data',
            type: 'GET',
            data: {
                start_date: start_date,
                end_date: end_date,
                data_type: data_type
            },
            success: function(response) {
                dataBalance = response.data_balance;
                dataIncome = response.data_income;
                dataExpense = response.data_expense;
                label = response.label;
                if (label.length > 0) {
                    renderChart(dataBalance, dataIncome, dataExpense, label);
                } else {
                    var chart = document.getElementById("chart").getContext("2d");
                    chart.clearRect(0, 0, chart.canvas.width, chart.canvas.height);
                    chart.fillText("Tidak ada data untuk ditampilkan", 10, 50);
                }
            }
        });
    }

    function renderChart(dataBalance, dataIncome, dataExpense, label) {
        if (cashChart) {
            cashChart.destroy();
        }

        var ctx = document.getElementById("chart").getContext("2d");

        cashChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: label,
                datasets: [
                    {
                        label: "Kas",
                        data: dataBalance,
                        borderColor: "#1d7af3",
                        backgroundColor: "transparent",
                        borderWidth: 2,
                        pointBackgroundColor: "#1d7af3"
                    },
                    {
                        label: "Pemasukan",
                        data: dataIncome,
                        borderColor: "#59d05d",
                        backgroundColor: "transparent",
                        borderWidth: 2,
                        pointBackgroundColor: "#59d05d"
                    },
                    {
                        label: "Pengeluaran",
                        data: dataExpense,
                        borderColor: "#f3545d",
                        backgroundColor: "transparent",
                        borderWidth: 2,
                        pointBackgroundColor: "#f3545d"
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                            var value = tooltipItem.yLabel;
                            return datasetLabel + ': ' + formatRupiah(value);
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value, index, values) {
                                return formatRupiah(value);
                            }
                        }
                    }]
                },
                layout: {
                    padding: {
                        left: 15,
                        right: 15,
                        top: 15,
                        bottom: 15
                    }
                }
            }
        });
    }

    function formatRupiah(value) {
        return 'Rp' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    

</script>
@endsection

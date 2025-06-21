@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Grafik Penyewaan</h3>
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
                                    <label class="input-group-text">Sewa Tanggal : </label>
                                    <input type="date" class="form-control" name="rent_start_date" value="@if(isset($rent_start_date)){{ date('Y-m-d', strtotime($rent_start_date))}}@endif" placeholder="Tanggal Mulai">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label class="input-group-text">Sampai Tanggal : </label>
                                    <input type="date" class="form-control" name="rent_end_date" value="@if(isset($rent_end_date)){{ date('Y-m-d', strtotime($rent_end_date))}}@endif" placeholder="Tanggal Selesai">
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
    let rentChart;
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

    $('input[name="rent_start_date"]').change(function() {
        getData();
    });

    $('input[name="rent_end_date"]').change(function() {
        getData();
    });

    function getData(){
        var rent_start_date = $('input[name="rent_start_date"]').val();
        var rent_end_date = $('input[name="rent_end_date"]').val();
        var data_type = $('#data_type').val();

        $.ajax({
            url: '/sewa/grafik/data',
            type: 'GET',
            data: {
                rent_start_date: rent_start_date,
                rent_end_date: rent_end_date,
                data_type: data_type
            },
            success: function(response) {
                data = response.data;
                label = response.label;
                console.log(data);
                if (data.length > 0) {
                    renderChart(data, label);
                } else {
                    var chart = document.getElementById("chart").getContext("2d");
                    chart.clearRect(0, 0, chart.canvas.width, chart.canvas.height);
                    chart.fillText("Tidak ada data untuk ditampilkan", 10, 50);
                }
            }
        });
    }

    function renderChart(data, label){
        if (rentChart) {
            rentChart.destroy();
        }
        var chart = document.getElementById("chart").getContext("2d");
        rentChart = new Chart(chart, {
            type: "line",
            data: {
                labels: label,
                datasets: [
                    {
                        label: "Penyewaan",
                        borderColor: "#1d7af3",
                        pointBorderColor: "#FFF",
                        pointBackgroundColor: "#1d7af3",
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 1,
                        pointRadius: 4,
                        backgroundColor: "transparent",
                        fill: true,
                        borderWidth: 2,
                        data: data,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "bottom",
                    labels: {
                        padding: 10,
                        fontColor: "#1d7af3",
                    },
                },
                tooltips: {
                    bodySpacing: 4,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10,
                },
                layout: {
                    padding: { left: 15, right: 15, top: 15, bottom: 15 },
                },
            },
        });
    }
    

</script>
@endsection

@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
            <h6 class="op-7 mb-2">{{ HDate::fullDateFormat(now())}}</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/sewa/pembukuan-sewa" class="btn btn-info btn-round me-2">Buku Besar Penyewaan</a>
            <a href="/sewa/draft/input" class="btn btn-primary btn-round">Buat Draft Sewa</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-list-ul"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Penyewaan</p>
                                <h4 class="card-title currency">{{ HData::getRentTotal('Berjalan')+HData::getRentTotal('Selesai') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Penyewaan Berjalan</p>
                                <h4 class="card-title currency">{{ HData::getRentTotal('Berjalan') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Penyewaan Selesai</p>
                                <h4 class="card-title currency">{{ HData::getRentTotal('Selesai') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Penyewaan Belum Dibayar</p>
                                <h4 class="card-title">Rp <span class="currency">{{ $unpaid_rent->total_payment ?? 0}}</span></h4>
                                <small>Dari <span class="currency">{{ $unpaid_rent->total }}</span> Penyewaan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Ganti Rugi Belum Dibayar</p>
                                <h4 class="card-title">Rp <span class="currency">{{ $unpaid_fine->total_fine ?? 0}}</span></h4>
                                <small>Dari <span class="currency">{{ $unpaid_fine->total }}</span> Penyewaan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Deposit Belum Dikembalikan</p>
                                <h4 class="card-title">Rp <span class="currency">{{ $unpaid_deposit->total_deposit ?? 0}}</span></h4>
                                <small>Dari <span class="currency">{{ $unpaid_deposit->total }}</span> Penyewaan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Jumlah Penyewaan 30 Hari Terakhir</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Penyewaan Berjalan</div>
                    </div>
                </div>
                <div class="card-body">
                     <table class="table table-bordered table-hover" id="basic-datatables">
                        <thead>
                            <tr>
                                <th>Nomor Sewa</th>
                                <th>Penyewa</th>
                                <th>Tanggal Sewa</th>
                            </tr>
                        </thead>
                       
                        <tbody>
                            @foreach ($rent as $r)
                            <tr>
                                <td>
                                    <a href="/sewa/penyewaan/detail/{{ $r->rent_id }}">{{ HID::genNumberRent($r->rent_id) }}</a><br>
                                </td>
                                <td>
                                    <strong>{{ $r->renter->renter_name }}<br>
                                    <a target="_blank" href="/wa/chat/{{ $r->renter->renter_phone }}"> {{ $r->renter->renter_phone }}</a></strong>
                                </td>
                                <td>
                                    <span class="text-muted">{{ HDate::dateFormat($r->rent_start_date) }} s.d {{ HDate::dateFormat($r->rent_end_date) }}</span><br>
                                    @php $daysDiffFromNow = HDate::daysDiffFromNow($r->rent_end_date); @endphp
                                    @if($daysDiffFromNow < 0)
                                        <span class="text-danger">Lewat {{ abs($daysDiffFromNow) }} hari</span>
                                    @elseif($daysDiffFromNow == 0)
                                        Berakhir Hari Ini
                                    @elseif($daysDiffFromNow >=1 && $daysDiffFromNow <= 7)
                                        <span class="text-warning"> Berakhir dalam {{ $daysDiffFromNow }} hari lagi</span>
                                    @else
                                         <span class="text-primary  "> Berakhir dalam {{ $daysDiffFromNow }} hari lagi</span>
                                    @endif
                                </td>
                            </tr>
                            {{-- <tr>
                                <td class="text-nowrap">{{ HID::genNumberRent($r->rent_id) }}</td>
                                <td><b>{{ $r->renter->renter_name }}</b> <br> <a target="_blank" href="/wa/chat/{{ $r->renter->renter_phone }}">{{ $r->renter->renter_phone }}</a></td>
                                <td class="text-nowrap">{{ HDate::dateFormat($r->rent_end_date) }}</td>
                                <td>
                                   
                                </td>
                                <td>
                                    <a class="btn btn-success btn-sm m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim WhatsApp Informasi Sisa Waktu Sewa Kepada Penyewa" target="_blank" href="/wa/informasi-sisa-waktu-sewa/{{ $r->rent_id }}"><span class="fab fa-whatsapp"></span></a>
                                </td>
                            </tr> --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="/assets/js/plugin/chart.js/chart.min.js"></script>
<script>
    $(document).ready(function() {
        $("#basic-datatables").DataTable({
            fixedColumns: {
                left: 2,
            }
        });
    });

    let label = jQuery.parseJSON('@php echo json_encode($label) @endphp');
    let data = jQuery.parseJSON('@php echo json_encode($data_rent) @endphp');

   
    const ctx = document.getElementById('chart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: label,
            datasets: [{
                label: 'Penyewaan',
                data: data,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah Penyewaan'
                    }
                }
            }
        }
    });
</script>
@endsection

@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Buku Besar Pemasukan</h3>
            @if(isset($cash_flow_start_date) && isset($cash_flow_end_date))
                <i>Periode: {{ HDate::dateFormat($cash_flow_start_date) }} s.d. {{ HDate::dateFormat($cash_flow_end_date) }}</i>
            @endif
        </div>
        <div class="ms-md-auto">
            <small>Total Pemasukan  :</small>
            <h1>Rp <span class="currency">{{ $cash_income_total }}</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="/keuangan/arus-kas/pemasukan" method="get">
                        <div class="row">
                            <div class="col-md-4 p-1">
                                <div class="input-group">
                                    <label class="input-group-text">Pemasukan Tanggal : </label>
                                    <input type="date" class="form-control" name="cash_flow_start_date" value="@if(isset($cash_flow_start_date)){{ date('Y-m-d', strtotime($cash_flow_start_date))}}@endif" placeholder="Tanggal Mulai">
                                </div>
                            </div>
                            <div class="col-md-4 p-1">
                                <div class="input-group">
                                    <label class="input-group-text">Sampai Tanggal : </label>
                                    <input type="date" class="form-control" name="cash_flow_end_date" value="@if(isset($cash_flow_end_date)){{ date('Y-m-d', strtotime($cash_flow_end_date))}}@endif" placeholder="Tanggal Selesai">
                                </div>
                            </div>
                            <div class="col-md-4 p-1">
                                <button class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan</button>
                                <a class="btn btn-success" target="_blank" href="/pdf/arus-kas-pemasukan/{{  request()->get('cash_flow_start_date', date('Y-m-d')) }}/{{  request()->get('cash_flow_end_date', date('Y-m-d')) }}"><i class="fas fa-print"></i> Cetak</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="table table-bordered" width="100%">
                            <thead> 
                                <tr class="table-primary">
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Nominal</th>
                                    <th>Total Pemasukan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($cash_flow as $cf)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ HDate::dateFormat($cf->created_at) }}</td>
                                    <td>{{ $cf->cash_flow_income_category }}</td>
                                    <td>
                                        {{ $cf->cash_flow_description }}<br>
                                        @if($cf->cash_flow_income_category == 'Penyewaan' || $cf->cash_flow_income_category == 'Pembayaran Denda')
                                        No. <a href="/sewa/penyewaan/detail/{{ $cf->cash_flow_reference_id }}" target="_blank">{{ HID::genNumberRent($cf->cash_flow_reference_id) }}</a>
                                        @endif
                                    </td>
                                    <td>Rp <span class="currency">{{ $cf->cash_flow_amount }}</span></td>
                                    <td class="text-end">Rp <span class="currency">{{ $cf->cash_flow_income_total_after }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">            
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
  
    $(document).ready(function() {
        $("#basic-datatables").DataTable();
    });

</script>
@endsection

@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Buku Besar Penyewaan</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                     <form action="/sewa/pembukuan-sewa" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label class="input-group-text">Sewa Tanggal : </label>
                                    <input type="date" class="form-control" name="rent_start_date" value="@if(isset($rent_start_date)){{ date('Y-m-d', strtotime($rent_start_date))}}@endif" placeholder="Tanggal Mulai">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label class="input-group-text">Sampai Tanggal : </label>
                                    <input type="date" class="form-control" name="rent_end_date" value="@if(isset($rent_end_date)){{ date('Y-m-d', strtotime($rent_end_date))}}@endif" placeholder="Tanggal Selesai">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group mb-3 bg-primary p-2 text-white rounded">
                                    <label class="text-white">Status Penyewaan</label>
                                    <select class="form-select" id="rent_status" name="rent_status">
                                        <option value="Semua" {{ request()->get('rent_status', 'Semua') == 'Semua' ? 'selected' : '' }}>Semua</option>
                                        <option value="Berjalan" {{ request()->get('rent_status', 'Semua') == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                                        <option value="Selesai" {{ request()->get('rent_status', 'Semua') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3 bg-success p-2 text-white rounded">
                                    <label class="text-white">Status Pembayaran Sewa</label>
                                    <select class="form-select" id="rent_status_payment" name="rent_status_payment">
                                        <option value="Semua" {{ request()->get('rent_status_payment', 'Semua') == 'Semua' ? 'selected' : '' }}>Semua</option>
                                        <option value="Lunas" {{ request()->get('rent_status_payment', 'Semua') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="Belum Bayar" {{ request()->get('rent_status_payment', 'Semua') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3 bg-warning p-2 text-white rounded">
                                    <label class="text-white">Status Pembayaran Pengembalian Sewa</label>
                                    <select class="form-select" id="rent_return_payment_status" name="rent_return_payment_status">
                                        <option value="Semua" {{ request()->get('rent_return_payment_status', 'Semua') == 'Semua' ? 'selected' : '' }}>Semua</option>
                                        <option value="Lunas" {{ request()->get('rent_return_payment_status', 'Semua') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="Belum Bayar" {{ request()->get('rent_return_payment_status', 'Semua') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3 bg-danger p-2 text-white rounded">
                                    <label class="text-white">Status Kwitansi Pengembalian Sewa</label>
                                    <select class="form-select" id="rent_return_receipt_status" name="rent_return_receipt_status">
                                        <option value="Semua" {{ request()->get('rent_return_receipt_status') == 'Semua' ? 'selected' : '' }}>Semua</option>
                                        <option value="Nihil" {{ request()->get('rent_return_receipt_status') == 'Nihil' ? 'selected' : '' }}>Nihil</option>
                                        <option value="Klaim Ganti Rugi" {{ request()->get('rent_return_receipt_status') == 'Klaim Ganti Rugi' ? 'selected' : '' }}>Klaim Ganti Rugi</option>
                                        <option value="Pengembalian Deposit" {{ request()->get('rent_return_receipt_status') == 'Pengembalian Deposit' ? 'selected' : '' }}>Pengembalian Deposit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3 bg-info p-2 text-white rounded">
                                    <label class="text-white">Status Pengembalian Barang</label>
                                    <select class="form-select" id="rent_return_is_complete" name="rent_return_is_complete">
                                        <option value="Semua" {{ request()->get('rent_return_is_complete', 'Semua') == 'Semua' ? 'selected' : '' }}>Semua</option>
                                        <option value='1' {{ request()->get('rent_return_is_complete', 'Semua') == '1' ? 'selected' : '' }}>Kembali Lengkap</option>
                                        <option value='0' {{ request()->get('rent_return_is_complete', 'Semua') == '0' ? 'selected' : '' }}>Kembali Tidak Lengkap</option>
                                    </select>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group mb-3 bg-secondary p-2 text-white rounded">
                                    <label class="text-white">Status Sewa Lanjut</label>
                                    <select class="form-select" id="rent_return_status" name="rent_return_status">
                                        <option value="Semua" {{ request()->get('rent_return_status', 'Semua') == 'Semua' ? 'selected' : '' }}>Semua</option>
                                        <option value="Lanjut" {{ request()->get('rent_return_status', 'Semua') == 'Lanjut' ? 'selected' : '' }}>Lanjut</option>
                                        <option value="Selesai" {{ request()->get('rent_return_status', 'Semua') == 'Selesai' ? 'selected' : '' }}>Tidak Lanjut</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary float-end"><i class="fas fa-search"></i> Tampilkan</button>
                                <a class="btn btn-success float-end me-2" target="_blank" href="/pdf/buku-penyewaan/{{ request()->get('rent_start_date', date('Y-m-d')) }}/{{  request()->get('rent_end_date', date('Y-m-d')) }}/{{ request()->get('rent_status') }}/{{ request()->get('rent_status_payment') }}/{{ request()->get('rent_return_payment_status', 'Semua') }}/{{ request()->get('rent_return_receipt_status') }}/{{ request()->get('rent_return_is_complete', 'Semua') }}/{{ request()->get('rent_return_is_complete', 'Semua') }}"><i class="fas fa-print"></i> Cetak</a>
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
                                    <th>Nomor Penyewaan</th>
                                    <th>Penyewa</th>
                                    <th>Daftar Item</th>
                                    <th>Waktu Sewa</th>
                                    <th>Proyek</th>
                                    <th>Keterangan Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($rent as $r)
                                @php $statusPaymentAll='table-primary'; @endphp
                                @if(!is_null($r->rentReturn))
                                    @if($r->rentReturn->rent_return_status == 'Selesai' || $r->rentReturn->rent_return_status == 'Lanjut')
                                        @if($r->rent_status_payment == 'Belum Bayar' || $r->rentReturn->rent_return_payment_status == 'Belum Bayar')
                                            @php $statusPaymentAll = 'table-danger'; @endphp
                                        @else
                                            @php $statusPaymentAll = 'table-success'; @endphp
                                        @endif
                                    @endif
                                @endif
                                <tr class="{{ $statusPaymentAll }} text-nowrap">
                                    <td>{{ $no++; }}</td>
                                    <td><a class="text-dark text-decoration-underline" href="/sewa/penyewaan/detail/{{ $r->rent_id }}">{{ HID::genNumberRent($r->rent_id) }}</a></td>
                                    <td>
                                        <b>{{ $r->renter->renter_name }}</b><br>
                                        <a target="_blank" href="/wa/chat/{{ $r->renter->renter_phone }}">{{ $r->renter->renter_phone }}</a>
                                    </td>
                                    <td>
                                        @php
                                            $item = $r->rentItem->groupBy('item_id');
                                            $item = $item->map(function($item) use ($rent) {
                                                return [
                                                    'item_id' => \App\Helpers\IDHelper::genID($item[0]->item_id),
                                                    'item_name' => $item[0]->item->item_name,
                                                    'item_unit' => $item[0]->item->item_unit,
                                                    'item_quantity' => $item->sum('rent_item_quantity'),
                                                ];
                                            });
                                            $item = $item->sortBy('item_id')->values()->all();
                                        @endphp
                                        <ol>
                                             @foreach($item as $i)
                                             <li>{{ $i['item_name'] }} ({{ $i['item_quantity']." ".$i['item_unit'] }})</li>
                                                @endforeach
                                        </ol>
                                    </td>
                                    <td class="text-nowrap">
                                        @if($r->rent_duration == '2 Minggu')
                                            2 Minggu
                                        @elseif($r->rent_duration == 'Per Bulan')
                                            {{ $r->rent_total_duration }} Bulan
                                        @endif
                                        <br>
                                        {{ HDate::dateFormat($r->rent_start_date) }} s.d {{ HDate::dateFormat($r->rent_end_date) }}
                                    </td>
                                    <td>
                                        <b>{{ $r->rent_project_name }}</b><br>
                                        <i>{{ $r->rent_project_address }}</i>
                                    </td>
                                    <td>
                                        @if($r->rent_status == 'Selesai')
                                            <strong>Status Penyewaan : Selesai</strong><br>
                                            @if($r->rentReturn->rent_return_status == 'Selesai')
                                                <strong>Status Sewa Lanjut : Tidak Lanjut</strong><br>
                                            @else
                                                 <strong>Status Sewa Lanjut : Lanjut</strong>
                                                @php $rentExtend = HData::getRentExtend($r->rent_id); @endphp
                                                @if ($rentExtend['rent_status'] == 'Draft')
                                                    Draft No. # <a href="/sewa/draft/detail/{{ $rentExtend['rent_id'] }}">{{ HID::genId($rentExtend['rent_id']) }}</a>
                                                @else
                                                    Sewa No. <a href="/sewa/penyewaan/detail/{{ $rentExtend['rent_id'] }}">{{ HID::genNumberRent($rentExtend['rent_id']) }}</a>
                                                @endif
                                                <br>
                                            @endif
                                            @if($r->rentReturn->rent_return_is_complete == 1)
                                                <strong>Status Barang Kembali : Kembali Lengkap</strong><br>
                                            @else
                                                <strong>Status Barang Kembali : Kembali Tidak Lengkap</strong><br>
                                            @endif
                                            <strong>Status Pembayaran :</strong><br>
                                            <ul>
                                                <li>
                                                    Pembayaran Sewa : {{ $r->rent_status_payment }}    
                                                </li>
                                                <li>
                                                    Pembayaran Pengembalian Sewa : {{ $r->rentReturn->rent_return_payment_status }} ({{ $r->rentReturn->rent_return_receipt_status }})
                                                </li>
                                            </ul>
                                        @elseif($r->rent_status == 'Berjalan')
                                            Sewa Berjalan<br>
                                            <ul>
                                                <li>
                                                    Pembayaran Sewa : {{ $r->rent_status_payment }}    
                                                </li>
                                            </ul>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <i>Keterangan Warna :</i><br>
                    <button class="btn btn-success btn-sm"></button><b> Sewa Selesai, Seluruh Pembayaran Lunas</b><br>
                    <button class="btn btn-danger btn-sm"></button><b> Sewa Selesai, Terdapat Pembayaran Belum Lunas</b><br>
                    <button class="btn btn-primary btn-sm"></button><b> Sewa Berjalan</b>                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
  
    $(document).ready(function() {
        $("#basic-datatables").DataTable({
            fixedColumns: {
                left: 2,
                
            }
        });
    });

</script>
@endsection

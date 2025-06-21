@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold">Detail Draft Sewa</h3>
            <small>Tanggal Input : {{ HDate::dateFormat($rent->created_at) }}</small><br>
            @if($rent->rent_is_extension==1)
            Sewa Lanjutan No. <a href="/sewa/penyewaan/detail/{{ $rent_extend->rent_id  }}" class="text-primary">{{ HID::genNumberRent($rent_extend->rent_id) }}</a>
            @endif
        </div>
        <div class="ms-md-auto p-2 py-md-0">
            <h2 class="text-danger"><strong>Rp <span class="currency">{{ $rent->rent_total_payment }}</span></strong></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills nav-fill nav-primary" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-identity-tab" data-bs-toggle="pill" href="#pills-identity" role="tab" aria-controls="pills-identity" aria-selected="true"><strong><span class="fa fa-user"></span> Identitas</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-description-tab" data-bs-toggle="pill" href="#pills-description" role="tab" aria-controls="pills-description" aria-selected="false"><strong><span class="fas fa-dollar-sign"></span> Biaya</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-item-tab" data-bs-toggle="pill" href="#pills-item" role="tab" aria-controls="pills-item" aria-selected="false"><strong><span class="fas fa-dolly"></span> Item</strong></a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content mt-3" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-identity" role="tabpanel" aria-labelledby="pills-identity-tab">
                          
                            <div class="table-responsive">
                                <table class="table table-stripped">
                                    <tbody>
                                        <tr>
                                            <td><strong>Nama Penyewa</strong></td>
                                            <td>: {{ $rent->renter->renter_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>No. Identitas Penyewa</strong></td>
                                            <td>: {{ $rent->renter->renter_identity }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Telepon Penyewa</strong></td>
                                            <td>: <a target="_blank" href="/wa/chat/{{ $rent->renter->renter_phone }}">{{ $rent->renter->renter_phone }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat Penyewa</strong></td>
                                            <td>: {{ $rent->renter->renter_address }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Foto Identitas Penyewa</strong></td>
                                            <td>: <a class="text-primary" href="#" data-bs-toggle="modal" data-bs-target="#image_modal" data-image="{{ asset('storage/'.$rent->renter->renter_identity_photo) }}">Lihat Identitas</a></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Proyek</strong></td>
                                            <td>: {{ $rent->rent_project_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat Proyek</strong></td>
                                            <td>: {{ $rent->rent_project_address }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Telepon Petugas Proyek</strong></td>
                                            <td>: <a target="_blank" href="/wa/chat/{{ $rent->rent_project_phone }}">{{ $rent->rent_project_phone }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-description" role="tabpanel" aria-labelledby="pills-desctiption-tab">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td><strong>Lama Sewa</strong></td>
                                        <td>: 
                                            @if($rent->rent_duration == '2 Minggu')
                                            2 Minggu
                                            @elseif($rent->rent_duration == 'Per Bulan')
                                            {{ $rent->rent_total_duration }} Bulan
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Mulai Sewa</strong></td>
                                        <td>: {{ HDate::dateFormat($rent->rent_start_date) }}</td>
                                    </tr>
                                        <tr>
                                        <td><strong>Tanggal Selesai Sewa</strong></td>
                                        <td>: {{ HDate::dateFormat($rent->rent_end_date) }}</td>
                                    </tr>
                                    </tr>
                                        <tr>
                                        <td><strong>Rincian Harga Sewa</strong></td>
                                        <td>: </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-primary">
                                            <th>Nama</th>
                                            <th>Jumlah</th>
                                            <th>Harga Satuan / {{ $rent->rent_duration }}</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach($rent->rentSet as $rs)
                                        <tr>
                                            <td><small class="text-muted">({{ HID::genId($rs->set_id) }})</small> {{ $rs->set->set_name }}</td>  
                                            <td>{{ $rs->rent_set_quantity }} Set</td>
                                            <td>Rp <span class="currency">{{ $rs->rent_set_price }}</span></td>
                                            <td class="text-end"><strong>Rp <span class="currency">{{ $rs->rent_set_total_price }}</span></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <i>Item Dalam Set {{ $rs->set->set_name }}</i> :
                                                <ul>
                                                    @foreach($rs->rentItem as $ri)
                                                    <li>
                                                        <small class="text-muted">({{ HID::genId($ri->item_id) }})</small> {{ $ri->item->item_name }} {{ $ri->rent_item_quantity }} {{ $ri->item->item_unit }}
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @foreach($rent->rentItem as $ri)
                                        @if($ri->rent_set_id == null)
                                        <tr>
                                            <td><small class="text-muted">({{ HID::genId($ri->item_id) }})</small> {{ $ri->item->item_name }}</td>  
                                            <td>{{ $ri->rent_item_quantity }} {{ $ri->item->item_unit }}</td>
                                            <td>Rp <span class="currency">{{ $ri->rent_item_price }}</span></td>
                                            <td class="text-end"><strong>Rp <span class="currency">{{ $ri->rent_item_total_price }}</span></strong></td>  
                                        </tr>
                                        @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="3"><strong>Subtotal</strong></td>
                                            @php $subtotal = $rent->rent_total_price - $rent->rent_transport_price - $rent->rent_deposit + $rent->rent_discount; @endphp
                                            <td class="text-end"><strong>Rp {{ number_format($subtotal, 0, ',', '.');}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Biaya Transport</strong></td>
                                            <td colspan="1" class="text-end"><strong>Rp <span class="currency">{{ $rent->rent_transport_price }}</span></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Deposit</strong></td>
                                            <td colspan="1" class="text-end"><strong>Rp <span class="currency">{{ $rent->rent_deposit }}</span></strong></td> 
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Diskon</strong></td>
                                            <td colspan="1" class="text-end"><strong>Rp <span class="currency">{{ $rent->rent_discount }}</span></strong></td>
                                        </tr>
                                         @if($rent->rent_is_extension==1)
                                            <tr>
                                                <td colspan="3"><strong>Total Biaya Sewa</strong></td>
                                                <td class="text-end"><strong>Rp {{ number_format($rent->rent_total_price, 0, ',', '.');}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">Deposit dari Sewa Sebelumnya</td>
                                                <td class="text-end">Rp {{ number_format($rent->rent_last_deposit, 0, ',', '.');}}</td>
                                            </tr>
                                        @endif
                                        <tr class="table-info">
                                            <td colspan="3"><strong>Total</strong></td>
                                            <td class="text-end"><strong>Rp <span class="currency">{{ $rent->rent_total_payment}}</span></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-item" role="tabpanel" aria-labelledby="pills-item-tab">
                            <strong>Rekap Jumlah Item Disewa</strong>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Total Dipesan</th>
                                            <th>Stok Saat Ini</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item as $i)
                                        @php 
                                            if ($i['item_quantity'] > $i['item_available']) {
                                                $status="Stok Kurang";
                                            }else{
                                                $status="Tersedia";
                                            }
                                        @endphp
                                        <tr>
                                            <td><small class="text-muted">({{ HID::genId($i['item_id']) }})</small> {{ $i['item_name'] }}</td>  
                                            <td class="currency">{{ $i['item_quantity'] }}</td>
                                            <td class="currency">{{ $i['item_available'] }}</td>
                                            <td><span class="badge badge-{{ $status == 'Tersedia' ? 'success' : 'danger' }}">{{ $status }}</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-left align-items-md-center flex-column flex-md-row pt-4 pb-4">
                   <div width="50%">
                        @if(!HUser::userHasPermission(['approve_rent']))
                        <select class="form-select" id="send_approval"">
                            <option value="">Kirim Permohonan Persetujuan Sewa</option>
                            @foreach(HUser::userCanApproveRent() as $user)
                            <option value="/wa/permintaan-persetujuan-draft-sewa/{{ $user['id'] }}/{{ $rent->rent_id }}">Kirim ke {{ $user['role'] }}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                    <div class="ms-md-auto p-2 py-md-0">
                        <a href="/sewa/draft/edit/{{$rent->rent_id}}" class="btn btn-success"><span class="icon-pencil"></span> Ubah</a>
                        @if(HUser::userHasPermission(['approve_rent']))
                        <button onclick="confirmAlert('/sewa/draft/setujui/{{ $rent->rent_id }}', 'Anda yakin akan menyetujui sewa ini?')" class="btn btn-primary"><span class="fas fa-check"></span> Setujui</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <strong>Invoice Penyewaan</strong>
                </div>
                <div class="card-body">
                    <iframe src="/pdf/invoice-penyewaan/{{ encrypt($rent->rent_id) }}" width="100%" height="400px"></iframe>
                </div>
                <div class="card-footer p-3 ">
                    <form action="/sewa/draft/upload-invoice/{{ $rent->rent_id }}" method="post" novalidate class="needs-validation" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group d-grid gap-2">
                            <label for="renter_identity_file" class="text-wrap"><b>Foto Invoice :</b></label>
                            @if(is_null($rent->rent_invoice_photo))
                            <input type="file" class="form-control input-full"  name="rent_invoice_file" id="rent_invoice_file" required>
                            <div class="invalid-feedback">Foto Invoice harus diisi</div>
                            <button type="submit" class="btn btn-primary"><span class="fas fa-upload"></span> Unggah Foto Invoice</button>
                            @else
                            <div class="input-group">
                                <input type="file" class="form-control input-full"  name="rent_invoice_file" id="rent_invoice_file" required>
                                <div class="invalid-feedback">Foto Invoice harus diisi</div>
                            </div>
                            <button type="submit" class="btn btn-primary"><span class="fas fa-upload"></span> Unggah Foto Invoice</button>
                            <a class="btn btn-info mt-3" target="_blank" href="{{ asset('storage/'.$rent->rent_invoice_photo) }}"><span class="fas fa-eye"></span> Lihat Foto Invoice</a>
                            <a target="_blank" href="/wa/invoice-penyewaan/{{ $rent->rent_id }}" class="btn btn-success"><span class="fab fa-whatsapp"></span> Kirim Invoice Via WhatsApp</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="image_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img class="img-fluid" id="image_show">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
     $('#image_modal').on('show.bs.modal', function (event) {
        var data = $(event.relatedTarget);
        var image = data.data('image');

        $('#image_show').attr("src", image);
    });

    $('#send_approval').on('change', function() {
        var url = $(this).val();
        if (url) {
            window.open(url, '_blank');
            $(this).val(''); // Reset the select after opening
        }
    });
</script>    
@endsection


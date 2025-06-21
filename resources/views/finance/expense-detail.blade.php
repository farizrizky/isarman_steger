@extends('template.dashboard')
@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Pengeluaran</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>ID Pengeluaran</th>
                            <td>: {{ HID::genId($expense->expense_id) }}</td>
                        <tr>
                            <th>Tanggal Pengeluaran</th>
                            <td>: {{ $expense->expense_date }}</td>
                        </tr>
                        <tr>
                            <th>Kategori Pengeluaran</th>
                            <td>: {{ $expense->expense_category }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi Pengeluaran</th>
                            <td>: {{ $expense->expense_description }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Pengeluaran</th>
                            <td>: Rp {{ number_format($expense->expense_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Bukti Pengeluaran</th>
                            <td>: <a href="{{ asset('storage/' . $expense->expense_file) }}" target="_blank">Lihat Bukti</a></td>
                        </tr>
                    </table>
                </div>
                <div class="card-action">
                    <a href="/keuangan/pengeluaran" class="btn btn-black"><span class="icon-action-undo"></span> Kembali</a>
                    @if ($expense->expense_status == 'Draft')
                        <a class="btn btn-info float-end me-2" href="/keuangan/pengeluaran/draft/edit/{{ $expense->expense_id }}"><span class="icon-pencil"></span> Ubah</a>
                        <a class="btn btn-danger float-end me-2" onclick="confirmAlert('/keuangan/pengeluaran/draft/hapus/{{ $expense->expense_id }}', 'Anda yakin akan menghapus data ini?')"><span class="icon-trash"></span> Hapus</a>
                        <a class="btn btn-success float-end me-2" onclick="confirmAlert('/keuangan/pengeluaran/draft/post/{{ $expense->expense_id }}','Anda yakin akan memposting pengeluaran ini? Pengeluaran yang telah diposting tidak dapat diubah')"><span class="icon-check"></span> Posting</a>
                    @else
                        <i class="float-end me-2">Pengeluaran Telah Diposting, {{ HDate::fullDateFormat($expense->expense_posted_at) }}</i>
                    @endif
                  </div>
            </div>
        </div>
        
    </div>
</div>
@endsection


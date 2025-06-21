<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScaffoldingRequest extends FormRequest
{
    protected $type;

    public function __construct($type = null)
    {
        parent::__construct();
        $this->type = $type;
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'item_create' => [
                'item_name' => 'required|max:200',
                'item_unit' => 'required|max:50',
                'stock_total' => 'required|numeric',
                'item_price_2_weeks' => 'required|numeric',
                'item_price_per_month' => 'required|numeric',
                'item_fine_damaged' => 'required|numeric',
                'item_fine_lost' => 'required|numeric'
            ],
            'set_create' => [
                'set_name' => 'required|max:200',
                'set_price_2_weeks' => 'required|numeric',
                'set_price_per_month' => 'required|numeric',
            ],
            'purchase_create' => [
                'purchase_date' => 'required|date',
                'purchase_total' => 'required|numeric',
                'purchase_supplier' => 'required',
                'purchase_receipt_file' => 'required|mimes:jpg, jpeg, png'
            ],
            'purchase_update' => [
                'purchase_date' => 'required|date',
                'purchase_total' => 'required|numeric',
                'purchase_supplier' => 'required',
                'purchase_receipt_file' => 'nullable|mimes:jpg, jpeg, png'
            ],
            'purchase_accepted_evidence_create' => [
                'purchase_accepted_date' => 'required|date',
                'purchase_accepted_evidence_courier_name' => 'required|max:100',
                'purchase_accepted_evidence_vehicle_number' => 'required|max:12',
                'purchase_accepted_evidence_courier_photo_file' => 'required|mimes:jpg, jpeg, png',
                'purchase_accepted_evidence_courier_identity_file' => 'required|mimes:jpg, jpeg, png',
                'purchase_accepted_evidence_vehicle_photo_file' => 'required|mimes:jpg, jpeg, png',
                'purchase_accepted_evidence_vehicle_identity_file' => 'required|mimes:jpg, jpeg, png',
                'purchase_accepted_evidence_file_file' => 'nullable|mimes:jpg, jpeg, png',
            ],
            'purchase_accepted_evidence_update' => [
                'purchase_accepted_date' => 'required|date',
                'purchase_accepted_evidence_courier_name' => 'required|max:100',
                'purchase_accepted_evidence_vehicle_number' => 'required|max:12',
                'purchase_accepted_evidence_courier_photo_file' => 'nullable|mimes:jpg, jpeg, png',
                'purchase_accepted_evidence_courier_identity_file' => 'nullable|mimes:jpg, jpeg, png',
                'purchase_accepted_evidence_vehicle_photo_file' => 'nullable|mimes:jpg, jpeg, png',
                'purchase_accepted_evidence_vehicle_identity_file' => 'nullable|mimes:jpg, jpeg, png',
                'purchase_accepted_evidence_file_file' => 'nullable|mimes:jpg, jpeg, png',
            ],
        ];

        return $rules[$this->type];
    }

    public function messages(): array
    {
        $messages = [
            'item_create' => [
                'item_name.required' => 'Nama item tidak boleh kosong',
                'item_name.max' => 'Nama item maksimal 200 karakter',
                'item_unit.required' => 'Satuan item tidak boleh kosong',
                'item_unit.max' => 'Satuan item maksimal 50 karakter',
                'stock_total.required' => 'Stok awal item tidak boleh kosong',
                'stock_total.numeric' => 'Stok awal harus angka',
                'item_price_2_weeks.required' => 'Harga sewa 2 minggu tidak boleh kosong',
                'item_price_2_weeks.numeric' => 'Harga sewa 2 minggu harus angka',
                'item_price_per_month.required' => 'Harga sewa per bulan tidak boleh kosong',
                'item_price_per_month.numeric' => 'Harga sewa per bulan harus angka',
                'item_fine_damaged.required' => 'Denda kerusakan tidak boleh kosong',
                'item_fine_damaged.numeric' => 'Denda kerusakan harus angka',
                'item_fine_lost.required' => 'Denda kehilangan tidak boleh kosong',
                'item_fine_lost.numeric' => 'Denda kehilangan harus angka',
            ],
            'set_create' => [
                'set_name.required' => 'Nama set tidak boleh kosong',
                'set_name.max' => 'Nama set maksimal 200 karakter',
                'set_price_2_weeks.required' => 'Harga sewa 2 minggu tidak boleh kosong',
                'set_price_2_weeks.numeric' => 'Harga sewa 2 minggu harus angka',
                'set_price_per_month.required' => 'Harga sewa per bulan tidak boleh kosong',
                'set_price_per_month.numeric' => 'Harga sewa per bulan harus angka',
            ],
            'purchase_create' => [
                'purchase_date.required' => 'Tanggal pembelian tidak boleh kosong',
                'purchase_date.date' => 'Tanggal pembelian harus berformat tanggal',
                'purchase_total.required' => 'Total pembelian tidak boleh kosong',
                'purchase_total.numeric' => 'Total pembelian harus berupa angka',
                'purchase_supplier.required' => 'Supplier tidak boleh kosong',
                'purchase_receipt_file.required' => 'Kwintansi pembelian tidak boleh kosong',
                'purchase_receipt_file.mimes' => 'Kwitansi pembelian harus berformat gambar (.jpg, .jpeg, .png)'
            ],
            'purchase_update' => [
                'purchase_date.required' => 'Tanggal pembelian tidak boleh kosong',
                'purchase_date.date' => 'Tanggal pembelian harus berformat tanggal',
                'purchase_total.required' => 'Total pembelian tidak boleh kosong',
                'purchase_total.numeric' => 'Total pembelian harus berupa angka',
                'purchase_supplier.required' => 'Supplier tidak boleh kosong',
                'purchase_receipt_file.mimes' => 'Kwitansi pembelian harus berformat gambar (.jpg, .jpeg, .png)'
            ],
            'purchase_accepted_evidence_create' => [
                'purchase_accepted_date.required' => 'Tanggal penerimaan tidak boleh kosong',
                'purchase_accepted_date.date' => 'Tanggal penerimaan harus berformat tanggal',
                'purchase_accepted_evidence_courier_name.required' => 'Nama kurir tidak boleh kosong',
                'purchase_accepted_evidence_courier_name.max' => 'Nama kurir terlalu panjang',
                'purchase_accepted_evidence_vehicle_number.required' => 'Nomor kendaraan tidak boleh kosong',
                'purchase_accepted_evidence_vehicle_number.max' => 'Nomor kendaraan terlalu panjang',
                'purchase_accepted_evidence_courier_photo_file.required' => 'Foto kurir tidak boleh kosong',
                'purchase_accepted_evidence_courier_photo_file.mimes' => 'Foto kurir harus berformat .jpg, .jpeg, atau .png',
                'purchase_accepted_evidence_courier_identity_file.required' => 'Identitas kurir tidak boleh kosong',
                'purchase_accepted_evidence_courier_identity_file.mimes' => 'Identitas kurir harus berformat .jpg, .jpeg, atau .png',
                'purchase_accepted_evidence_vehicle_photo_file.required' => 'Foto kendaraan tidak boleh kosong',
                'purchase_accepted_evidence_vehicle_photo_file.mimes' => 'Foto kendaraan harus berformat .jpg, .jpeg, atau .png',
                'purchase_accepted_evidence_vehicle_identity_file.required' => 'Identitas kendarran tidak boleh kosong',
                'purchase_accepted_evidence_vehicle_identity_file.mimes' => 'Identitas kendaraan harus berformat .jpg, .jpeg, atau .png',
                'purchase_accepted_evidence_file_file.mimes' => 'Berkas penerimaan harus berformat .jpg, .jpeg, atau .png',
            ],
            'purchase_accepted_evidence_update' => [
                'purchase_accepted_date.required' => 'Tanggal penerimaan tidak boleh kosong',
                'purchase_accepted_date.date' => 'Tanggal penerimaan harus berformat tanggal',
                'purchase_accepted_evidence_courier_name.required' => 'Nama kurir tidak boleh kosong',
                'purchase_accepted_evidence_courier_name.max' => 'Nama kurir terlalu panjang',
                'purchase_accepted_evidence_vehicle_number.required' => 'Nomor kendaraan tidak boleh kosong',
                'purchase_accepted_evidence_vehicle_number.max' => 'Nomor kendaraan terlalu panjang',
                'purchase_accepted_evidence_courier_photo_file.mimes' => 'Foto kurir harus berformat .jpg, .jpeg, atau .png',
                'purchase_accepted_evidence_courier_identity_file.mimes' => 'Identitas kurir harus berformat .jpg, .jpeg, atau .png',
                'purchase_accepted_evidence_vehicle_photo_file.mimes' => 'Foto kendaraan harus berformat .jpg, .jpeg, atau .png',
                'purchase_accepted_evidence_vehicle_identity_file.mimes' => 'Identitas kendaraan harus berformat .jpg, .jpeg, atau .png',
                'purchase_accepted_evidence_file_file.mimes' => 'Berkas penerimaan harus berformat .jpg, .jpeg, atau .png',
            ]
        ];

        return $messages[$this->type];
    }

   
}

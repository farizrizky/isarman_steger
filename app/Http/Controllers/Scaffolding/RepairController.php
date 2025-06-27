<?php

namespace App\Http\Controllers\Scaffolding;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Finance\CashFlowController;
use App\Http\Controllers\Scaffolding\StockController;
use App\Models\Cash;
use App\Models\Item;
use App\Models\Repair;
use App\Models\RepairItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RepairController extends Controller
{
    public function repair(){
        $repair = Repair::with('RepairItem')->get();
        $data = [
            'repair' => $repair,
        ];
        return view('scaffolding.repair-list', $data);
    }

    public function createFormDraftRepair(){
        $data = [
            'item' => Item::get(),
        ];
        return view('scaffolding.repair-draft-create-form', $data);
    }

    public function createDraftRepair(Request $request){
        $repair = Repair::create($request->all());
        $repairId = $repair->repair_id;
        $item = $request->item;
        $quantity = $request->quantity;

        foreach ($item as $key => $value) {
            $data = [
                'repair_id' => $repairId,
                'item_id' => $value,
                'repair_item_quantity' => $quantity[$key],
            ];
            RepairItem::create($data);
        }

        $sweetalert = [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Draft perbaikan berhasil dibuat",
        ];

        return redirect('scaffolding/perbaikan/detail/'.$repairId)->with('sweetalert', $sweetalert);
    }

    public function updateFormDraftRepair($repairId){
        $repair = Repair::with('repairItem')->where('repair_status', 'Draft')->find($repairId);
        if(is_null($repair)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Perbaikan tidak ditemukan atau sudah diselesaikan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $data = [
            'repair' => $repair,
            'item' => Item::get(),
        ];
        return view('scaffolding.repair-draft-update-form', $data);
    }
    public function updateDraftRepair(Request $request, $repairId){
        $repair = Repair::find($repairId);
        if(is_null($repair)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Perbaikan tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $repair->update($request->all());
        RepairItem::where('repair_id', $repairId)->delete();

        $item = $request->item;
        $quantity = $request->quantity;

        foreach ($item as $key => $value) {
            $data = [
                'repair_id' => $repairId,
                'item_id' => $value,
                'repair_item_quantity' => $quantity[$key],
            ];
            RepairItem::create($data);
        }

        $sweetalert = [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Draft perbaikan berhasil diperbarui",
        ];

        return redirect('scaffolding/perbaikan/detail/'.$repairId)->with('sweetalert', $sweetalert);
    }

    public function deleteDraftRepair($repairId){
        $repair = Repair::where('repair_status', 'Draft')->find($repairId);
        if(is_null($repair)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Perbaikan tidak ditemukan atau sudah diselesaikan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $repair->delete();

        $sweetalert = [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Draft perbaikan berhasil dihapus",
        ];

        return redirect('/scaffolding/perbaikan')->with('sweetalert', $sweetalert);
    }

    public function detailRepair($repairId){
        $repair = Repair::with('repairItem')->find($repairId);
        if(is_null($repair)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Perbaikan tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $data = [
            'repair' => $repair,
        ];
        return view('scaffolding.repair-detail', $data);
    }

    public function startRepair($repairId){
        $repair = Repair::with('repairItem')->where('repair_status', 'Draft')->find($repairId);
        if(is_null($repair)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Perbaikan tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $repair->update([
            'repair_status' => 'Dalam Perbaikan',
            'repair_start_at' => now(),
        ]);

        $availableStatus = true;
        foreach ($repair->repairItem as $item) {
            $insufficientStock = StockController::insufficientStock($item->item_id, $item->repair_item_quantity);
            if($insufficientStock){
                $availableStatus = false;
                $sweetalert = [
                    'state' => "error",
                    'title' => "Stok Tidak Cukup",
                    'message' => "Stok item tidak mencukupi untuk perbaikan ini",
                ];
                return redirect('scaffolding/perbaikan/detail/'.$repairId)->with('sweetalert', $sweetalert);
            }
        }
        
        if($availableStatus){
           foreach ($repair->repairItem as $item) {
                StockController::stockOut($item->item_id, 'Perbaikan', $item->repair_item_quantity, 'Repair', $repairId);
            }
        }

        $sweetalert = [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Perbaikan berhasil dimulai",
        ];

        return redirect('scaffolding/perbaikan/detail/'.$repairId)->with('sweetalert', $sweetalert);
    }

    public function uploadReceiptRepair(Request $request, $repairId){
        $repair = Repair::where('repair_status', '!=', 'Draft')->find($repairId);
        if(is_null($repair)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Perbaikan tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        if(!is_null($repair->repair_receipt_file)){
            Storage::disk('public')->delete($repair->repair_receipt_file);
        }
        
        $path = $request->file('repair_receipt_file_file');
        $fileExtension = $path->getClientOriginalExtension();
        $fileName = 'receipt_'.$repair->repair_id.'.'.$fileExtension;
        $pathFile = $path->storeAs('repair', $fileName, 'public');
        $dataRepair['repair_receipt_file'] = $pathFile; 
                
        if($repair->repair_payment_status == 'Belum Bayar'){
           $dataRepair['repair_payment_status'] = 'Lunas';
           $dataRepair['repair_paid_at'] = now();
           CashFlowController::createCashFlow([
                'cash_flow_category' => 'Pengeluaran',
                'cash_flow_description' => 'Pembayaran perbaikan item',
                'cash_flow_amount' => $repair->repair_price,
                'cash_flow_reference_id' => $repair->repair_id,
                'cash_flow_expense_category' => 'Perbaikan Item', 
           ]);
        }

        $repair->update($dataRepair);

        $sweetalert = [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Kwitansi perbaikan item berhasil diupload",
        ];

        return redirect('scaffolding/perbaikan/detail/'.$repairId)->with('sweetalert', $sweetalert);
    }

    public function finishRepair($repairId){
        $repair = Repair::where('repair_status', 'Dalam Perbaikan')->find($repairId);
        if(is_null($repair)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Perbaikan tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $repair->update([
            'repair_status' => 'Selesai',
            'repair_completed_at' => now(),
        ]);

        foreach ($repair->repairItem as $item) {
            StockController::stockIn($item->item_id, 'Selesai Perbaikan', $item->repair_item_quantity, 'Repair', $repairId);
        }

        $sweetalert = [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Perbaikan berhasil diselesaikan",
        ];

        return redirect('scaffolding/perbaikan/detail/'.$repairId)->with('sweetalert', $sweetalert);
    }




}

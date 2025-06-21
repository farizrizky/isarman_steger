<?php

namespace App\Http\Controllers\Scaffolding;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseAcceptedEvidence;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    public function purchase(){
        $data = [
            'purchase' => Purchase::get()
        ]; 
        return view('scaffolding.purchase-list', $data);
    }

    public function createFormPurchase(){
        $data = [
            'item' => Item::get()
        ];
        return view('scaffolding.purchase-create-form', $data);
    }

    public function createPurchase(Request $request){
        if(!isset($request->item_id)){
            $sweetalert = [
                'state' => 'danger',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Item pembelian belum ditambahkan'
            ];
            return back()->with('notify', $sweetalert)->withInput();
        }

        $purchase = Purchase::create($request->all());
        $purchaseId = $purchase->purchase_id;

        $path = $request->file('purchase_receipt_file');
        $fileExtension = $path->getClientOriginalExtension();
        $fileName = 'receipt_'.$purchaseId.'.'.$fileExtension;
        $pathFile = $path->storeAs('purchase/purchase_'.$purchaseId, $fileName, 'public');
        Purchase::find($purchaseId)->update(['purchase_receipt_photo'=>'public/'.$pathFile]);

        $itemId = $request->item_id;
        $purchaseItemQuantity = $request->purchase_item_quantity;

        for($i=0;$i<count($itemId);$i++){
            $dataPurchaseItem = [
                'purchase_id' => $purchaseId,
                'item_id' => $itemId[$i],
                'purchase_item_quantity' => str_replace('.', '', $purchaseItemQuantity[$i])
            ];
            PurchaseItem::create($dataPurchaseItem);
        }

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Pembelian berhasil diinputkan"
        ];
        return redirect('/scaffolding/pembelian')->with('sweetalert', $sweetalert);
    }

    public function updateFormPurchase($purchaseId){
        $purchase = Purchase::with('purchaseItem', 'purchaseAcceptedEvidence')->find($purchaseId);
        if(is_null($purchase)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Pembelian tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $data = [
            'purchase' => $purchase,
            'item' => Item::get(),
        ];
        return view('scaffolding.purchase-update-form', $data);
    }

    public function updatePurchase(Request $request, $purchaseId){
        $purchase = Purchase::with('purchaseItem', 'purchaseAcceptedEvidence')->find($purchaseId);
        if(is_null($purchase)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Pembelian tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        //Cek jika item belum ditambahkan
        if(!isset($request->item_id)){
            $sweetalert = [
                'state' => 'danger',
                'title' => 'Terjadi Kesalahan',
                'message' => 'Item pembelian belum ditambahkan'
            ];
            return back()->with('notify', $sweetalert)->withInput();
        }

        $purchaseStatus = $request->purchase_status;

        if($purchase->purchase_status == "Belum Diterima"){
             //Ubah data pembelian
            if($request->hasFile('purchase_receipt_file')){
                Storage::disk('public')->delete($purchase->purchase_receipt_photo);
                $path = $request->file('purchase_receipt_file');
                $fileExtension = $path->getClientOriginalExtension();
                $fileName = 'receipt_'.$purchaseId.'.'.$fileExtension;
                $pathFile = $path->storeAs('purchase/purchase_'.$purchaseId, $fileName, 'public');
                $request->merge(['purchase_receipt_photo' => 'public/'.$pathFile]);
            }

            $itemId = $request->item_id;
            $purchaseItemQuantity = $request->purchase_item_quantity;
            //Input ulang item pembelian
            for($i=0;$i<count($itemId);$i++){
                //Cek jika item tidak berubah
                $item = PurchaseItem::where(['purchase_id'=>$purchaseId, 'item_id'=>$itemId[$i]]);
                //Jika item masih ada maka hanya dirubah kuantitasnya
                if($item->count()==1){
                    $dataPurchaseItem = [
                        'purchase_item_quantity' => str_replace('.', '', $purchaseItemQuantity[$i])
                    ];
                    $item->update($dataPurchaseItem);
                //Jika item baru maka disimpan
                }else if($item->count()==0){
                    $dataPurchaseItem = [
                        'purchase_id' => $purchaseId,
                        'item_id' => $itemId[$i],
                        'purchase_item_quantity' => str_replace('.', '', $purchaseItemQuantity[$i])
                    ];
                    PurchaseItem::create($dataPurchaseItem);
                }   
            }
            //Hapus item yang tidak digunakan lagi
            $currentItems = PurchaseItem::select('item_id')->where('purchase_id', $purchaseId)->get();
                
            foreach($currentItems as $c){
                if(!in_array($c->item_id, $itemId)){
                    PurchaseItem::where(['purchase_id'=>$purchaseId, 'item_id'=>$c->item_id])->delete();
                }
            }

            if($purchaseStatus == 'Diterima'){  
                //Upload file bukti penerimaan
                $pathCourier = $request->file('purchase_accepted_evidence_courier_file');
                $fileExtensionCourier = $pathCourier->getClientOriginalExtension();
                $fileNameCourier = 'courier_'.$purchaseId.'.'.$fileExtensionCourier;
                $pathFileCourier = $pathCourier->storeAs('purchase/purchase_'.$purchaseId, $fileNameCourier, 'public');

                $pathCourierIdentity = $request->file('purchase_accepted_evidence_courier_identity_file');
                $fileExtensionCourierIdentity = $pathCourierIdentity->getClientOriginalExtension();
                $fileNameCourierIdentity = 'courier_identity_'.$purchaseId.'.'.$fileExtensionCourierIdentity;
                $pathFileCourierIdentity = $pathCourierIdentity->storeAs('purchase/purchase_'.$purchaseId, $fileNameCourierIdentity, 'public');

                $pathVehicle = $request->file('purchase_accepted_evidence_vehicle_file');
                $fileExtensionVehicle = $pathVehicle->getClientOriginalExtension();
                $fileNameVehicle = 'vehicle_'.$purchaseId.'.'.$fileExtensionVehicle;
                $pathFileVehicle = $pathVehicle->storeAs('purchase/purchase_'.$purchaseId, $fileNameVehicle, 'public');

                $pathVehicleIdentity = $request->file('purchase_accepted_evidence_vehicle_identity_file');
                $fileExtensionVehicleIdentity = $pathVehicleIdentity->getClientOriginalExtension();
                $fileNameVehicleIdentity = 'vehicle_identity_'.$purchaseId.'.'.$fileExtensionVehicleIdentity;
                $pathFileVehicleIdentity = $pathVehicleIdentity->storeAs('purchase/purchase_'.$purchaseId, $fileNameVehicleIdentity, 'public');
                
                $request->merge([
                    'purchase_id' => $purchaseId,
                    'purchase_accepted_evidence_courier_photo' => 'public/'.$pathFileCourier,
                    'purchase_accepted_evidence_courier_identity_photo' => 'public/'.$pathFileCourierIdentity,
                    'purchase_accepted_evidence_vehicle_photo' => 'public/'.$pathFileVehicle,
                    'purchase_accepted_evidence_vehicle_identity_photo' => 'public/'.$pathFileVehicleIdentity
                ]);
                
                //Simpan bukti penerimaan
                if($request->hasFile('purchase_accepted_evidence_file_file')){
                    $path = $request->file('purchase_accepted_evidence_file_file');
                    $fileExtension = $path->getClientOriginalExtension();
                    $fileName = 'file_'.$purchaseId.'.'.$fileExtension;
                    $pathFile = $path->storeAs('purchase/purchase_'.$purchaseId, $fileName, 'public');
                    $request->merge(['purchase_accepted_evidence_file' => 'public/'.$pathFile]); 
                }
                PurchaseAcceptedEvidence::create($request->all());
                $itemId = $request->item_id;
                $purchaseItemQuantity = $request->purchase_item_quantity;
                //Update stok
                for($i=0;$i<count($itemId);$i++){
                    StockController::stockIn($itemId[$i], 'Pembelian', $purchaseItemQuantity[$i], 'Purchase', $purchaseId);
                }
            }
            $purchase->update($request->all());
        }else if($purchase->purchase_status=="Diterima"){
            //Upload file bukti penerimaan
            $purchaseEvidence = PurchaseAcceptedEvidence::where('purchase_id', $purchaseId)->first();
            if($request->hasFile('purchase_accepted_evidence_courier_file')){
                Storage::disk('public')->delete($purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_courier_photo);
                $pathCourier = $request->file('purchase_accepted_evidence_courier_file');
                $fileExtensionCourier = $pathCourier->getClientOriginalExtension();
                $fileNameCourier = 'courier_'.$purchaseId.'.'.$fileExtensionCourier;
                $pathFileCourier = $pathCourier->storeAs('purchase/purchase_'.$purchaseId, $fileNameCourier, 'public');
                $request->merge(['purchase_accepted_evidence_courier_photo' => 'public/'.$pathFileCourier]); 
            }

            if($request->hasFile('purchase_accepted_evidence_courier_identity_file')){
                Storage::disk('public')->delete($purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_courier_identity_photo);
                $pathCourierIdentity = $request->file('purchase_accepted_evidence_courier_identity_file');
                $fileExtensionCourierIdentity = $pathCourierIdentity->getClientOriginalExtension();
                $fileNameCourierIdentity = 'courier_identity_'.$purchaseId.'.'.$fileExtensionCourierIdentity;
                $pathFileCourierIdentity = $pathCourierIdentity->storeAs('purchase/purchase_'.$purchaseId, $fileNameCourierIdentity, 'public');
                $request->merge(['purchase_accepted_evidence_courier_identity_photo' => 'public/'.$pathFileCourierIdentity]); 
            }

            if($request->hasFile('purchase_accepted_evidence_vehicle_file')){
                Storage::disk('public')->delete($purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_vehicle_photo);
                $pathVehicle = $request->file('purchase_accepted_evidence_vehicle_file');
                $fileExtensionVehicle = $pathVehicle->getClientOriginalExtension();
                $fileNameVehicle = 'vehicle_'.$purchaseId.'.'.$fileExtensionVehicle;
                $pathFileVehicle = $pathVehicle->storeAs('purchase/purchase_'.$purchaseId, $fileNameVehicle, 'public');
                $request->merge(['purchase_accepted_evidence_vehicle_photo' => 'public/'.$pathFileVehicle]); 
            }

            if($request->hasFile('purchase_accepted_evidence_vehicle_identity_file')){
                Storage::disk('public')->delete($purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_vehicle_identity_photo);
                $pathVehicleIdentity = $request->file('purchase_accepted_evidence_vehicle_identity_file');
                $fileExtensionVehicleIdentity = $pathVehicleIdentity->getClientOriginalExtension();
                $fileNameVehicleIdentity = 'vehicle_identity_'.$purchaseId.'.'.$fileExtensionVehicleIdentity;
                $pathFileVehicleIdentity = $pathVehicleIdentity->storeAs('purchase/purchase_'.$purchaseId, $fileNameVehicleIdentity, 'public');
                $request->merge(['purchase_accepted_evidence_vehicle_identity_photo' => 'public/'.$pathFileVehicleIdentity]); 
            }
            
            if($request->hasFile('purchase_accepted_evidence_file_file')){
                if(!is_null($purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_file)){
                    Storage::disk('public')->delete($purchase->purchaseAcceptedEvidence->purchase_accepted_evidence_file);
                }
                $path = $request->file('purchase_accepted_evidence_file_file');
                $fileExtension = $path->getClientOriginalExtension();
                $fileName = 'file_'.$purchaseId.'.'.$fileExtension;
                $pathFile = $path->storeAs('purchase/purchase_'.$purchaseId, $fileName, 'public');
                $request->merge(['purchase_accepted_evidence_file' => 'public/'.$pathFile]); 
            }

            $purchaseEvidence->update($request->all());
            $purchase->update(['purchase_accepted_date'=>$request->purchase_accepted_date]);
        }

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Pembelian berhasil diubah"
        ];
        return back()->with('sweetalert', $sweetalert);
    }

    public function deletePurchase($purchaseId){
        //Validasi id pembelian
        $purchase = Purchase::with('purchaseItem', 'purchaseAcceptedEvidence')->find($purchaseId);
        if(is_null($purchase)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Pembelian tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $purchaseStatus = $purchase->purchase_status;

        //Jika pembelian telah diterima
        if($purchaseStatus == 'Diterima'){
            //Cek jika stok menjadi minus setelah data pembelian dihapus
            foreach($purchase->purchaseItem as $pi){
                $avoidMinusStock = StockController::avoidMinusStock($pi->item_id, $pi->purchase_item_quantity);
                if(!$avoidMinusStock){
                    $sweetalert =  [
                        'state' => "error",
                        'title' => "Terjadi Kesalahan",
                        'message' => "Stok menjadi minus setelah dihapus"
                    ];
                    return back()->with('sweetalert', $sweetalert);
                }
            }

            //Update stock
            foreach($purchase->purchaseItem as $pi){
                StockController::stockOut($pi->item_id, 'Penghapusan', $pi->purchase_item_quantity, 'Purchase', $purchaseId);
            }
        }

        //Hapus data pembelian
        $purchase->delete();

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Pembelian berhasil dihapus"
        ];
        return redirect('/scaffolding/pembelian')->with('sweetalert', $sweetalert);
    }

    public function detailPurchase($purchaseId){
        $purchase = Purchase::with('purchaseItem', 'purchaseAcceptedEvidence')->find($purchaseId);
        if(is_null($purchase)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Pembelian tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $data = [
            'purchase' => $purchase,
        ];
        return view('scaffolding.purchase-detail', $data);
    }
}

<?php

namespace App\Http\Controllers\Rental;

use App\Helpers\StockHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Finance\CashFlowController;
use App\Http\Controllers\Rental\RenterController;
use App\Http\Controllers\Scaffolding\StockController;
use App\Models\Item;
use App\Models\ItemSet;
use App\Models\Rent;
use App\Models\RentDeposit;
use App\Models\RentDepositFlow;
use App\Models\Renter;
use App\Models\RentItem;
use App\Models\RentReturn;
use App\Models\RentReturnItem;
use App\Models\RentSet;
use App\Models\Set;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RentController extends Controller
{
    public function draftRent(){
        $data = [
            'rent' => Rent::with('renter')->where('rent_status', 'Draft')->orderBy('created_at', 'desc')->get(),
        ];
        return view('rental.rent-draft-list', $data);
    }

    public function createFormDraftRent($rentIdExtend=null){
        if($rentIdExtend!=null){
            $rentExtend = Rent::with('renter', 'rentReturn')->where('rent_status', 'Selesai')->find($rentIdExtend);
            if(is_null($rentExtend)){
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Data Tidak Ditemukan",
                    'message' => "Penyewaan sebelumnya tidak ditemukan"
                ];
                return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
            }

            if($rentExtend->rentReturn == null){
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Terjadi Kesalahan",
                    'message' => "Penyewaan sebelumnya belum dikembalikan"
                ];
                return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
            }

            $rentSet = RentSet::with('set')->where('rent_id', $rentIdExtend)->get();
            $rentItem = RentItem::with('item')->where(['rent_id'=>$rentIdExtend, 'rent_set_id'=>null])->get();

            $dataRentSet = [];
            $dataRentItem = [];
            $dataRentItemSet = [];

            foreach($rentSet as $rs){
                $dataRentSet[$rs->rent_set_id] = [
                    'name' => $rs->set->set_name,
                    'price' => $rs->rent_set_price,
                    'quantity' => $rs->rent_set_quantity,
                    'set_id' => $rs->set_id
                ];

                $rentItemSet = RentItem::with('rent','item',  'itemSet')->where(['rent_id'=>$rentIdExtend, 'rent_set_id'=>$rs->rent_set_id])->get();
                foreach($rentItemSet as $ris){
                    $dataRentItemSet[$rs->rent_set_id][$ris->item_id]['name'] = $ris->item->item_name;
                    $dataRentItemSet[$rs->rent_set_id][$ris->item_id]['quantity'] = $ris->rent_item_quantity;
                    $dataRentItemSet[$rs->rent_set_id][$ris->item_id]['optional'] = $ris->rentSet->set->itemSet->where('item_id', $ris->item_id)->first()->item_set_optional;
                }
            }

            foreach($rentItem as $ri){
                $dataRentItem[$ri->item_id] = [
                    'name' => $ri->item->item_name,
                    'price' => $ri->rent_item_price,
                    'quantity' => $ri->rent_item_quantity,
                    'optional' => 0
                ];
            }

            $dataExtend = [
                'rent_extend' => $rentExtend,
                'rent_set' => json_encode($dataRentSet),
                'rent_item' => json_encode($dataRentItem),
                'rent_item_set' => json_encode($dataRentItemSet),
            ];
        }

        $set = Set::get();
        $item = Item::get();
        $dataSet = [];
        $dataItem = [];
        $dataItemSet = [];
        foreach($set as $s){
            $dataSet[$s->set_id] = [
                'name' => $s->set_name,
                'price_2_weeks' => $s->set_price_2_weeks,
                'price_per_month' => $s->set_price_per_month,
            ];

            $itemSet = ItemSet::with('item')->where('set_id', $s->set_id)->get();
            foreach($itemSet as $is){
                $dataItemSet[$s->set_id][$is->item_id]['name'] = $is->item->item_name;
                $dataItemSet[$s->set_id][$is->item_id]['quantity'] = $is->item_set_quantity;
                $dataItemSet[$s->set_id][$is->item_id]['optional'] = $is->item_set_optional;
            }
        }
        foreach($item as $i){
            $dataItem[$i->item_id] = [
                'name' => $i->item_name,
                'unit' => $i->item_unit,
                'price_2_weeks' => $i->item_price_2_weeks,
                'price_per_month' => $i->item_price_per_month,
                'stock' => StockHelper::getStock('available', $i->item_id)
            ];
        }
        $dataRequired = [
            'set' => json_encode($dataSet),
            'item' => json_encode($dataItem),
            'item_set' => json_encode($dataItemSet),
            'renter_list' => Renter::get(),
        ];

        if($rentIdExtend!=null){
            $data = array_merge($dataRequired, $dataExtend);
            return view('rental.rent-draft-continued-create-form', $data);
        }else{
            return view('rental.rent-draft-create-form', $dataRequired);
        }
    }

    public function createFormDraftRentExistsRenter($renterId){
        $renter = Renter::find($renterId);
        if(is_null($renter)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewa tidak ditemukan"
            ];
            return redirect('/sewa/draft/input')->with('sweetalert', $sweetalert);
        }

      
        $checkRentDraft = Rent::where('renter_id', $renter->renter_id)->where('rent_status', 'Draft') ->first();
        if(!is_null($checkRentDraft)){
                $sweetalert =  [
                'state' => "error",
                'title' => "Terjadi Kesalahan",
                'message' => "Penyewa sudah memiliki draft penyewaan yang belum disetujui",
            ];
            return redirect('/sewa/draft/input')->with('sweetalert', $sweetalert);
        }

        $set = Set::get();
        $item = Item::get();
        $dataSet = [];
        $dataItem = [];
        $dataItemSet = [];
        foreach($set as $s){
            $dataSet[$s->set_id] = [
                'name' => $s->set_name,
                'price_2_weeks' => $s->set_price_2_weeks,
                'price_per_month' => $s->set_price_per_month,
            ];

            $itemSet = ItemSet::with('item')->where('set_id', $s->set_id)->get();
            foreach($itemSet as $is){
                $dataItemSet[$s->set_id][$is->item_id]['name'] = $is->item->item_name;
                $dataItemSet[$s->set_id][$is->item_id]['quantity'] = $is->item_set_quantity;
                $dataItemSet[$s->set_id][$is->item_id]['optional'] = $is->item_set_optional;
            }
        }
        foreach($item as $i){
            $dataItem[$i->item_id] = [
                'name' => $i->item_name,
                'unit' => $i->item_unit,
                'price_2_weeks' => $i->item_price_2_weeks,
                'price_per_month' => $i->item_price_per_month,
                'stock' => StockHelper::getStock('available', $i->item_id)
            ];
        }
        $data = [
            'set' => json_encode($dataSet),
            'item' => json_encode($dataItem),
            'item_set' => json_encode($dataItemSet),
            'renter' => $renter,
            'renter_list' => Renter::get(),
        ];

        return view('rental.rent-draft-exists-renter-create-form', $data);

    }

    public function updateFormDraftRent($rentId){
        $rent = Rent::with('renter')->where('rent_status', 'Draft')->find($rentId);
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }

        $rentIsExtension = $rent->rent_is_extension;
        if($rentIsExtension == 1){
            $rentExtend = Rent::with('renter', 'rentReturn', 'rentDeposit')->where('rent_status', 'Selesai')->find($rent->rent_id_extend);
            if(is_null($rentExtend)){
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Data Tidak Ditemukan",
                    'message' => "Penyewaan sebelumnya tidak ditemukan"
                ];
                return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
            }

            if($rentExtend->rentReturn == null){
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Terjadi Kesalahan",
                    'message' => "Penyewaan sebelumnya belum dikembalikan"
                ];
                return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
            }
        }

        $set = Set::get();
        $item = Item::get();
        $rentSet = RentSet::with('set')->where('rent_id', $rentId)->get();
        $rentItem = RentItem::with('item')->where(['rent_id'=>$rentId, 'rent_set_id'=>null])->get();
        $dataSet = [];
        $dataItem = [];
        $dataItemSet = [];
        $dataRentSet = [];
        $dataRentItem = [];
        $dataRentItemSet = [];
        foreach($set as $s){
            $dataSet[$s->set_id] = [
                'name' => $s->set_name,
                'price_2_weeks' => $s->set_price_2_weeks,
                'price_per_month' => $s->set_price_per_month,
            ];

            $itemSet = ItemSet::with('item')->where('set_id', $s->set_id)->get();
            foreach($itemSet as $is){
                $dataItemSet[$s->set_id][$is->item_id]['name'] = $is->item->item_name;
                $dataItemSet[$s->set_id][$is->item_id]['quantity'] = $is->item_set_quantity;
                $dataItemSet[$s->set_id][$is->item_id]['optional'] = $is->item_set_optional;
            }
        }
        foreach($item as $i){
            $dataItem[$i->item_id] = [
                'name' => $i->item_name,
                'unit' => $i->item_unit,
                'price_2_weeks' => $i->item_price_2_weeks,
                'price_per_month' => $i->item_price_per_month,
                'stock' => StockHelper::getStock('available', $i->item_id)
            ];
        }
       
        foreach($rentSet as $rs){
            $dataRentSet[$rs->rent_set_id] = [
                'name' => $rs->set->set_name,
                'quantity' => $rs->rent_set_quantity,
                'price' => $rs->rent_set_price,
                'set_id' => $rs->set_id
            ];

            $rentItemSet = RentItem::with('rent', 'item',  'itemSet', 'rentSet', 'set')->where(['rent_id'=>$rentId, 'rent_set_id'=>$rs->rent_set_id])->get();
            foreach($rentItemSet as $ris){
                $dataRentItemSet[$rs->rent_set_id][$ris->item_id]['name'] = $ris->item->item_name;
                $dataRentItemSet[$rs->rent_set_id][$ris->item_id]['quantity'] = $ris->rent_item_quantity;
                $dataRentItemSet[$rs->rent_set_id][$ris->item_id]['optional'] = $ris->rentSet->set->itemSet->where('item_id', $ris->item_id)->first()->item_set_optional;
            }
        }

        foreach($rentItem as $ri){
            $dataRentItem[$ri->item_id] = [
                'name' => $ri->item->item_name,
                'quantity' => $ri->rent_item_quantity,
                'price' => $ri->rent_item_price,
                'optional' => 0
            ];
        }
        $data = [
            'rent' => $rent,
            'set' => json_encode($dataSet),
            'item' => json_encode($dataItem),
            'item_set' => json_encode($dataItemSet),
            'rent_set' => json_encode($dataRentSet),
            'rent_item' => json_encode($dataRentItem),
            'rent_item_set' => json_encode($dataRentItemSet),
        ];

        if($rentIsExtension==1){
            $data['rent_extend'] = $rentExtend;
            return view('rental.rent-draft-continued-update-form', $data);
        }else{
            return view('rental.rent-draft-update-form', $data);
        }
        
    }

    public function createDraftRentExistsRenter(Request $request, $renterId){
        $renter = Renter::find($renterId);
        if($request->hasFile('renter_identity_file')){
            Storage::disk('public')->delete($renter->renter_identity_photo);
            $pathFile = $request->file('renter_identity_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'identity_'.$renterId.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('renter', $fileName, 'public');
            $request->merge([
                'renter_identity_photo' => 'public/'.$pathFile
            ]);
        }
        Renter::find($renterId)->update($request->all());
        $request->merge([
            'renter_id' => $renterId,
            'rent_created_by' => Auth::user()->id
        ]);
        $rent = Rent::create($request->all());
        $rentId = $rent->rent_id;

        $rentDeposit = [
            'rent_id' => $rentId,
            'renter_id' => $renterId,
            'rent_deposit_balance' => 0,
        ];
        RentDeposit::create($rentDeposit);
          
        $dataItemSet = [
            'rent_id' => $rentId,
            'item_set' => $request->item_set,
            'item_set_optional' => isset($request->item_set_optional) ? $request->item_set_optional : [],
            'quantity' => $request->quantity,
            'price' => $request->price,
            'duration' => $request->rent_duration,
            'total_duration' => $request->rent_total_duration,
            'transport_price' => $request->rent_transport_price,
            'deposit' => $request->rent_deposit,
            'discount' => $request->rent_discount
        ];
        
        $totalPrice = $this->saveItemSetRent($dataItemSet);
        $totalPayment = $totalPrice;
        
        $dataPrice['rent_total_price'] = $totalPrice;
        $dataPrice['rent_total_payment'] = $totalPayment;
        Rent::find($rentId)->update($dataPrice);
        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Draft penyewaan berhasil disimpan",
        ];
        return redirect('/sewa/draft/detail/'.$rentId)->with('sweetalert', $sweetalert);
    }

    public function createDraftRent(Request $request, $rentIdExtend = null){

        //Cek apakah penyewa sudah memiliki draft penyewaan yang belum disetujui
        $renter = Renter::where('renter_identity', $request->renter_identity)->first();
        if($renter != null){
            $checkRentDraft = Rent::where('renter_id', $renter->renter_id)->where('rent_status', 'Draft') ->first();
            if(!is_null($checkRentDraft)){
                 $sweetalert =  [
                    'state' => "error",
                    'title' => "Terjadi Kesalahan",
                    'message' => "Penyewa sudah memiliki draft penyewaan yang belum disetujui",
                ];
                return redirect('/sewa/draft/input')->with('sweetalert', $sweetalert);
            }
        }

        if($rentIdExtend!=null){
            $rentExtend = Rent::with('renter', 'rentReturn', 'rentDeposit')->where('rent_status', 'Selesai')->find($rentIdExtend);
            if(is_null($rentExtend)){
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Data Tidak Ditemukan",
                    'message' => "Penyewaan sebelumnya tidak ditemukan"
                ];
                return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
            }

            //Cek jika sudah pernah lanjutan penyewaan
            $rentExtendExist = Rent::where('rent_id_extend', $rentIdExtend)->first();
            if(!is_null($rentExtendExist)){
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Terjadi Kesalahan",
                    'message' => "Penyewaan sudah pernah dilanjutkan",
                ];
                return redirect('/sewa/penyewaan/selesai')->with('sweetalert', $sweetalert);
            }

            if($rentExtend->rentReturn == null){
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Terjadi Kesalahan",
                    'message' => "Penyewaan sebelumnya belum dikembalikan"
                ];
                return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
            }
            $renterId = $rentExtend->renter_id;
            $request->merge([
                'renter_id' => $renterId,
                'rent_last_deposit' => $rentExtend->rentDeposit->rent_deposit_balance,
                'rent_is_extension' => 1,
                'rent_id_extend' => $rentIdExtend,
                'rent_created_by' => Auth::user()->id,
            ]);
            $rent = Rent::create($request->all());
            $rentId = $rent->rent_id;
            $rentDeposit = [
                'rent_id' => $rentId,
                'renter_id' => $renterId,
                'rent_deposit_balance' => $rentExtend->rentDeposit->rent_deposit_balance,
            ];
            RentDeposit::create($rentDeposit);
        }else{
            $renter = Renter::create($request->all());
            $renterId = $renter->renter_id;
            $request->merge([
                'renter_id' => $renterId,
                'rent_created_by' => Auth::user()->id
            ]);
            $rent = Rent::create($request->all());
            $rentId = $rent->rent_id;

            $rentDeposit = [
                'rent_id' => $rentId,
                'renter_id' => $renterId,
                'rent_deposit_balance' => 0,
            ];
            RentDeposit::create($rentDeposit);

            $renterIdentityFile = $request->file('renter_identity_file');
            $fileExtension = $renterIdentityFile->getClientOriginalExtension();
            $fileName = 'identity_'.$renterId.'.'.$fileExtension;
            $pathFile = $renterIdentityFile->storeAs('renter', $fileName, 'public');
            Renter::find($renterId)->update(['renter_identity_photo'=>'public/'.$pathFile]);
           
        }

        $dataItemSet = [
            'rent_id' => $rentId,
            'item_set' => $request->item_set,
            'item_set_optional' => isset($request->item_set_optional) ? $request->item_set_optional : [],
            'quantity' => $request->quantity,
            'price' => $request->price,
            'duration' => $request->rent_duration,
            'total_duration' => $request->rent_total_duration,
            'transport_price' => $request->rent_transport_price,
            'deposit' => $request->rent_deposit,
            'discount' => $request->rent_discount
        ];
        
        $totalPrice = $this->saveItemSetRent($dataItemSet);
        $totalPayment = $totalPrice;
        if($rentIdExtend!=null){
            $lastDeposit = $rentExtend->rentDeposit->rent_deposit_balance;
           
            if($lastDeposit >= $totalPrice){
                $totalPayment = 0;
                $dataPrice['rent_payment_method'] = 'Deposit';
            }else{
                if($lastDeposit== 0){
                    $dataPrice['rent_payment_method'] = 'Cash';
                    $totalPayment = $totalPrice;
                }else{
                    $dataPrice['rent_payment_method'] = 'Deposit & Cash';
                    $totalPayment = $totalPrice - $lastDeposit;
                }
            }

            //Jika ada pengembalian deposit dari penyewaan sebelumnya, pengembalian dipending
            $returnReceiptStatus = $rentExtend->rentReturn->rent_return_receipt_status;
            $returnPaymentStatus = $rentExtend->rentReturn->rent_return_payment_status;
            $dataReturn['rent_return_status'] = 'Lanjut';
            if($returnReceiptStatus == 'Pengembalian Deposit' && $returnPaymentStatus == 'Belum Bayar'){
                $dataReturn['rent_return_payment_status'] = 'Pending';
            }
            RentReturn::where('rent_id', $rentIdExtend)->update($dataReturn);
        }
        
        $dataPrice['rent_total_price'] = $totalPrice;
        $dataPrice['rent_total_payment'] = $totalPayment;
        Rent::find($rentId)->update($dataPrice);
        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Draft penyewaan berhasil disimpan",
        ];
        return redirect('/sewa/draft/detail/'.$rentId)->with('sweetalert', $sweetalert);
    }

    public function updateDraftRent(Request $request, $rentId){
        $rent = Rent::with('renter')->where('rent_status', 'Draft')->find($rentId);
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }

        $rentIsExtension = $rent->rent_is_extension;
        if($rentIsExtension == 1){
            $rentExtend = Rent::with('renter', 'rentReturn', 'rentDeposit')->where('rent_status', 'Selesai')->find($rent->rent_id_extend);
            if(is_null($rentExtend)){
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Data Tidak Ditemukan",
                    'message' => "Penyewaan sebelumnya tidak ditemukan"
                ];
                return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
            }

            if($rentExtend->rentReturn == null){
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Terjadi Kesalahan",
                    'message' => "Penyewaan sebelumnya belum dikembalikan"
                ];
                return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
            }
        }else{
            $renterId = $rent->renter_id;
            if($request->hasFile('renter_identity_file')){
                Storage::disk('public')->delete($rent->renter->renter_identity_photo);
                $pathFile = $request->file('renter_identity_file');
                $fileExtension = $pathFile->getClientOriginalExtension();
                $fileName = 'identity_'.$renterId.'.'.$fileExtension;
                $pathFile = $pathFile->storeAs('renter', $fileName, 'public');
                $request->merge([
                    'renter_identity_photo' => 'public/'.$pathFile
                ]);
            }
            Renter::find($renterId)->update($request->all());
        }

        $rent->update($request->all());

        $dataItemSet = [
            'rent_id' => $rentId,
            'item_set' => $request->item_set,
            'item_set_optional' => isset($request->item_set_optional) ? $request->item_set_optional : [],
            'quantity' => $request->quantity,
            'price' => $request->price,
            'duration' => $request->rent_duration,
            'total_duration' => $request->rent_total_duration,
            'transport_price' => $request->rent_transport_price,
            'deposit' => $request->rent_deposit,
            'discount' => $request->rent_discount

        ];
        RentItem::where('rent_id', $rentId)->delete();
        RentSet::where('rent_id', $rentId)->delete();
        $totalPrice = $this->saveItemSetRent($dataItemSet);
        $totalPayment = $totalPrice;
        if($rentIsExtension == 1 && $rentExtend!=null){
            $lastDeposit = $rentExtend->rentDeposit->rent_deposit_balance;
           
            if($lastDeposit >= $totalPrice){
                $totalPayment = 0;
                $dataPrice['rent_payment_method'] = 'Deposit';
            }else{
                if($lastDeposit== 0){
                    $dataPrice['rent_payment_method'] = 'Cash';
                    $totalPayment = $totalPrice;
                }else{
                    $dataPrice['rent_payment_method'] = 'Deposit & Cash';
                    $totalPayment = $totalPrice - $lastDeposit;
                }
            }
        }
        
        $dataPrice['rent_total_price'] = $totalPrice;
        $dataPrice['rent_total_payment'] = $totalPayment;
        $rent->update($dataPrice);
        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Draft Penyewaan berhasil diperbarui",
        ];
        return redirect('/sewa/draft/detail/'.$rentId)->with('sweetalert', $sweetalert);
    }

    public function deleteDraftRent($rentId){
        $rent = Rent::where('rent_status', 'Draft')->find($rentId);
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }
       
        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Draft Penyewaan berhasil dihapus"
        ];

        if($rent->rent_is_extension == 1){
            $rentExtend = Rent::with('rentReturn')->find($rent->rent_id_extend);
            if($rentExtend->rentReturn->rent_return_payment_status == 'Pending'){
                $dataRentReturn['rent_return_payment_status'] = 'Belum Bayar';
            }
            $dataRentReturn['rent_return_status'] = 'Selesai';
            RentReturn::where('rent_id', $rent->rent_id_extend)->update($dataRentReturn);
        }
         $rent->delete();

        return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
    }

    public function detailDraftRent($id){
        $rent = Rent::where('rent_status', 'Draft')->find($id);
        $rentApproved = Rent::where('rent_status', '!=', 'Draft')->find($id);
        if(is_null($rent)){
            if($rentApproved){
                return redirect('/sewa/penyewaan/detail/'.$id);
            }else{
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Data Tidak Ditemukan",
                    'message' => "Draft Penyewaan tidak ditemukan"
                ];
                return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
            }
        }
        $data = [
            'rent' => Rent::with('renter', 'rentDeposit', 'rentSet', 'rentItem', 'set', 'item', 'stock')->find($id),
            'item' => $this->getQuantityTotalRentItem($rent->rent_id)
        ];

        if($rent->rent_is_extension==1){
            $data['rent_extend'] = Rent::with('renter')->find($rent->rent_id_extend);
        }
        return view('rental.rent-draft-detail', $data);
    }

    public function approvalRent($rentId){
        $rent = Rent::with('renter', 'rentItem', 'rentDeposit')->find($rentId);
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }

        $insufficientStock = false;
        $item = $this->getQuantityTotalRentItem($rentId);

        foreach($item as $i){
            $insufficientStock = StockController::insufficientStock($i['item_id'], $i['item_quantity']);
            if($insufficientStock){
                $sweetalert =  [
                    'state' => "error",
                    'title' => "Terjadi Kesalahan",
                    'message' => "Penyewaan tidak dapat disetujui, karena stok tidak mencukupi"
                ];
                return redirect('/sewa/draft/detail/'.$rentId)->with('sweetalert', $sweetalert);
            }
        }

        $rentNumber = $this->setNewNumberRent();
        
        $dataAccept = [
            'rent_status' => 'Berjalan',
            'rent_number' => $rentNumber,
            'rent_approved_by' => Auth::user()->id,
            'rent_approved_at' => date('Y-m-d H:i:s')
        ];
        $rent->update($dataAccept);

        foreach($item as $i){
            StockController::stockOut($i['item_id'], 'Penyewaan', $i['item_quantity'], 'Rent', $rentId);
        }

        if($rent->rent_is_extension==1 && $rent->rentDeposit->rent_deposit_balance > 0){
            if($rent->rentDeposit->rent_deposit_balance >= $rent->rent_total_price){
                $depositRelease = $rent->rent_total_price;
                $rent->update([
                    'rent_status_payment' => 'Lunas',
                    'rent_processed_by' => Auth::user()->id,
                    'rent_processed_at' => date('Y-m-d H:i:s')
                ]);
            }else{
                $depositRelease = $rent->rentDeposit->rent_deposit_balance;
                $rent->update([
                    'rent_status_payment' => 'Belum Bayar',
                ]);
            }
            $this->rentDepositOut($rent->rent_id, 'Pembayaran Sewa Lanjutan', $depositRelease);
        }

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Penyewaan berhasil disetujui"
        ];
        return redirect('/sewa/penyewaan/detail/'.$rentId)->with('sweetalert', $sweetalert);
    }

    public function ongoingRent(){
        $data = [
            'rent' => Rent::with('renter')->where('rent_status', 'Berjalan')->orderBy('rent_approved_at', 'desc')->get(),
        ];
        return view('rental.rent-ongoing-list', $data);
    }

    public function finishedRent(){
        $data = [
            'rent' => Rent::with('renter', 'rentReturn')->where('rent_status', 'Selesai')->get(),
        ];
        return view('rental.rent-finished-list', $data);
    }

    public function detailApprovedRent($id){
        //Validasi id sewa
        $rent = Rent::with('renter', 'rentDeposit', 'rentSet', 'rentItem', 'set', 'item', 'stock', 'rentReturn', 'rentReturnItem')->where('rent_status', '!=' , 'Draft')->find($id);
        $rentExtend = null;
        if($rent->rent_is_extension == 1){
            $rentExtend = Rent::with('renter', 'rentReturn')->find($rent->rent_id_extend);
        }
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }
               
        $data = [
            'rent' => $rent,
            'item' => $this->getQuantityTotalRentItem($rent->rent_id),
            'rent_extend' => $rentExtend ? $rentExtend : null,
        ];

        return view('rental.rent-approved-detail', $data);
    }

    public function uploadInvoiceDraftRent(Request $request, $id){
        //Validasi id sewa
        $rent = Rent::find($id);
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }

        if($request->hasFile('rent_invoice_file')){
            if($rent->rent_invoice_photo != null){
                Storage::disk('public')->delete($rent->rent_invoice_photo);
            }

            $pathFile = $request->file('rent_invoice_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'invoice_'.$id.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('rent/rent_'.$id, $fileName, 'public');
            $request->merge(['rent_invoice_photo' => 'public/'.$pathFile]); 
        }

        $rent->update($request->all());

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Invoice Penyewaan telah diupload"
        ];
        return redirect('/sewa/draft/detail/'.$id)->with('sweetalert', $sweetalert);

    }

    public function uploadFilesRent(Request $request, $id){
        //Validasi id sewa
        $rent = Rent::where('rent_status', '!=', 'Draft')->find($id);
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/draft')->with('sweetalert', $sweetalert);
        }
        if($request->hasFile('rent_receipt_file')){
            if($rent->rent_receipt_photo != null){
                Storage::disk('public')->delete($rent->rent_receipt_photo);
            }

            $pathFile = $request->file('rent_receipt_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'receipt_'.$id.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('rent/rent_'.$id, $fileName, 'public');

            if($rent->rent_status_payment == 'Belum Bayar'){
                $this->rentDepositIn($rent->rent_id, $rent->rent_deposit);
                $request->merge(['rent_status_payment' => 'Lunas']);
                $request->merge(['rent_processed_by' => Auth::user()->id]);
                $request->merge(['rent_processed_at' => date('Y-m-d H:i:s')]);

                $dataCashFlow = [
                    'cash_flow_category' => 'Pemasukan',
                    'cash_flow_income_category' => 'Penyewaan',
                    'cash_flow_description' => 'Pembayaran sewa',
                    'cash_flow_amount' => $rent->rent_total_payment,
                    'cash_flow_reference_id' => $rent->rent_id
                ];

                CashFlowController::createCashFlow($dataCashFlow);
            }

            $request->merge(['rent_receipt_photo' => 'public/'.$pathFile]); 
        }

        if($request->hasFile('rent_statement_letter_file')){
            if($rent->rent_statement_letter_photo != null){
                Storage::disk('public')->delete($rent->rent_statement_letter_photo);
            }

            $pathFile = $request->file('rent_statement_letter_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'statement_letter_'.$id.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('rent/rent_'.$id, $fileName, 'public');
            $request->merge(['rent_statement_letter_photo' => 'public/'.$pathFile]); 
        }

        if($request->hasFile('rent_event_report_file')){
            if($rent->rent_event_report_photo != null){
                Storage::disk('public')->delete($rent->rent_event_report_photo);
            }
            $pathFile = $request->file('rent_event_report_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'event_report_'.$id.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('rent/rent_'.$id, $fileName, 'public');
            $request->merge(['rent_event_report_photo' => 'public/'.$pathFile]); 
        }

        if($request->hasFile('rent_transport_letter_file')){
            if($rent->rent_transport_letter_photo != null){
                Storage::disk('public')->delete($rent->rent_transport_letter_photo);
            }
            $pathFile = $request->file('rent_transport_letter_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'transport_letter_'.$id.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('rent/rent_'.$id, $fileName, 'public');
            $request->merge(['rent_transport_letter_photo' => 'public/'.$pathFile]); 
        }

        if($request->hasFile('rent_invoice_file')){
            if($rent->rent_transport_letter_photo != null){
                Storage::disk('public')->delete($rent->rent_invoice_photo);
            }
            $pathFile = $request->file('rent_invoice_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'invoice_'.$id.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('rent/rent_'.$id, $fileName, 'public');
            $request->merge(['rent_invoice_photo' => 'public/'.$pathFile]); 
        }

        $rent->update($request->all());

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Berkas Penyewaan telah diupload"
        ];
        return redirect('/sewa/penyewaan/detail/'.$id)->with('sweetalert', $sweetalert);
    }

    public function returnRent(Request $request, $id){
        $rent = Rent::with('renter', 'rentItem', 'rentDeposit')->find($id);
        if(is_null($rent)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/penyewaan')->with('sweetalert', $sweetalert);
        }

        if($rent->rent_status_payment == 'Belum Bayar' && $rent->rent_deposit > 0){
            $deposit = $rent->rent_deposit;
            $totalPayment = $rent->rent_total_payment;
            $totalPrice = $rent->rent_total_price;
            $dataNewPrice = [
                'rent_deposit' => 0,
                'rent_total_price' => $totalPrice - $deposit,
                'rent_total_payment' => $totalPrice - $deposit
            ];
            $rent->update($dataNewPrice);
        }

        $isComplete = $request->rent_return_is_complete;
        $rentDepositSaldo = $rent->rentDeposit->rent_deposit_balance;
        $rentReturnItem = $request->rent_return_item;
        $dispensation = $request->rent_return_dispensation_fine;

        $dataReturnPrimer = [
            'rent_id' => $id,
            'rent_return_date' => $request->rent_return_date,
            'rent_return_is_complete' => $isComplete,    
            'rent_return_deposit_saldo' => $rentDepositSaldo,
            'rent_return_discount_fine' => $dispensation,    
        ];

        $rentReturn = RentReturn::create($dataReturnPrimer);
        $rentReturnId = $rentReturn->rent_return_id;

        $totalFineLost = 0;
        $totalFineDamaged = 0;
        $totalFine = 0;
        $grandTotalFine = 0;

        foreach($rentReturnItem as $rri){
            $item = explode('_', $rri);
            $itemId = $item[0];
            $itemLost = $item[1];
            $itemDamaged = $item[2];

            $itemFine = Item::find($itemId);
            $lostFine = $itemFine->item_fine_lost * $itemLost;
            $damagedFine = $itemFine->item_fine_damaged * $itemDamaged;
            $total = $lostFine + $damagedFine;

            $totalFineLost = $totalFineLost + $lostFine;
            $totalFineDamaged = $totalFineDamaged + $damagedFine;
            $totalFine = $totalFine + $total;

            $dataReturnItem = [
                'rent_return_id' => $rentReturnId,
                'item_id' => $itemId,
                'rent_return_item_lost' => $itemLost,
                'rent_return_item_damaged' => $itemDamaged,
                'rent_return_item_fine_lost' => $lostFine,
                'rent_return_item_fine_damaged' => $damagedFine,
                'rent_return_item_total_fine' => $total
            ];

            RentReturnItem::create($dataReturnItem);

            $rentItemRented = RentItem::where(['rent_id'=>$id, 'item_id'=>$itemId])->sum('rent_item_quantity');
            StockController::stockIn($itemId, 'Pengembalian', $rentItemRented, 'RentReturn', $rentReturnId);

            if($itemLost > 0){
                StockController::stockOut($itemId, 'Kehilangan', $itemLost, 'RentReturn', $rentReturnId);
            }

            if($itemDamaged > 0){
                StockController::stockOut($itemId, 'Kerusakan   ', $itemLost, 'RentReturn', $rentReturnId);
            }
        }
        
        //Jika total denda adalah 0, maka sewa kembali lengkap
        if($totalFine==0 && $isComplete == 0){
            RentReturn::find($rentReturnId)->update(['rent_return_is_complete' => 1]);
            $isComplete = 1;
        }

        $grandTotalFine = $totalFine - $dispensation;
        $totalPayment = $rentDepositSaldo - $grandTotalFine;
        $status = '';
        $depositRemains = 0;
        $paymentStatus = '';
        $paymentMethod = '';
        $processedBy = Auth::user()->id;
        $finishedBy = Auth::user()->id;
        $finishedAt = date('Y-m-d H:i:s');

        if($isComplete == 1){
            if($rentDepositSaldo == 0){
                $status = 'Nihil';
                $depositRemains = 0;
                $paymentStatus = 'Lunas';
                $paymentMethod = null;
            }else{
                $status = 'Pengembalian Deposit';
                $depositRemains = $rentDepositSaldo;
                $paymentStatus = 'Belum Bayar';
                $paymentMethod = 'Cash';
                $finishedBy = null;
                $finishedAt = null;
            }
        }else{            
            if($totalPayment < 0){
                $status = 'Klaim Ganti Rugi';
                $depositRemains = 0;
                $totalPayment = abs($totalPayment);
                $finishedBy = null;
                $finishedAt = null;
                if($rentDepositSaldo == 0){
                    $paymentStatus = 'Belum Bayar';
                    $paymentMethod = 'Cash';
                }else{
                    $paymentStatus = 'Belum Bayar';
                    $paymentMethod = 'Deposit & Cash';
                    $this->rentDepositOut($rent->rent_id, 'Pembayaran Denda', $rentDepositSaldo);
                }
            }else if($totalPayment == 0){
                $status = 'Klaim Ganti Rugi';
                $depositRemains = 0;
                $paymentStatus = 'Lunas';
                $paymentMethod = 'Deposit';
                $this->rentDepositOut($rent->rent_id, 'Pembayaran Denda', $rentDepositSaldo);
            }else{
                $status = 'Pengembalian Deposit';
                $depositRemains = $totalPayment;
                $paymentStatus = 'Belum Bayar';
                $paymentMethod = 'Cash';
                $finishedBy = null;
                $finishedAt = null;
                $this->rentDepositOut($rent->rent_id, 'Pembayaran Denda', $grandTotalFine);
            }
        }
    
        $dataReturn = [
            'rent_return_deposit_remains' => $depositRemains,
            'rent_return_payment_status' => $paymentStatus,
            'rent_return_payment_method' => $paymentMethod,
            'rent_return_fine_lost' => $totalFineLost,
            'rent_return_fine_damaged' => $totalFineDamaged,
            'rent_return_total_fine' => $totalFine,
            'rent_return_dispensation_fine' => $dispensation,
            'rent_return_grand_total_fine' => $grandTotalFine,
            'rent_return_total_payment' => $totalPayment,
            'rent_return_receipt_status' => $status,
            'rent_return_payment_status' => $paymentStatus,
            'rent_return_payment_method' => $paymentMethod,
            'rent_return_processed_by' => $processedBy,
            'rent_return_finished_by' => $finishedBy,
            'rent_return_finished_at' => $finishedAt,
        ];

        RentReturn::find($rentReturnId)->update($dataReturn);
        Rent::find($id)->update(['rent_status' => 'Selesai']);

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Penyewaan berhasil dikembalikan"
        ];  
        return redirect('/sewa/penyewaan/detail/'.$id)->with('sweetalert', $sweetalert);
    }

    public function uploadInvoiceReturnRent(Request $request, $id){
        $rentReturn = RentReturn::where('rent_id', $id)->first();
        if(is_null($rentReturn)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Detail Penyewaan Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/penyewaan')->with('sweetalert', $sweetalert);
        }
        if($request->hasFile('rent_return_invoice_file')){
            if($rentReturn->rent_return_invoice_photo != null){
                Storage::disk('public')->delete($rentReturn->rent_return_invoice_photo);
            }
            $pathFile = $request->file('rent_return_invoice_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'invoice_return'.$id.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('rent/rent_'.$id, $fileName, 'public');
            $request->merge(['rent_return_invoice_photo' => 'public/'.$pathFile]);
        }
        
        $rentReturn->update($request->all());
        
        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Invoice pengembalian sewa telah diupload"
        ];
        return redirect('/sewa/penyewaan/detail/'.$id)->with('sweetalert', $sweetalert);
    }

    public function uploadReceiptReturnRent(Request $request, $id){
        $rentReturn = RentReturn::where('rent_id', $id)->first();
        if(is_null($rentReturn)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Detail Penyewaan Tidak Ditemukan",
                'message' => "Penyewaan tidak ditemukan"
            ];
            return redirect('/sewa/penyewaan')->with('sweetalert', $sweetalert);
        }
        if($request->hasFile('rent_return_receipt_file')){
            if($rentReturn->rent_return_receipt_photo != null){
                Storage::disk('public')->delete($rentReturn->rent_return_receipt_photo);
            }
            $pathFile = $request->file('rent_return_receipt_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'receipt_return'.$id.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('rent/rent_'.$id, $fileName, 'public');
            $request->merge(['rent_return_receipt_photo' => 'public/'.$pathFile]);

            if($rentReturn->rent_return_payment_status == 'Belum Bayar'){
                $dataRentReturn['rent_return_payment_status'] = 'Lunas';
                $dataRentReturn['rent_return_finished_by'] = Auth::user()->id;
                $dataRentReturn['rent_return_finished_at'] = date('Y-m-d H:i:s');
                if($rentReturn->rent_return_receipt_status == 'Pengembalian Deposit'){
                    $this->rentDepositOut($rentReturn->rent_id, 'Dikembalikan', $rentReturn->rent_return_deposit_remains);
                    $dataCashFlow = [
                        'cash_flow_category' => 'Pengeluaran',
                        'cash_flow_expense_category' => 'Pengembalian Deposit',
                        'cash_flow_description' => 'Pengembalian deposit sewa',
                        'cash_flow_amount' => $rentReturn->rent_return_total_payment,
                        'cash_flow_reference_id' => $rentReturn->rent_id
                    ];

                    CashFlowController::createCashFlow($dataCashFlow);
                }else if($rentReturn->rent_return_receipt_status == 'Klaim Ganti Rugi'){
                    $dataCashFlow = [
                        'cash_flow_category' => 'Pemasukan',
                        'cash_flow_income_category' => 'Pembayaran Denda',
                        'cash_flow_description' => 'Pembayaran denda sewa',
                        'cash_flow_amount' => $rentReturn->rent_return_total_payment,
                        'cash_flow_reference_id' => $rentReturn->rent_id
                    ];

                    CashFlowController::createCashFlow($dataCashFlow);
                }
                
            }
            
            $dataRentReturn['rent_return_receipt_photo'] = $pathFile;

            $rentReturn->update($dataRentReturn);
        }
        
        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Berkas pengembalian sewa telah diupload"
        ];
        return redirect('/sewa/penyewaan/detail/'.$id)->with('sweetalert', $sweetalert);
    }
    
    public function bookRent(Request $request){
        $rentStartDate = $request->rent_start_date;
        $rentEndDate = $request->rent_end_date;

        $rentStatus = $request->rent_status;
        $rentStatus = ($rentStatus === 'Semua' || is_null($rentStatus)) ? ['Berjalan', 'Selesai'] : [$rentStatus];

        $rentStatusPayment = $request->rent_status_payment;
        $rentStatusPayment = ($rentStatusPayment === 'Semua' || is_null($rentStatusPayment)) ? ['Lunas', 'Belum Bayar'] : [$rentStatusPayment];

        $rentReturnPaymentStatus = $request->rent_return_payment_status;
        $rentReturnPaymentStatus = ($rentReturnPaymentStatus === 'Semua' || is_null($rentReturnPaymentStatus)) ? ['Lunas', 'Belum Bayar', 'Pending'] : [$rentReturnPaymentStatus];

        $rentReturnReceiptStatus = $request->rent_return_receipt_status;
        $rentReturnReceiptStatus = ($rentReturnReceiptStatus === 'Semua' || is_null($rentReturnReceiptStatus)) ? ['Nihil', 'Pengembalian Deposit', 'Klaim Ganti Rugi'] : [$rentReturnReceiptStatus];

        $rentReturnStatus = $request->rent_return_status;
        $rentReturnStatus = ($rentReturnStatus === 'Semua' || is_null($rentReturnStatus)) ? ['Selesai', 'Lanjut'] : [$rentReturnStatus];

        $rentReturnIsComplete = $request->rent_return_is_complete;
        $rentReturnIsComplete = ($rentReturnIsComplete === 'Semua' || is_null($rentReturnIsComplete)) ? [0, 1] : [$rentReturnIsComplete];

        // Mulai Query
        $rent = Rent::whereIn('rent_status', $rentStatus)
                ->whereIn('rent_status_payment', $rentStatusPayment)
                ->with(['renter','rentItem','rentReturn'])
                ->whereHas('rentReturn', function($query) use ($rentReturnStatus, $rentReturnPaymentStatus, $rentReturnReceiptStatus, $rentReturnIsComplete) {
                $query->whereIn('rent_return_status', $rentReturnStatus)
                    ->whereIn('rent_return_payment_status', $rentReturnPaymentStatus)
                    ->whereIn('rent_return_receipt_status', $rentReturnReceiptStatus)
                    ->whereIn('rent_return_is_complete', $rentReturnIsComplete);
            })
    
        ->orderBy('rent_number', 'asc');

        // Filter berdasarkan tanggal
        if (!is_null($rentStartDate) && !is_null($rentEndDate)) {
            $rent->where(function ($query) use ($rentStartDate, $rentEndDate) {
                $query->whereBetween('rent_start_date', [$rentStartDate, $rentEndDate])
                    ->orWhereBetween('rent_end_date', [$rentStartDate, $rentEndDate]);
            });
        }

        $rent = $rent->get();

        return view('rental.rent-book-list', [
            'rent' => $rent,
            'rent_start_date' => $rentStartDate,
            'rent_end_date' => $rentEndDate,
        ]);
    }


     //Menghasilkan nomor penyewaan baru
    private function setNewNumberRent(){
        $rentNumber = Rent::latest('rent_number')->first()['rent_number']+1;
        return $rentNumber;
    }

    //Simpan item dan set penyewaan dan menghasilkan total harga sewa
    private function saveItemSetRent(Array $dataRent){
        $rentId = $dataRent['rent_id'];;
        $itemSet = $dataRent['item_set'];
        $itemSetOptional = $dataRent['item_set_optional'];
        $quantity = $dataRent['quantity'];
        $price = $dataRent['price'];
        $duration = $dataRent['duration'];
        $totalDuration = $dataRent['total_duration'];
        $transportPrice = $dataRent['transport_price'];
        $deposit = $dataRent['deposit'];
        $discount = $dataRent['discount'];

        $totalSetPrice = 0;
        $totalItemPrice = 0;
      

        $index=0;
        foreach($itemSet as $is){
            $itemSetId = explode('_', $is);
            $type = $itemSetId[0];
            $id = $itemSetId[1];
            
            if($type == "set"){
                $set = Set::find($id);
                $quantitySet = $quantity[$index];
                $priceSet = $price[$index];
               
                $setPrice = $priceSet;
                $setSubtotalPrice = $setPrice;
                $setTotalPrice = $quantitySet * $setSubtotalPrice;

                $dataSet = [
                    'rent_id' => $rentId,
                    'set_id' => $id,
                    'rent_set_quantity' => $quantitySet,
                    'rent_set_price' => $setPrice,
                    'rent_set_subtotal_price' => $setSubtotalPrice,
                    'rent_set_total_price' => $setTotalPrice
                ];
                $totalSetPrice = $totalSetPrice+$setTotalPrice;
                $rentSet = RentSet::create($dataSet);
                $rentSetId = $rentSet->rent_set_id;

                $itemSetRequired = ItemSet::where(['set_id'=>$id, 'item_set_optional'=>0])->get();
                foreach($itemSetRequired as $is){
                    $dataItemSet = [
                        'rent_id' => $rentId,
                        'item_id' => $is->item_id,
                        'rent_set_id' => $rentSetId,
                        'rent_item_quantity' => $is->item_set_quantity * $quantitySet,
                        'rent_item_total_price' => 0
                    ];
                    RentItem::create($dataItemSet);
                }

                foreach($itemSetOptional as $iso){
                    $itemOptional= explode('_', $iso);
                    $setId = $itemOptional[0];
                    $itemId = $itemOptional[1];
                    $quantityOptional = $itemOptional[2];
                    if($id == $setId){
                        $dataItemSet = [
                            'rent_id' => $rentId,
                            'item_id' => $itemId,
                            'rent_set_id' => $rentSetId,
                            'rent_item_quantity' => $quantityOptional,
                            'rent_item_total_price' => 0
                        ];
                        RentItem::create($dataItemSet);
                    }
                }
            }else if($type == "item"){
                $item = Item::find($id);
                $quantityItem = $quantity[$index];
                $priceItem = $price[$index];
                
                $itemPrice = $priceItem;
                $itemSubtotalPrice = $itemPrice;
                $itemTotalPrice = $quantityItem * $itemSubtotalPrice;

                $dataItem = [
                    'rent_id' => $rentId,
                    'item_id' => $id,
                    'rent_item_quantity' => $quantityItem,
                    'rent_item_price' => $itemPrice,
                    'rent_item_subtotal_price' => $itemSubtotalPrice,
                    'rent_item_total_price' => $itemTotalPrice
                ];
                $totalItemPrice = $totalItemPrice + $itemTotalPrice;
                RentItem::create($dataItem);
            }
            $index++;
        }

        $totalPrice = $totalSetPrice+$totalItemPrice+$transportPrice+$deposit-$discount;
        return $totalPrice;
    }

    //Menghasilkan item-item yang disewa dan total quantitasnya
    public function getQuantityTotalRentItem($rentId){
        $rent = Rent::find($rentId);
        if(is_null($rent)){
            return [];
        }

        $item = $rent->rentItem->groupBy('item_id');
        $item = $item->map(function($item) {
            return [
                'item_id' => $item[0]->item_id,
                'item_name' => $item[0]->item->item_name,
                'item_quantity' => $item->sum('rent_item_quantity'),
                'item_unit' => $item[0]->item->item_unit,
                'item_available' => Stock::getStock('available', $item[0]->item_id),
                'item_fine_damaged' => $item[0]->item->item_fine_damaged,
                'item_fine_lost' => $item[0]->item->item_fine_lost,
            ];
        });
        $item = $item->sortBy('item_id')->values()->all();
        return $item;
    }

    public static function rentDepositIn($rentId, $amount){
        $rentDeposit = RentDeposit::where('rent_id', $rentId)->first();
        if(is_null($rentDeposit)){
            return false;
        }

        $rentDeposit->rent_deposit_balance += $amount;
        $rentDeposit->save();

        $dataRentDepositFlow = [
            'rent_deposit_id' => $rentDeposit->rent_deposit_id,
            'rent_deposit_flow_action' => 'Masuk',
            'rent_deposit_flow_amount' => $amount,
            'rent_deposit_flow_balance' => $rentDeposit->rent_deposit_balance,
        ];
        RentDepositFlow::create($dataRentDepositFlow);
        return true;
    }

    public static function rentDepositOut($rentId, $release, $amount){
        $rentDeposit = RentDeposit::where('rent_id', $rentId)->first();
        if(is_null($rentDeposit)){
            return false;
        }

        $depositSaldo = $rentDeposit->rent_deposit_balance;
        if($depositSaldo < $amount){
            return false;
        }

        $rentDeposit->rent_deposit_balance -= $amount;
        $rentDeposit->save();

        $dataRentDepositFlow = [
            'rent_deposit_id' => $rentDeposit->rent_deposit_id,
            'rent_deposit_flow_action' => 'Keluar',
            'rent_deposit_flow_release' => $release,
            'rent_deposit_flow_amount' => $amount,
            'rent_deposit_flow_balance' => $rentDeposit->rent_deposit_balance,
        ];
        RentDepositFlow::create($dataRentDepositFlow);
        return true;
    }

    public function rentChart(){
        return view('rental.rent-chart');
    }

    public function rentChartData(Request $request){
        $rentStartDate = $request->rent_start_date;
        $rentEndDate = $request->rent_end_date;
        $typeData = $request->data_type;

        $data = [];
        $label = [];

        if($typeData == 'Per Day'){
            $dataRent = Rent::where('rent_status', '!=' , 'Draft')
                ->whereBetween('rent_start_date', [$rentStartDate, $rentEndDate])
                ->get()
                ->groupBy(function($date) {
                    return date('Y-m-d', strtotime($date->rent_start_date));
                });

            foreach($dataRent as $date => $rent){
                $label[] = date('d-m-Y', strtotime($date));
                $data[] = $rent->count();
            }
        }else if($typeData == 'Per Month'){
            $dataRent = Rent::where('rent_status', '!=' , 'Draft')
                ->whereBetween('rent_start_date', [$rentStartDate, $rentEndDate])
                ->get()
                ->groupBy(function($date) {
                    return date('Y-m', strtotime($date->rent_start_date));
                });

            foreach($dataRent as $date => $rent){
                $label[] = date('F Y', strtotime($date));
                $data[] = $rent->count();
            }
        }else if($typeData == 'Per Year'){
            $dataRent = Rent::where('rent_status', '!=' , 'Draft')
                ->whereBetween('rent_start_date', [$rentStartDate, $rentEndDate])
                ->get()
                ->groupBy(function($date) {
                    return date('Y', strtotime($date->rent_start_date));
                });
            foreach($dataRent as $date => $rent){
                $label[] = date('Y', strtotime($date));
                $data[] = $rent->count();
            }
        }
        $data = [
            'label' => $label,
            'data' => $data,
            'type_data' => $typeData,
            'rent_start_date' => $rentStartDate,
            'rent_end_date' => $rentEndDate
        ];
        return response()->json($data);
    }
                        
}

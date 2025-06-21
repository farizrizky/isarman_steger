<?php

namespace App\Http\Controllers\Scaffolding;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Rental\RentController;
use App\Models\Item;
use App\Models\ItemSet;
use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public function set(){
        $set = Set::with('itemSet')->get();

        $data = [
            'set' => $set
        ];
        return view('scaffolding.set-list', $data);
    }

    public function createFormSet(){
        $data = [
            'item' => Item::get()
        ];
        return view('scaffolding.set-create-form', $data);
    }

    public function createSet(Request $request){
        $request->flash();

        if(!isset($request->item_id)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Item set belum ditambahkan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $set = Set::create($request->all());
        $setId = $set->set_id;

        //Input item set
        $itemId = $request->item_id;
        $itemSetQuantity = $request->item_set_quantity;
        $itemSetOptional = $request->item_set_optional;
        
        for($i=0;$i<count($itemId);$i++){
            $dataItemSet = [
                'set_id' => $setId,
                'item_id' => $itemId[$i],
                'item_set_quantity' => str_replace('.', '', $itemSetQuantity[$i]),
                'item_set_optional' => $itemSetOptional[$i]
            ];
            ItemSet::create($dataItemSet);
        }

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Set berhasil diinputkan"
        ];
        
        return redirect('/scaffolding/set')->with('sweetalert', $sweetalert);

    }

    public function updateFormSet($id){
        //Validasi id set
        $set = Set::find($id);
        if(is_null($set)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Set tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $data = [
            'set' => $set,
            'item' => Item::get(),
            'item_set' => ItemSet::join('item', 'item_set.item_id', '=', 'item.item_id')->where('set_id', $id)->get()
        ];
        return view('scaffolding.set-update-form', $data);
    }

    public function updateSet(Request $request, $id){
        //Validasi id set
        $set = Set::find($id);
        if(is_null($set)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Set tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        //Cek jika Item set belum ditambahkan
        if(!isset($request->item_id)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Terjadi Kesalahan",
                'message' => "Item set belum ditambahkan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        //Ubah set
        $set->update($request->all());

        //Input ulang item set
        $itemId = $request->item_id;
        $itemSetQuantity = $request->item_set_quantity;
        $itemSetOptional = $request->item_set_optional;

        for($i=0;$i<count($itemId);$i++){
            //Cek jika item tidak berubah
            $item = ItemSet::where(['set_id'=>$id, 'item_id'=>$itemId[$i]]);
            //Jika item masih ada maka hanya dirubah kuantitasnya
            if($item->count()==1){
                $dataItemSet = [
                    'item_set_quantity' => $itemSetQuantity[$i],
                    'item_set_optional' => $itemSetOptional[$i]
                ];
                $item->update($dataItemSet);
            //Jika item baru maka disimpan
            }else if($item->count()==0){
                //Restore item yang pernah terhapus jika ditambahkan kembali dan disesuaikan kuantitasnya
                $trashedItem = ItemSet::onlyTrashed()->where(['set_id'=>$id, 'item_id'=>$itemId[$i]]);
                if($trashedItem->count() == 1){
                    $dataItemSet = [
                        'item_set_quantity' => $itemSetQuantity[$i],
                        'item_set_optional' => $itemSetOptional[$i],
                        'deleted_at' => null
                    ];
                    $trashedItem->update($dataItemSet);
                }else{
                    $dataItemSet = [
                        'set_id' => $id,
                        'item_id' => $itemId[$i],
                        'item_set_quantity' => $itemSetQuantity[$i],
                        'item_set_optional' => $itemSetOptional[$i]
                    ];
                    ItemSet::create($dataItemSet);
                }
            }            
        }

        //Hapus item yang tidak digunakan lagi
        $currentItems = ItemSet::select('item_id')->where('set_id', $id)->get();
            
        foreach($currentItems as $c){
            if(!in_array($c->item_id, $itemId)){
                ItemSet::where(['set_id'=>$id, 'item_id'=>$c->item_id])->delete();
            }
        }

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Set berhasil diubah"
        ];
        return redirect('/scaffolding/set')->with('sweetalert', $sweetalert);
    }

    public function deleteSet($id){
        $set = Set::find($id);
        if(is_null($set)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Set tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $set->delete();
        
        $sweetalert =  [
            'state' => "success",
            'title' => "Penghapusan Set Berhasil",
            'message' => "Set berhasil dihapus"
        ];
        return redirect('/scaffolding/set')->with('sweetalert', $sweetalert);
    }
}

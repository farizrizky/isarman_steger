<?php

namespace App\Http\Controllers\Scaffolding;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Rental\RentController;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Stock;

class ItemController extends Controller
{
    public function item(){
        $data = [
            'item' => Item::with('stock')->get()
        ];
        return view('scaffolding.item-list', $data);
    }

    public function createFormItem(){
        return view('scaffolding.item-create-form');
    }

    public function createItem(Request $request){
        $stockTotal = $request->stock_total;
        $item = Item::create($request->all());
        $itemId = $item->item_id;
        
        StockController::stockIn($itemId, 'Input', $stockTotal, 'Item', $itemId);

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Item berhasil diinputkan"
        ];
        return redirect('/scaffolding/item')->with('sweetalert', $sweetalert);
    }

    public function updateFormItem($itemId){
        $item = Item::find($itemId);
        if(is_null($item)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Item tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $data = [
            'item' => $item,
            'stock' => Stock::find($itemId)
        ];
        return view('scaffolding.item-update-form', $data);
    }

    public function updateItem(Request $request, $itemId){
        $item = Item::find($itemId);
        if(is_null($item)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Item tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }
        
        $item->update($request->all());
        $stockTotal = $request->stock_total;
        $stockCurrent = Stock::getStock('total', $itemId);

        if($stockTotal != $stockCurrent){
            if($stockTotal < $stockCurrent){
                $avoidMinusStock = StockController::avoidMinusStock($itemId, $stockTotal);
                if(!$avoidMinusStock){
                    $sweetalert =  [
                        'state' => "error",
                        'title' => "Terjadi Kesalahan",
                        'message' => "Stok total tidak boleh kurang dari stok yang tersedia"
                    ];
                    return back()->with('sweetalert', $sweetalert);
                }
            }
            StockController::stockIn($itemId, 'Perubahan', $stockTotal, 'Item', $itemId);
        }

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Item berhasil diubah"
        ];
        return back()->with('sweetalert', $sweetalert);
    }

    public function deleteItem($itemId){
        $item = Item::find($itemId);
        if(is_null($item)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Item tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $item->delete();

        $sweetalert =  [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Item berhasil dihapus"
        ];
        return redirect('/scaffolding/item')->with('sweetalert', $sweetalert);
    }
}

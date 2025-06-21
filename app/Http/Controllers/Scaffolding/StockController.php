<?php

namespace App\Http\Controllers\Scaffolding;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Stock;
use App\Models\StockFlow;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function stock($category=null){
        $data = [
            'stock' => Stock::with('item')->get()
        ];
        if($category=="tersewa"){
            $category = '-rent';
        }else if($category=="hilang"){
            $category = '-lost';
        }else if($category=="rusak"){
            $category = '-damaged';
        }    
        return view('scaffolding.stock'.$category, $data);
    }

    public function stockOpname(){
        $data = [
            'stock' => Stock::with('item')->get()
        ];
        return view('scaffolding.stock-opname', $data);
    }

    public function stockItem($itemId){
        $item = Item::find($itemId);
        $stock = Stock::with('item')->where('item_id', $itemId)->first();
        if(is_null($item) || is_null($stock)){
            $sweetalert =  [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Item & Stok tidak ditemukan"
            ];
            
            return redirect('/scaffolding/item')->with('sweetalert', $sweetalert);
        }

        $data = [
            'stock' => $stock,
            'stock_flow' => StockFlow::with('item', 'user')->where(['item_id'=>$itemId])->get()
        ];

        return view('scaffolding.stock-item', $data);
        
    }

    public static function stockIn($itemId, $capture, $quantity, $model, $modelId){
        $item = Item::find($itemId);
        
        if(is_null($item)){
            return false;
        }

        if($capture == "Input"){
            $dataStock = [
                'item_id' => $itemId,
                'stock_total' => $quantity,
                'stock_available' => $quantity
            ];

            Stock::create($dataStock);

            $dataStockFlow = [
                'item_id' => $itemId,
                'stock_flow_action' => 'Input',
                'stock_flow_status' => 'Masuk',
                'stock_flow_total_before' => 0,
                'stock_flow_available_before' => 0,
                'stock_flow_decreased_before' => 0,
                'stock_flow_quantity' => $quantity,
                'stock_flow_decreased_after' => 0,
                'stock_flow_available_after' => $quantity,
                'stock_flow_total_after' => $quantity,
                'stock_flow_reference_model' => $model,
                'stock_flow_reference_id' => $modelId,
                'stock_flow_description' => self::stockFlowAction($capture),
                'stock_flow_by' => Auth::user()->id
            ];

            StockFlow::create($dataStockFlow);
            return true;
        }

        $stock = Stock::where('item_id', $itemId);
        $stockItem = $stock->first();
        $stockTotalBefore = $stockItem->stock_total;
        $stockAvailableBefore = $stockItem->stock_available;
        $stockDecreasedBefore = $stockItem->stock_decreased;

        if($capture == "Pembelian"){
            $stockNew = $stockTotalBefore + $quantity;
            $stock->update(['stock_total'=>$stockNew]);
        }else if($capture == "Pengembalian"){
            $stockNew = $stockItem->stock_rented - $quantity;
            $stock->update(['stock_rented'=>$stockNew]);
        }else if($capture == "Selesai Perbaikan"){
            $stockNew = $stockItem->stock_on_repair - $quantity;
            $stock->update(['stock_on_repair'=>$stockNew]);
        }else if($capture == "Perubahan"){
            if($quantity > $stockItem->stock_decreased && $stockItem->stock_decreased > 0){
                return false;
            }else{
                $stockNew = $quantity;
                $stock->update(['stock_total'=>$stockNew]);
            }
        }

        self::recalculateStock($itemId);
        $stockNew = Stock::where('item_id', $itemId)->first();
        $stockTotalAfter = $stockNew->stock_total;
        $stockAvailableAfter = $stockNew->stock_available;
        $stockDecreasedAfter = $stockNew->stock_decreased;

        $dataStockFlow = [
            'item_id' => $itemId,
            'stock_flow_action' => $capture,
            'stock_flow_status' => $capture == 'Perubahan' ? 'Diubah' : 'Masuk',
            'stock_flow_total_before' => $stockTotalBefore,
            'stock_flow_available_before' => $stockAvailableBefore,
            'stock_flow_decreased_before' => $stockDecreasedBefore,
            'stock_flow_quantity' => $quantity,
            'stock_flow_decreased_after' => $stockDecreasedAfter,
            'stock_flow_available_after' => $stockAvailableAfter,
            'stock_flow_total_after' => $stockTotalAfter,
            'stock_flow_reference_model' => $model,
            'stock_flow_reference_id' => $modelId,
            'stock_flow_description' => self::stockFlowAction($capture),
            'stock_flow_by' => Auth::user()->id
        ];

        StockFlow::create($dataStockFlow);
        return true;
    }

    public static function stockOut($itemId, $release, $quantity, $model, $modelId){
        $item = Item::find($itemId);
        if(is_null($item)){
            return false;
        }

        $stock = Stock::where('item_id', $itemId);
        $stockItem = $stock->first();
        $stockTotalBefore = $stockItem->stock_total;
        $stockAvailableBefore = $stockItem->stock_available;
        $stockDecreasedBefore = $stockItem->stock_decreased;

        if($quantity > $stockAvailableBefore ){
            return false;
        }
        
        if($release == "Penyewaan"){
            $stockNew = $stockItem->stock_rented + $quantity;
            $stock->update(['stock_rented'=>$stockNew]);
        }else if($release == "Perbaikan"){
            $stockNew = $stockItem->stock_on_repair + $quantity;
            $stock->update(['stock_on_repair'=>$stockNew]);
        }else if($release == "Kehilangan"){
            $stockNew = $stockItem->stock_lost + $quantity;
            $stock->update(['stock_lost'=>$stockNew]);
        }else if($release == "Kerusakan"){
            $stockNew = $stockItem->stock_damaged + $quantity;
            $stock->update(['stock_damaged'=>$stockNew]);
        }else if($release == "Bermasalah"){
            $stockNew = $stockItem->stock_dispute + $quantity;
            $stock->update(['stock_dispute'=>$stockNew]);
        }else if($release == "Penghapusan"){
            $avoidMinusStock = self::avoidMinusStock($itemId, $quantity);
            if($avoidMinusStock){
                $stockNew = $stockItem->stock_total - $quantity;
                $stock->update(['stock_total'=>$stockNew]);
            }else{
                return false;
            }
        }

        self::recalculateStock($itemId);
        $stockNew = Stock::where('item_id', $itemId)->first();
        $stockTotalAfter = $stockNew->stock_total;
        $stockAvailableAfter = $stockNew->stock_available;
        $stockDecreasedAfter = $stockNew->stock_decreased;

        $dataStockFlow = [
            'item_id' => $itemId,
            'stock_flow_action' => $release,
            'stock_flow_status' => 'Keluar',
            'stock_flow_total_before' => $stockTotalBefore,
            'stock_flow_available_before' => $stockAvailableBefore,
            'stock_flow_decreased_before' => $stockDecreasedBefore,
            'stock_flow_quantity' => $quantity,
            'stock_flow_decreased_after' => $stockDecreasedAfter,
            'stock_flow_available_after' => $stockAvailableAfter,
            'stock_flow_total_after' => $stockTotalAfter,
            'stock_flow_reference_model' => $model,
            'stock_flow_reference_id' => $modelId,
            'stock_flow_description' => self::stockFlowAction($release),
            'stock_flow_by' => Auth::user()->id
        ];

        StockFlow::create($dataStockFlow);

        return true;

    }

    public static function insufficientStock($itemId, $quantityOut){
        $stock = Stock::where('item_id', $itemId)->first();

        if($quantityOut > $stock->stock_available){
            return true;
        }else{
            return false;
        }
        
    }

    public static function avoidMinusStock($itemId, $quantity){
        $stock = Stock::find($itemId);
        if(is_null($stock)){
            return false;
        }

        if($stock->stock_decreased < $quantity && $stock->stock_decreased > 0){
            return false;     
        }else{
            return true;
        }
    }

    public static function recalculateStock($itemId){
        $stockData = Stock::where('item_id', $itemId)->first();
        $stockDecreased = $stockData->stock_rented + $stockData->stock_damaged + $stockData->stock_lost + $stockData->stock_dispute + $stockData->stock_unknown + $stockData->stock_on_repair;
        $stockTotal = $stockData->stock_total;
        $stockAvailable = $stockTotal - $stockDecreased;
        $stockData->update(['stock_available'=>$stockAvailable, 'stock_decreased'=>$stockDecreased]);
        return true;
    }

    public static function stockFlowAction($action){
        $description = "";

        if($action=="Input"){
            $description = "Penginputan item baru";
        }else if($action=="Pembelian"){
            $description = "Pembelian item";
        }else if($action=="Penyewaan"){
            $description = "Item disewa";
        }else if($action=="Pengembalian"){
            $description = "Pengembalian sewa item";
        }else if($action=="Perubahan"){
            $description = "Stok item diubah";
        }else if($action=="Penghapusan"){
            $description = "Data pembelian item dihapus";
        }else if($action=="Perbaikan"){
            $description = "Item diperbaiki";
        }else if($action=="Kehilangan"){
            $description = "Item hilang saat sewa";
        }else if($action=="Kerusakan"){
            $description = "Item rusak saat sewa";
        }else if($action=="Bermasalah"){
            $description = "Penyewaan bermasalah";
        }else if($action=="Selesai Perbaikan"){
            $description = "Item selesai diperbaiki";
        }else if($action=="Stock Opname"){
            $description = "Stock Opname Item";
        }else{
            return false;
        }

        return $description;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    use HasFactory;
    protected $table = "stock";
    protected $primaryKey = "stock_id";
    protected $guarded = ['stock_id'];

    protected $dates = ['deleted_at'];

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
    
    public static function recalculateStock($itemId){
        $instance = new self();

        $stockData = $instance->where('item_id', $itemId)->first();
        $stockDecreased = $stockData->stock_rented + $stockData->stock_damaged + $stockData->stock_lost + $stockData->stock_dispute + $stockData->stock_unknown + $stockData->stock_on_repair;
        $stockTotal = $stockData->stock_total;
        $stockAvailable = $stockTotal - $stockDecreased;
        $stockData->update(['stock_available'=>$stockAvailable, 'stock_decreased'=>$stockDecreased]);
    }

    public static function avoidMinusStock($itemId, $decreasedTotal){
        $instance = new self();
        
        $stockData = $instance->where('item_id', $itemId)->first();
        $stockDecreased = $stockData->stock_decreased;
        $stockTotal = $stockData->stock_total;
        
        $minusStock = false;
        
        if($decreasedTotal <  $stockDecreased){
            $minusStock = true;
        }

        return $minusStock;
    }

    public static function getStock($type, $itemId){
        $instance = new self();

        $stock = $instance->where('item_id', $itemId)->first()->toArray();
        return $stock['stock_'.$type];
    }

    public static function saveStock($type, $itemId, $amount){
        $instance = new self();

        $stock = $instance->where('item_id', $itemId);
        $stock->update(['stock_'.$type => $amount]);
    }

    public static function getAvailableSet($setId){
        $instance = new self();

        $setItem = ItemSet::where(['set_id'=>$setId, 'item_set_optional'=>1])->get();
        $maximumSet = [];

        if($setItem->isEmpty()){
            $setItem = ItemSet::where(['set_id'=>$setId])->get();
        }

        foreach($setItem as $si){
            $itemStock = $instance->getStock('available', $si->item_id);
            $setStock = floor($itemStock/$si->item_set_quantity);
            array_push($maximumSet, $setStock);
        }

        sort($maximumSet);

        return $maximumSet[0];
    }
}

<?php
namespace App\Helpers;

use App\Models\Stock;

class StockHelper {

    public static function getAvailableSet($setId) { 
        $setStock = Stock::getAvailableSet($setId);
        return $setStock;
    }

    public static function getStock($type, $itemId){
        $itemStock = Stock::getStock($type, $itemId);
        return $itemStock;
    }
    
}
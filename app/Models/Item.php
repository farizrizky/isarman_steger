<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "item";
    protected $primaryKey = "item_id";
    protected $guarded = ['item_id'];

    protected $dates = ['deleted_at'];

    public function itemSet(){
        return $this->hasMany(ItemSet::class, 'item_id', 'item_id');
    }

    public function stock(){
        return $this->hasOne(Stock::class, 'item_id', 'item_id', '');
    }

    public function stockFlow(){
        return $this->hasMany(StockFlow::class, 'item_id', 'item_id');
    }

    public function purchaseItem(){
        return $this->hasMany(Purchase::class, 'item_id', 'item_id');
    }

    public function rentItem(){
        return $this->hasMany(RentItem::class, 'item_id', 'item_id');
    }
    
    public function rent(){
        return $this->hasManyThrough(Rent::class, RentItem::class, 'item_id', 'rent_id', 'item_id', 'rent_id')->withTrashed();
    }

    public function rentReturnItem(){
       return $this->hasMany(RentReturnItem::class, 'item_id', 'item_id');
    }
}

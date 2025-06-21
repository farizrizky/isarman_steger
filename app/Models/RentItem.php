<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentItem extends Model
{
    use HasFactory;
    protected $table = "rent_item";
    protected $primaryKey = "rent_item_id";
    protected $guarded = ["rent_item_id"];

    public function rent(){
        return $this->belongsTo(Rent::class, 'rent_id', 'rent_id')->withTrashed();
    }

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'item_id')->withTrashed();
    }

    public function rentSet(){
        return $this->belongsTo(RentSet::class, 'rent_set_id', 'rent_set_id');
    }

    public function Set(){
        return $this->hasManyThrough(Set::class, RentSet::class, 'rent_set_id', 'set_id', 'rent_set_id', 'set_id')->withTrashed();
    }

    public function itemSet(){
        return $this->hasOneThrough(Item::class, ItemSet::class, 'item_id', 'item_id', 'item_id', 'item_id');
    }

    public function rentReturnItem(){
        return $this->hasManyThrough(RentReturnItem::class, RentReturn::class, 'rent_id', 'rent_return_id', 'rent_id', 'rent_return_id');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentReturnItem extends Model
{
    use HasFactory;
    protected $table = "rent_return_item";
    protected $primaryKey = "rent_return_item_id";
    protected $guarded = ["rent_return_item_id"];

    public function rent(){
        return $this->hasManyThrough(Rent::class, RentReturn::class, 'rent_return_id', 'rent_id', 'rent_return_id', 'rent_id')->withTrashed();
    }

    public function rentReturn(){
        return $this->belongsTo(RentReturn::class, 'rent_return_id', 'rent_return_id');
    }

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'item_id')->withTrashed();;
    }

    public function rentItem(){
        return $this->hasManyThrough(RentItem::class, Rent::class, 'rent_id', 'rent_id', 'rent_id', 'rent_id');
    }
}

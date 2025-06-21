<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemSet extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "item_set";
    protected $primaryKey = "item_set_id";
    protected $guarded = ['item_set_id'];
    
    protected $dates = ['deleted_at'];

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'item_id')->withTrashed();
    }

    public function set(){
        return $this->belongsTo(Set::class, 'set_id', 'set_id')->withTrashed();
    }

    public function rentItem(){
        return $this->hasManyThrough(RentItem::class, Item::class, 'item_id', 'item_id', 'item_id', 'item_id');
    }
    
}

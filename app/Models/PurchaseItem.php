<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;
    protected $table = "purchase_item";
    protected $primaryKey = "purchase_item_id";
    protected $guarded = ['purchase_item_id'];

    public function purchase(){
        return $this->belongsTo(Purchase::class, 'purchase_id', 'purchase_id')->withTrashed();
    }

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'item_id')->withTrashed();
    }
}

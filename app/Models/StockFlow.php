<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockFlow extends Model
{
    use HasFactory;
    protected $table = "stock_flow";
    protected $primaryKey = "stock_id";
    protected $guarded = ['stock_id'];

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function user(){
        return $this->hasMany(User::class, 'id', 'stock_flow_by')->withTrashed();
    }
}

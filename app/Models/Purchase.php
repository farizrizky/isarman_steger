<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "purchase";
    protected $primaryKey = "purchase_id";
    protected $guarded = ['purchase_id'];

    protected $dates = ['deleted_at'];

    public function purchaseItem(){
        return $this->hasMany(PurchaseItem::class, 'purchase_id', 'purchase_id');
    }

    public function purchaseAcceptedEvidence(){
        return $this->hasOne(PurchaseAcceptedEvidence::class, 'purchase_id', 'purchase_id');
    }
}

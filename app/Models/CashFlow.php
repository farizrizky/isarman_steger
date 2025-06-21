<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashFlow extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "cash_flow";
    protected $primaryKey = "cash_flow_id";
    protected $guarded = ['cash_flow_id'];

    protected $dates = ['deleted_at'];

    public function cash()
    {
        return $this->belongsTo(Cash::class, 'cash_flow_reference_id', 'cash_id');
    }
}

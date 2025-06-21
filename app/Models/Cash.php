<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cash extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "cash";
    protected $primaryKey = "cash_id";
    protected $guarded = ['cash_id', 'cash_initial'];

    protected $dates = ['deleted_at'];

    public function cashFlow()
    {
        return $this->hasMany(CashFlow::class, 'cash_flow_reference_id', 'cash_id');
    }
}

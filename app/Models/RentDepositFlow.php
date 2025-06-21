<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentDepositFlow extends Model
{
    use HasFactory;
    protected $table = "rent_deposit_flow";
    protected $primaryKey = "rent_deposit_flow_id";
    protected $guarded = ["rent_deposit_flow_id"];

    public function rentDeposit(){
        return $this->belongsTo(RentDeposit::class, 'rent_deposit_id', 'rent_deposit_id');
    }

}

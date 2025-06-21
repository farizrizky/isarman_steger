<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentDeposit extends Model
{
    use HasFactory;
    protected $table = "rent_deposit";
    protected $primaryKey = "rent_deposit_id";
    protected $guarded = ['rent_deposit_id'];

    public function rent()
    {
        return $this->belongsTo(Rent::class, 'rent_id', 'rent_id')->withTrashed();
    }

    public function renter()
    {
        return $this->belongsTo(Renter::class, 'renter_id', 'renter_id')->withTrashed();
    }

}

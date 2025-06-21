<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Renter extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "renter";
    protected $primaryKey = "renter_id";
    protected $guarded = ["renter_id"];

    protected $dates = ['deleted_at'];

    public function rent(){
        return $this->hasMany(Rent::class, 'renter_id', 'renter_id');
    }

    public function rentDeposit(){
        return $this->hasMany(RentDeposit::class, 'renter_id', 'renter_id');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rent extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "rent";
    protected $primaryKey = "rent_id";
    protected $guarded = ['rent_id'];
   
    protected $dates = ['deleted_at'];

    public function renter(){
        return $this->belongsTo(Renter::class, 'renter_id', 'renter_id')->withTrashed();
    }

    public function rentItem(){
        return $this->hasMany(RentItem::class, 'rent_id', 'rent_id');
    }

    public function rentSet(){
        return $this->hasMany(RentSet::class, 'rent_id', 'rent_id');
    }

    public function set(){
        return $this->hasManyThrough(Set::class, RentSet::class, 'rent_id', 'set_id', 'rent_id', 'set_id')->withTrashed();
    }

    public function item(){
        return $this->hasManyThrough(Item::class, RentItem::class, 'rent_id', 'item_id', 'rent_id', 'item_id')->withTrashed();
    }

    public function stock(){
        return $this->hasManyThrough(Stock::class, RentItem::class, 'rent_id', 'item_id', 'rent_id', 'item_id');
    }

    public function rentReturn(){
        return $this->hasOne(RentReturn::class, 'rent_id', 'rent_id');
    }

    public function rentReturnItem(){
        return $this->hasManyThrough(RentReturnItem::class, RentReturn::class, 'rent_id', 'rent_return_id', 'rent_id', 'rent_return_id');
    }

    public function rentDeposit(){
        return $this->hasOne(RentDeposit::class, 'rent_id', 'rent_id');
    }
}

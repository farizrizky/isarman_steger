<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Set extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "set";
    protected $primaryKey = "set_id";
    public $fillable = [
        'set_name',
        'set_price_2_weeks',
        'set_price_per_month'
    ];
    protected $dates = ['deleted_at'];

    public function itemSet(){
        return $this->hasMany(ItemSet::class, 'set_id', 'set_id');
    }

    public function rentSet(){
        return $this->hasMany(RentSet::class, 'set_id', 'set_id');
    }

    public function rentItem(){
        return $this->hasManyThrough(RentItem::class, RentSet::class, 'set_id', 'rent_set_id', 'set_id', 'rent_set_id');
    }

    public function rent(){
        return $this->hasManyThrough(Rent::class, RentSet::class, 'set_id', 'rent_id', 'set_id', 'rent_id');
    }

    public function item(){
        return $this->hasManyThrough(Item::class, ItemSet::class, 'set_id', 'item_id', 'set_id', 'item_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentSet extends Model
{
    use HasFactory;
    protected $table = "rent_set";
    protected $primaryKey = "rent_set_id";
    protected $guarded = ["rent_set_id"];
    
    public function rent(){
        return $this->belongsTo(Rent::class, 'rent_id', 'rent_id');
    }

    public function set(){
        return $this->belongsTo(Set::class, 'set_id', 'set_id')->withTrashed();;
    }

    public function rentItem(){
        return $this->hasMany(RentItem::class, 'rent_set_id', 'rent_set_id');
    }
}



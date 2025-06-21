<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentReturn extends Model
{
    use HasFactory;
    protected $table = "rent_return";
    protected $primaryKey = "rent_return_id";
    protected $guarded = ["rent_return_id"];

    public function rent(){
        return $this->belongsTo(Rent::class, 'rent_id', 'rent_id')->withTrashed();
    }

    public function rentReturnItem(){
        return $this->hasMany(RentReturnItem::class, 'rent_return_id', 'rent_return_id');
    }
    
}

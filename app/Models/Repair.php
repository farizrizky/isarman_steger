<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repair extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "repair";
    protected $primaryKey = "repair_id";
    protected $guarded = ['repair_id'];
   
    protected $dates = ['deleted_at'];

    public function repairItem(){
        return $this->hasMany(RepairItem::class, 'repair_id', 'repair_id');
    }
}

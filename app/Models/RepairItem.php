<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairItem extends Model
{
    use HasFactory;

    protected $table = "repair_item";
    protected $primaryKey = "repair_item_id";
    protected $guarded = ["repair_item_id"];

    public function repair()
    {
        return $this->belongsTo(Repair::class, 'repair_id', 'repair_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}

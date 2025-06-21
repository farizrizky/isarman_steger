<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseAcceptedEvidence extends Model
{
    use HasFactory;
    protected $table = "purchase_accepted_evidence";
    protected $primaryKey = "purchase_accepted_evidence_id";
    protected $guarded = ['purchase_accepted_evidence_id'];

    public function purchase(){
        return $this->belongsTo(Purchase::class, 'purchase_id', 'purchase_id')->withTrashed();
    }
}

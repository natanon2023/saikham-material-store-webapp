<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationMaterial extends Model
{
    use HasFactory;

    protected $table = 'quotation_materials';
    protected $fillable = [
        'quotation_id',
        'material_type',
        'description',
        'lot_number',
        'unit_price',
        'quantity',
        'total_price',
        'remark'
    ];


    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'total_product_amount',
        'total_expense_amount', 'total_labor_amount', 'service_charge_amount',
        'vat_amount', 'grand_total', 'version', 'status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function quotationMaterials()
    {
        return $this->hasMany(QuotationMaterial::class, 'quotation_id');
    }

} 
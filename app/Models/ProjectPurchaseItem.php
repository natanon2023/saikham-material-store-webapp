<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_purchase_id',
        'material_id',
        'quantity',
        'unit_price',
        'total_price',
        'remark',
    ];

    public function projectPurchase()
    {
        return $this->belongsTo(ProjectPurchase::class, 'project_purchase_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    
}

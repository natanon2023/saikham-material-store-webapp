<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSetItem extends Model
{
    use HasFactory;

    protected $table = "product_set_items";

    protected $fillable = [
        'product_set_id',
        'material_id',
        'status',
        'price'
    ];

    public function productset()
    {
        return $this->belongsTo(ProductSet::class, 'product_set_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function customerneed()
    {
        return $this->hasMany(Customerneed::class,'product_set_id');
    }
}

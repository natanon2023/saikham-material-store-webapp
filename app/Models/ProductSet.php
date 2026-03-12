<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "product_sets";

    protected $fillable = [
        'product_set_name_id',
        'detail',
        'created_by',
        'product_image',
        'aluminum_surface_finish_id',
        'glass_colouritem_id',
        'glasstype_id'
    ];

    

    public function productSetName(){
        return $this->belongsTo(ProductSetName::class,'product_set_name_id');
    }

    public function aluminumSurfaceFinish()
    {
        return $this->belongsTo(AluminumSurfaceFinish::class, 'aluminum_surface_finish_id');
    }

    public function glasscolouritem()
    {
        return $this->belongsTo(ColourItem::class, 'glass_colouritem_id');
    }

    public function glasstype(){
        return $this->belongsTo(GlassType::class,'glasstype_id');
    }



    public function productsetitem(){
        return $this->hasMany(ProductSetItem::class,'product_set_id');
    }

    

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    

    
}

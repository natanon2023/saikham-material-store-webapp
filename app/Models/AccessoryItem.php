<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryItem extends Model
{
    use HasFactory;

    protected $table = 'accessory_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'accessory_type_id',
        'current_stock',
        'minimum_stock',
        'cost_per_unit',
        'aluminum_surface_finish_id',
        'unit_id',
        'image_accessory_item',
        'dealer_id',
        'price'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'accessory_item_id', 'id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'accessory_item_id', 'id');
    }

    public function accessoryType()
    {
        return $this->belongsTo(AccessoryType::class, 'accessory_type_id');
    }

    public function aluminumSurfaceFinish()
    {
        return $this->belongsTo(AluminumSurfaceFinish::class, 'aluminum_surface_finish_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class,'dealer_id');
    }

    public function price()
    {
        return $this->hasMany(Price::class,'accessory_item_id');
    }
}

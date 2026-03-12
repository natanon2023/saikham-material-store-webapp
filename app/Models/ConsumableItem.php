<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumableItem extends Model
{
    use HasFactory;

    protected $table = 'consumable_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'consumable_type_id',
        'current_stock',
        'minimum_stock',
        'cost_per_unit',
        'unit_id',
        'image_consumable_item',
        'dealer_id',
        'price'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'consumable_item_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function consumabletype()
    {
        return $this->belongsTo(ConsumableType::class, 'consumable_type_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'consumable_item_id', 'id');
    }

    public function stockEditLogs()
    {
        return $this->hasMany(StockEditLog::class, 'sub_item_id')->where('material_type', 'consumable');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class,'dealer_id');
    }

    public function price()
    {
        return $this->hasMany(Price::class,'consumable_item_id');
    }
}

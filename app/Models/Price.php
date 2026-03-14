<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $table = 'prices';
    protected $primaryKey = 'id';

    protected $fillable = [
        'material_id',
        'accessory_item_id',
        'consumable_item_id',
        'aluminium_length_id',
        'glass_size_id',
        'tool_item_id',
        'dealer_id',
        'quantity',
        'price',
        'lot',
        'sumquantity'
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class,'dealer_id');
    }

    public function accessoryitem()
    {
        return $this->belongsTo(AccessoryItem::class, 'accessory_item_id');
    }

    public function consumableitem()
    {
        return $this->belongsTo(ConsumableItem::class, 'consumable_item_id');
    }

    public function aluminiumlength()
    {
        return $this->belongsTo(AluminiumLength::class, 'aluminium_length_id');
    }


    public function glassSize()
    {
        return $this->belongsTo(GlassSize::class, 'glass_size_id');
    }

    public function toolitem()
    {
        return $this->belongsTo(ToolItem::class, 'tool_item_id');
    }

    

    public function stockeditlog(){
        return $this->hasMany(StockEditLog::class,'price_id');
    }

    public function materiallog(){
        return $this->hasMany(MaterialLog::class,'price_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }








}

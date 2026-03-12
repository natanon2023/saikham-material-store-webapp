<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'materials';
    protected $primaryKey = 'id';

    protected $fillable = [
        'material_type',
        'dealer_id',
        'aluminium_item_id',
        'glass_item_id',
        'accessory_item_id',
        'tool_item_id',
        'consumable_item_id',
        'status',
        'user_id'
    ];

    protected $dates = [
        'deleted_at',
    ];




    public function aluminiumItem()
    {
        return $this->hasOne(AluminiumItem::class, 'id', 'aluminium_item_id');
    }

    public function glassItem()
    {
        return $this->hasOne(GlassItem::class, 'id', 'glass_item_id');
    }

    public function accessoryItem()
    {
        return $this->hasOne(AccessoryItem::class, 'id', 'accessory_item_id');
    }

    public function toolItem()
    {
        return $this->hasOne(ToolItem::class, 'id', 'tool_item_id');
    }

    public function consumableItem()
    {
        return $this->hasOne(ConsumableItem::class, 'id', 'consumable_item_id');
    }

    public function materialLogs()
    {
        return $this->hasMany(MaterialLog::class, 'material_id');
    }

    public function price()
    {
        return $this->hasMany(Price::class, 'material_id');
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id');
    }

    public function getStockableItem($subItemId = null)
    {
        switch ($this->material_type) {
            case 'aluminium':
                return AluminiumLength::find($subItemId);
            case 'glass':
                return GlassSize::find($subItemId);
            case 'accessory':
                return $this->accessoryItem;
            case 'tool':
                return $this->toolItem;
            case 'consumable':
                return $this->consumableItem;
            default:
                return null;
        }
    }

    public function stockEditLogs()
    {
        return $this->hasMany(StockEditLog::class);
    }

    public function productsetitem()
    {
        return $this->hasMany(ProductSetItem::class, 'material_id');
    }


    
}

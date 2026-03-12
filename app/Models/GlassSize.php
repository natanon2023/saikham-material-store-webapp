<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlassSize extends Model
{
    use HasFactory;

    protected $table = 'glass_sizes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'glass_item_id',
        'width_meter',
        'length_meter',
        'thickness'
    ];

    public function glassItem()
    {
        return $this->belongsTo(GlassItem::class, 'glass_item_id');
    }

    public function materialLogs()
    {
        return $this->hasMany(MaterialLog::class, 'glass_size_id');
    }

    public function stockEditLogs()
    {
        return $this->hasMany(StockEditLog::class, 'sub_item_id')->where('material_type', 'glass');
    }

    
    public function price()
    {
        return $this->hasMany(Price::class,'glass_size_id');
    }
}

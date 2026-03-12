<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolItem extends Model
{
    use HasFactory;

    protected $table = 'tool_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tool_type_id',
        'description',
        'tool_item_code',
        'current_stock',
        'unit_id',
        'image_tool_item',
    ];



    public function toolType()
    {
        return $this->belongsTo(ToolType::class, 'tool_type_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'tool_item_id', 'id');
    }


    public function materials()
    {
        return $this->hasMany(Material::class, 'tool_item_id', 'id');
    }



    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function stockEditLogs()
    {
        return $this->hasMany(StockEditLog::class, 'sub_item_id')->where('material_type', 'tool');
    }

    


}

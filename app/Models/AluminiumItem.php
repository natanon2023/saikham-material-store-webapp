<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AluminiumItem extends Model
{
    use HasFactory;

    protected $table = 'aluminium_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'aluminium_profile_types_id',
        'aluminum_surface_finish_id',
        'image_aluminium_item'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'aluminium_item_id', 'id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'aluminium_item_id', 'id');
    }

    public function aluminiumType()
    {
        return $this->belongsTo(AluminiumProfileType::class, 'aluminium_profile_types_id');
    }


    public function aluminumSurfaceFinish()
    {
        return $this->belongsTo(AluminumSurfaceFinish::class, 'aluminum_surface_finish_id');
    }


    public function aluminiumLengths()
    {
        return $this->hasOne(AluminiumLength::class, 'aluminium_item_id');
    }
    
    public function stockEditLogs()
    {
        return $this->hasMany(StockEditLog::class, 'sub_item_id')
            ->where('material_type', 'accessory');
    }
}

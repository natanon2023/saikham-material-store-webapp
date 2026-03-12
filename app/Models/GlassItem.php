<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlassItem extends Model
{
    use HasFactory;

    protected $table = 'glass_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'glass_type_id',
        'colouritem_id',
        'image_glass_item'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'glass_item_id', 'id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'glass_item_id', 'id');
    }

    public function glassType()
    {
        return $this->belongsTo(GlassType::class, 'glass_type_id');
    }

    public function colourItem()
    {
        return $this->belongsTo(ColourItem::class, 'colouritem_id');
    }

    public function glassSize()
    {
        return $this->hasOne(GlassSize::class, 'glass_item_id');
    }
}

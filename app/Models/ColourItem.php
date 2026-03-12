<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColourItem extends Model
{
    use HasFactory;

    protected $table = 'colouritems';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function glassItems()
    {
        return $this->hasMany(GlassItem::class, 'colouritem_id');
    }

    public function productset()
    {
        return $this->hasMany(ProductSet::class, 'glass_colouritem_id');
    }

    






}

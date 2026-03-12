<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlassType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'glasstypes'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function glassItems()
    {
        return $this->hasMany(GlassItem::class, 'glass_type_id');
    }

    public function productset()
    {
        return $this->hasMany(ProductSet::class, 'glasstype_id');
    }

    
}

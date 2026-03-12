<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table ='units';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];


    public function material()
    {
        return $this->hasMany(Material::class, 'unit_id');
    }

    public function consumableItems()
    {
        return $this->hasMany(ConsumableItem::class, 'unit_id');
    }

    public function toolItems()
    {
        return $this->hasMany(ToolItem::class, 'unit_id');
    }

}

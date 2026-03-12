<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessoryType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'accessorytypes'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function accessoryItems()
    {
        return $this->hasMany(AccessoryItem::class, 'accessory_type_id');
    }
}

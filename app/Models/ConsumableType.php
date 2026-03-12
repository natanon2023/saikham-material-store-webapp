<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsumableType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'consumable_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function consumableItem()
    {
        return $this->hasMany(ConsumableItem::class, 'consumable_type_id');
    }




}

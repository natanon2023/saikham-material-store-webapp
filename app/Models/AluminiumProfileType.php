<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AluminiumProfileType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aluminium_profile_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function aluminiumItems()
    {
        return $this->hasMany(AluminiumItem::class, 'aluminium_profile_types_id'); 
    }
}

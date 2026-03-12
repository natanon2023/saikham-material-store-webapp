<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThaiGeographie extends Model
{
    use HasFactory;

    protected $table = 'thai_geographies';
    public $timestamps = false;

    public function thai_geographies()
    {
        return $this->hasMany(ThaiGeographie::class, 'geography_id');
    }


}

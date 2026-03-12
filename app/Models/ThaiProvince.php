<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThaiProvince extends Model
{
    use HasFactory;

    protected $table = 'thai_provinces';
    public $timestamps = false;

    public function amphures()
    {
        return $this->hasMany(ThaiAmphure::class, 'province_id');
    }

    public function thai_geographies()
    {
        return $this->belongsTo(ThaiGeographie::class, 'geography_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'province_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'province_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "customers";

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'house_number',
        'village',
        'alley',
        'road',
        'province_id',
        'amphure_id',
        'tambon_id',
        'zip_code',
        'house_name',
        'prefix',
        'gender',
        'tax_id_number'
    ];

    
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    
    public function province()
    {
        return $this->belongsTo(ThaiProvince::class);
    }

    public function amphure()
    {
        return $this->belongsTo(ThaiAmphure::class);
    }

    public function tambon()
    {
        return $this->belongsTo(ThaiTambon::class);
    }

   
}

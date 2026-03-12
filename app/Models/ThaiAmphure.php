<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThaiAmphure extends Model
{
    use HasFactory;

    protected $table = 'thai_amphures';
    public $timestamps = false;

    public function province()
    {
        return $this->belongsTo(ThaiProvince::class, 'province_id');
    }

    public function tambons()
    {
        return $this->hasMany(ThaiTambon::class, 'amphure_id');
    }

   

    public function customers()
    {
        return $this->hasMany(Customer::class, 'amphure_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'amphure_id');
    }

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThaiTambon extends Model
{
    use HasFactory;

    protected $table = 'thai_tambons'; 
    public $timestamps = false;

    public function amphure()
    {
        return $this->belongsTo(ThaiAmphure::class, 'amphure_id');
    }

     public function customers()
    {
        return $this->hasMany(Customer::class, 'tambon_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'tambon_id');
    }
}

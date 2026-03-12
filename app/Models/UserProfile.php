<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'house_number',
        'moo',
        'road',
        'village',
        'province_id',
        'amphure_id',
        'tambon_id',
        'birth_date',
        'alley'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function province()
    {
        return $this->belongsTo(ThaiProvince::class, 'province_id');
    }

    public function amphure()
    {
        return $this->belongsTo(ThaiAmphure::class, 'amphure_id');
    }

    public function tambon()
    {
        return $this->belongsTo(ThaiTambon::class, 'tambon_id');
    }
}

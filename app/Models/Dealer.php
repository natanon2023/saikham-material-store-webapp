<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dealers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
    ];

    protected $dates = [
        'deleted_at',
    ];

   public function price()
    {
        return $this->hasMany(Price::class,'dealer_id');
    }

   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSetName extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function productset()
    {
        return $this->hasMany(ProductSet::class, 'product_set_name_id');
    }

    

    

}

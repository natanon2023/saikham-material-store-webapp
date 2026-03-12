<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageTypeName extends Model
{
    use HasFactory;
    use SoftDeletes; 

    protected $table = 'image_type_names';

    protected $fillable = [
        'name',
    ];

    public function projectimage()
    {
        return $this->hasMany(Projectimages::class, 'image_type');
    }

    
}

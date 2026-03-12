<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projectimages extends Model
{
    use HasFactory;

    protected $table = "project_images";
    protected $fillable = [
        'project_id',
        'image_path',
        'image_type',
        'description'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function imagetype()
    {
        return $this->belongsTo(ImageTypeName::class, 'image_type');
    }

    

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerNeed extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_needs';
    protected $fillable = [
        'project_id',
        'product_set_id',
        'location',
        'quantity',
        'created_by',
        'width',
        'height',
        'installation_image',
        'note_need',
        'imageafter'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function productset()
    {
        return $this->belongsTo(ProductSet::class, 'product_set_id');
    }

    public function projectImage()
    {
        return $this->belongsTo(Projectimages::class, 'location', 'id');
    }



    
}

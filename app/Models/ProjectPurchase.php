<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'total_amount',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function items()
    {
        return $this->hasMany(ProjectPurchaseItem::class, 'project_purchase_id');
    }
}

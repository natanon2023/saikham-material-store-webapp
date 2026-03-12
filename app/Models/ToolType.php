<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tooltypes'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function toolItems()
    {
        return $this->hasMany(ToolItem::class, 'tool_type_id');
    }
}

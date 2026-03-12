<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MaterialLog extends Model
{
    use HasFactory;

    protected $table = 'material_logs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'material_id',
        'user_id',
        'direction',
        'quantity',
        'lot',
        'price_id'
    ];

    protected $casts = [
        'action_date' => 'datetime:Asia/Bangkok',
    ];


    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function price()
    {
        return $this->belongsTo(Price::class, 'price_id');
    }

    

    

    

   
    
    


}

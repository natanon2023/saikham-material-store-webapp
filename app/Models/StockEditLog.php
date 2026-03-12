<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEditLog extends Model
{
    use HasFactory;

    protected $table = 'stock_edit_logs';

    protected $fillable = [
        'material_id',
        'price_id' ,
        'user_id',
        'old_quantity',
        'new_quantity',
        'old_price',
        'new_price',
        'old_length_meter',
        'new_length_meter',
        'old_width_meter',
        'new_width_meter',
        'old_thickness',
        'new_thickness',
        'reason'
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

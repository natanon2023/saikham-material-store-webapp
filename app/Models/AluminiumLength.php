<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AluminiumLength extends Model
{
    use HasFactory;

    protected $table = 'aluminium_lengths';
    protected $primaryKey = 'id';

    protected $fillable = [
        'aluminium_item_id',
        'length_meter'
    ];

    public function aluminiumItem()
    {
        return $this->belongsTo(AluminiumItem::class, 'aluminium_item_id');
    }

    public function materialLogs()
    {
        return $this->hasMany(MaterialLog::class, 'aluminium_length_id');
    }

    public function stockEditLogs()
    {
        return $this->hasMany(StockEditLog::class, 'sub_item_id')
            ->where('material_type', 'aluminium');
    }


    public function price()
    {
        return $this->hasMany(Price::class,'aluminium_length_id');
    }
}

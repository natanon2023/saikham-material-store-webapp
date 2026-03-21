<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'withdrawal_id',
        'material_id',
        'lot',
        'quantity',
        'direction',
    ];

    public function withdrawal()
    {
        return $this->belongsTo(Withdrawal::class, 'withdrawal_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function withdrawalitem(){
        return $this->hasMany(ProjectIssue::class, 'withdrawal_item_damaged');
    }

    
}
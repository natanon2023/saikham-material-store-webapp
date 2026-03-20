<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalItemLog extends Model
{
    protected $fillable = [
        'withdrawal_item_id',
        'old_quantity',
        'new_quantity',
        'reason',
        'edited_by'
    ];

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectExpense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'expense_type_id',
        'description',
        'amount',
        'expense_date',
        'created_by'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function type()
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

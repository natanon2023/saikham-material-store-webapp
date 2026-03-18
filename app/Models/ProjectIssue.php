<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectIssue extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'project_id', 'reported_by', 'category', 'description', 'status','withdrawal_item_damaged','damaged_amount','refilled_amount'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function images()
    {
        return $this->hasMany(IssueImage::class, 'issue_id');
    }

    public function withdrawalitemdamaged(){
        return $this->belongsTo(WithdrawalItem::class,'withdrawal_item_damaged');
    }
}

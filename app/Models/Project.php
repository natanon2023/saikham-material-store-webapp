<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_code',
        'project_name_id',
        'customer_id',
        'status',
        'assigned_surveyor_id',
        'assigned_installer_id',
        'survey_date',
        'survey_notes',
        'quotation_date',
        'quotation_amount',
        'approval_date',
        'installation_start_date',
        'installation_end_date',
        'actual_material_cost',
        'labor_cost',
        'other_costs',
        'total_profit',
        'created_by',
        'updated_by',
        'note',
        'homeimg',
        'estimated_work_days',
        'labor_cost_surveying',
        'daily_labor_rate',
        'tax_invoice_number',
        'quotation_number',
        'receipt_number'
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function assignedSurveyor()
    {
        return $this->belongsTo(User::class, 'assigned_surveyor_id');
    }

    public function assignedInstaller()
    {
        return $this->belongsTo(User::class, 'assigned_installer_id');
    }


    public function projectname()
    {
        return $this->belongsTo(ProjectName::class, 'project_name_id');
    }

    public function projectimage()
    {
        return $this->hasMany(Projectimages::class, 'project_id');
    }

    public function projectexpenses()
    {
        return $this->hasMany(ProjectExpense::class, 'project_id');
    }

    public function customerneed()
    {
        return $this->hasMany(Customerneed::class,'project_id');
    }

    public function withdrawals()
{
    return $this->hasMany(Withdrawal::class)->orderBy('created_at', 'desc');
}
}

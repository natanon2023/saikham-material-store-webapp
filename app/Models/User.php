<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory,Notifiable, SoftDeletes;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'last_name',
        'nickname',
        'phone_number',
        'password',
        'role',
        'profile_photo_path',
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTechnician()
    {
        return $this->role === 'technician';
    }

    public function materialLogs()
    {
        return $this->hasMany(MaterialLog::class, 'user_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'user_id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function createdProductSet()
    {
        return $this->hasMany(ProductSet::class, 'created_by');
    }

   
    public function updatedProjects()
    {
        return $this->hasMany(Project::class, 'updated_by');
    }

    
    public function assignedSurveyProjects()
    {
        return $this->hasMany(Project::class, 'assigned_surveyor_id');
    }

   
    public function assignedInstallProjects()
    {
        return $this->hasMany(Project::class, 'assigned_installer_id');
    }

    

    public function created_by()
    {
        return $this->hasMany(Customerneed::class,'created_by');
    }
}

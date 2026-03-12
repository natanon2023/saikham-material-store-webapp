<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AluminumSurfaceFinish extends Model
{
    use HasFactory;

    protected $table = 'aluminum_surface_finishs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public function aluminiumItems()
    {
        return $this->hasMany(AluminiumItem::class, 'aluminum_surface_finish_id');
    }

    public function accessoryItems()
    {
        return $this->hasMany(AccessoryItem::class, 'aluminum_surface_finish_id');
    }

    public function productset()
    {
        return $this->hasMany(ProductSet::class, 'aluminum_surface_finish_id');
    }



    


}

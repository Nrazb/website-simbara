<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'id',
        'user_id',
        'type_id',
        'maintenance_unit_id',
        'code',
        'order_number',
        'name',
        'cost',
        'acquisition_date',
        'acquisition_year',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function maintenanceUnit()
    {
        return $this->belongsTo(User::class, 'maintenance_unit_id');
    }

    public function mutationItemRequests()
    {
        return $this->hasMany(MutationItemRequest::class, 'item_id');
    }
    public function maintenanceItemRequests()
    {
        return $this->hasMany(MaintenanceItemRequest::class, 'item_id');
    }
    public function removeItemRequests()
    {
        return $this->hasMany(RemoveItemRequest::class, 'item_id');
    }
}

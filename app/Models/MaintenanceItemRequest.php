<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceItemRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'maintenance_user_id',
        'item_id',
        'item_status',
        'information',
        'maintenance_status',
        'unit_confirmed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => '-',
        ]);
    }

    public function maintenanceUnit()
    {
        return $this->belongsTo(User::class, 'maintenance_user_id')->withDefault([
            'name' => '-',
        ]);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id')->withDefault([
            'name' => '-',
        ]);
    }
}

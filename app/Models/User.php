<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'user_id');
    }
    public function maintenanceItems()
    {
        return $this->hasMany(Item::class, 'maintenance_unit_id');
    }
    public function itemRequests()
    {
        return $this->hasMany(ItemRequest::class);
    }
    public function mutationItemRequestsAsMaintenanceUnit()
    {
        return $this->hasMany(MutationItemRequest::class, 'maintenance_unit_id');
    }
    public function mutationItemRequestsFrom()
    {
        return $this->hasMany(MutationItemRequest::class, 'from_user_id');
    }
    public function mutationItemRequestsTo()
    {
        return $this->hasMany(MutationItemRequest::class, 'to_user_id');
    }
    public function maintenanceItemRequests()
    {
        return $this->hasMany(MaintenanceItemRequest::class);
    }
    public function removeItemRequests()
    {
        return $this->hasMany(RemoveItemRequest::class);
    }
}

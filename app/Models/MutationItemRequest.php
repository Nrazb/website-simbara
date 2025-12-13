<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MutationItemRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id',
        'from_user_id',
        'to_user_id',
        'unit_confirmed',
        'recipient_confirmed',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}

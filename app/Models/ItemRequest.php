<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type_id',
        'name',
        'detail',
        'qty',
        'reason',
        'sent_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => '-',
        ]);
    }

    public function type()
    {
        return $this->belongsTo(Type::class)->withDefault([
            'name' => '-',
        ]);
    }

    public function isDraft(): bool
    {
        return is_null($this->sent_at);
    }

    public function isSent(): bool
    {
        return !is_null($this->sent_at);
    }

}

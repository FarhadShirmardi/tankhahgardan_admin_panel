<?php

namespace App\Models;

use App\Enums\TicketStateEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'title',
        'state',
    ];

    protected $casts = [
        'state' => TicketStateEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticketMessages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->latest();
    }

    public function allTickets(): HasManyThrough
    {
        return $this->hasManyThrough(
            Ticket::class,
            User::class,
            'id',
            'user_id',
            'user_id',
            'id'
        )->latest();
    }

    public function lastTicketMessage(): HasOne
    {
        return $this->hasOne(TicketMessage::class)->latestOfMany();
    }
}

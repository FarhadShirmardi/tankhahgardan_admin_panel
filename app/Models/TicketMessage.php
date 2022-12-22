<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TicketMessage extends Model
{

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function panelUser(): BelongsTo
    {
        return $this->belongsTo(PanelUser::class);
    }
}

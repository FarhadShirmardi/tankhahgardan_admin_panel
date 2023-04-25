<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TicketMessage extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'text',
        'panel_user_id'
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(
            Image::class,
            'hasImage',
            'model_type',
            'model_id'
        );
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function panelUser(): BelongsTo
    {
        return $this->belongsTo(PanelUser::class);
    }

    public function projectUser(): BelongsTo
    {
        return $this->belongsTo(ProjectUser::class, 'project_user_id');
    }

    public function getProjectUser(): ?ProjectUser
    {
        $this->loadMissing('projectUser');

        return $this->projectUser;
    }
}

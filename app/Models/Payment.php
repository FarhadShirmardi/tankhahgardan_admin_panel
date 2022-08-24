<?php

namespace App\Models;

use App\Traits\DateCaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, DateCaster;

    protected $connection = 'mysql';

    protected $fillable = [
        'amount',
        'description',
        'date',
        'imprest_id',
        'project_id',
        'payment_subject',
        'creator_user_id'
    ];

    protected $casts = [
        'amount' => 'double'
    ];

    public function projectOwnerUser(): BelongsTo
    {
        return $this->belongsTo(ProjectUser::class, 'project_id', 'project_id')
            ->where('is_owner', true);
    }
}

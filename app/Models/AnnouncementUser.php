<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AnnouncementUser extends Pivot
{
    protected $connection = 'mysql';
}

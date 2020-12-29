<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AnnouncementUser extends Pivot
{
    protected $connection = 'mysql';
}

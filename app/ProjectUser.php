<?php

namespace App;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectUser extends Pivot
{
    use SoftDeletes;
    protected $table = 'project_user';

    protected $connection = 'mysql';


    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setAddedDateAttribute($value)
    {
        $this->attributes['added_date'] = Helpers::jalaliDateStringToGregorian($value);
    }

    public function getAddedDateAttribute($value)
    {
        return Helpers::gregorianDateStringToJalali($value);
    }

    public function accountingCodes()
    {
        return $this->morphMany(
            AccountingCode::class,
            'hasAccountingCode',
            'model_type',
            'model_id'
        );
    }
}

<?php

namespace App\Constants;

class ProjectTypes
{
    public static function getProjectType($id)
    {
        $projectTypes = collect(self::toArray());
        return $projectTypes->where('id', $id)->first();
    }

    private static function toArray()
    {
        return [
            [
                'id' => 1,
                'text' => 'پیمانکاری'
            ],
            [
                'id' => 2,
                'text' => 'بازرگانی'
            ],
            [
                'id' => 3,
                'text' => 'تولیدی'
            ],
            [
                'id' => 4,
                'text' => 'خدماتی'
            ],
            [
                'id' => 5,
                'text' => 'سایر'
            ],
        ];
    }

    public static function getProjectTypes()
    {
        $projectTypes = collect(self::toArray());
        return $projectTypes->toArray();
    }
}

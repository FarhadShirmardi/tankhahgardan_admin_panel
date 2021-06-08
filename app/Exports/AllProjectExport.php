<?php

namespace App\Exports;

use App\City;
use App\Constants\ProjectTypes;
use App\State;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AllProjectExport implements FromCollection, WithStrictNullComparison,
    WithMapping, WithHeadingRow, WithHeadings, WithColumnFormatting
{
    protected $projects, $row;
    private $carbon;

    public function __construct($projects)
    {
        $this->projects = $projects;
        $this->row = 0;
        $this->carbon = new Carbon();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->projects;
    }

    /**
     * @inheritDoc
     */
    public function map($project): array
    {
        $dateFormat = 'd/m/Y';
        $this->row++;
        return [
            $this->row,
            $project->name,
            State::query()->firstWhere('id', $project->state_id)['name'] ?? '',
            City::query()->firstWhere('id', $project->city_id)['name'] ?? '',
            $project->type ? ProjectTypes::getProjectType($project->type)['text'] : '',
            $project->created_at ? $this->carbon->parse($project->created_at)->format($dateFormat) : ' - ',
            $project->created_at ? $this->carbon->parse($project->created_at)->toTimeString() : ' - ',
            $project->max_time ? $this->carbon->parse($project->max_time)->format($dateFormat) : ' - ',
            $project->max_time ? $this->carbon->parse($project->max_time)->toTimeString() : ' - ',
            $project->user_count,
            $project->active_user_count,
            $project->not_active_user_count,
            $project->payment_count,
            $project->receive_count,
            $project->note_count,
            $project->imprest_count
        ];
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            'ردیف',
            'نام پروژه',
            'استان',
            'شهر',
            'نوع پروژه',
            'تاریخ ایجاد',
            'ساعت ایجاد',
            'تاریخ آخرین ثبت',
            'ساعت آخرین ثبت',
            'تعداد کاربر',
            'تعداد کاربر فعال',
            'تعداد کاربر غیرفعال',
            'تعداد پرداخت',
            'تعداد دریافت',
            'تعداد یادداشت',
            'تعداد تنخواه'
        ];
    }

    /**
     * @inheritDoc
     */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_DATE_TIME4,
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => NumberFormat::FORMAT_DATE_TIME4,
        ];
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Helpers\Helpers;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AllUserExport implements FromCollection, WithStrictNullComparison,
    WithMapping, WithHeadingRow, WithHeadings, WithColumnFormatting
{
    protected $users, $row;
    private $carbon;

    public function __construct($users)
    {
        $this->users = $users;
        $this->row = 0;
        $this->carbon = new Carbon();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->users;
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
            $project->phone_number,
            $this->carbon->parse($project->registered_at)->format($dateFormat),
            $this->carbon->parse($project->registered_at)->toTimeString(),
            $project->max_time ? $this->carbon->parse($project->max_time)->format($dateFormat) : ' - ',
            $project->max_time ? $this->carbon->parse($project->max_time)->toTimeString() : ' - ',
            $project->project_count,
            $project->own_project_count,
            $project->not_own_project_count,
            $project->payment_count,
            $project->receive_count,
            $project->note_count,
            $project->imprest_count,
            $project->file_count,
            $project->image_count,
            $project->image_size ? round($project->image_size, 2) : ' - ',
            $project->feedback_count,
            $project->device_count,
            $project->step_by_step,
        ];
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            'ردیف',
            'نام و نام خانوادگی',
            'شماره تلفن',
            'تاریخ ثبت‌نام',
            'ساعت ثبت‌نام',
            'تاریخ آخرین ثبت',
            'ساعت آخرین ثبت',
            'تعداد کل پروژه',
            'تعداد پروژه مالک',
            'تعداد پروژه اشتراکی',
            'تعداد پرداخت',
            'تعداد دریافت',
            'تعداد یادداشت',
            'تعداد تنخواه',
            'تعداد فایل‌ها',
            'تعداد عکس‌ها',
            'حجم عکس‌ها',
            'تعداد بازخورد',
            'تعداد دستگاه‌ها',
            'گام به گام',
        ];
    }

    /**
     * @inheritDoc
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'E' => NumberFormat::FORMAT_DATE_TIME4,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_DATE_TIME4,
        ];
    }
}

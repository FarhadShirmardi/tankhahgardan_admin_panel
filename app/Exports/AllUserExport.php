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
    public function map($user): array
    {
        $dateFormat = 'j/n/Y';
        $this->row++;
        return [
            $this->row,
            $user->name,
            $user->phone_number,
            $this->carbon->parse($user->registered_at)->format($dateFormat),
            $this->carbon->parse($user->registered_at)->toTimeString(),
            $user->max_time ? $this->carbon->parse($user->max_time)->format($dateFormat) : ' - ',
            $user->max_time ? $this->carbon->parse($user->max_time)->toTimeString() : ' - ',
            $user->project_count,
            $user->own_project_count,
            $user->not_own_project_count,
            $user->payment_count,
            $user->receive_count,
            $user->note_count,
            $user->imprest_count,
            $user->file_count,
            $user->image_count,
            $user->image_size ? round($user->image_size, 2) : ' - ',
            $user->feedback_count,
            $user->device_count,
            $user->step_by_step,
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
            'D' => NumberFormat::FORMAT_DATE_DMYSLASH,
            'E' => NumberFormat::FORMAT_DATE_TIME4,
            'F' => NumberFormat::FORMAT_DATE_DMYSLASH,
            'G' => NumberFormat::FORMAT_DATE_TIME4,
        ];
    }
}

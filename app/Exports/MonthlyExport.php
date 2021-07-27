<?php

namespace App\Exports;

use App\Exports\Backups\Admin\ProjectUserExport;
use App\Exports\Backups\Panel\PaymentExport;
use App\Exports\Backups\Panel\ReceiveExport;
use App\Exports\Backups\Panel\TurnoverDetailExport;
use App\Exports\Backups\Shared\AccountTitleExport;
use App\Exports\Backups\Shared\CostCenterExport;
use App\Exports\Backups\Shared\MemoExport;
use App\Exports\Backups\Shared\NoteExport;
use App\Exports\Backups\Shared\ProjectExport;
use App\Exports\Backups\Shared\ReminderExport;
use App\Exports\Monthly\MonthlyActiveUserExport;
use App\Exports\Monthly\MonthlyOldRegisterExport;
use App\Exports\Monthly\MonthlyRegisterExport;
use App\Exports\Monthly\MonthlyUserAssessmentExport;
use App\Exports\Monthly\MonthlyUserReturnExport;
use App\Helpers\Helpers;
use App\MonthlyReport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonthlyExport implements WithMultipleSheets
{
    use Exportable;


    public function __construct()
    {
    }

    /**
     * @return array
     *
     */
    public function sheets(): array
    {
        $date = explode('/', Helpers::gregorianDateStringToJalali(now()->toDateString()));
        $year = $date[0];
        $month = $date[1] - 1;
        $report = MonthlyReport::query()->where('year', $year)->where('month', $month)->first();
        $sheets = [];

        $sheets[] = new MonthlyRegisterExport($report);
        $sheets[] = new MonthlyOldRegisterExport($report);
        $sheets[] = new MonthlyUserReturnExport($report);
        $sheets[] = new MonthlyUserAssessmentExport($report);
        $sheets[] = new MonthlyActiveUserExport($report);

        return $sheets;
    }
}

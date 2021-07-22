<?php

namespace App\Exports\Monthly;

use App\MonthlyReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyActiveUserExport implements FromView, WithTitle
{
    /**
     * @var MonthlyReport $report
     */
    private $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        $finalResult = collect(json_decode($this->report->active_user_counts));
//        dd($finalResult->where('platform', 1)->sum('count'));
        return view('dashboard.report.activeUserCounts', [
            'data' => $finalResult,
        ]);
    }

    public function title(): string
    {
        return 'ارزیابی وفاداری کاربران';
    }
}

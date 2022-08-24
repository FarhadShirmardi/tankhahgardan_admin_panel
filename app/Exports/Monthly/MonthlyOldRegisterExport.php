<?php

namespace App\Exports\Monthly;

use App\Constants\PremiumDuration;
use App\Models\MonthlyReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyOldRegisterExport implements FromView, WithTitle
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
        $finalResult = collect(json_decode($this->report->old_user_data));
        $prices = PremiumDuration::toArray();
        return view('dashboard.report.monthlyOldRegister', [
            'data' => $finalResult,
            'prices' => $prices,
        ]);
    }

    public function title(): string
    {
        return 'کاربران قدیمی';
    }
}

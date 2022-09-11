<?php

namespace App\Exports\Monthly;

use App\Constants\PremiumDuration;
use App\Models\MonthlyReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyUserReturnExport implements FromView, WithTitle
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
        $finalResult = collect(json_decode($this->report->user_return_data));
//        dd($finalResult->where('platform', 1)->sum('count'));
        $prices = PremiumDuration::toArray();
        return view('dashboard.report.monthlyReturn', [
            'data' => $finalResult,
            'prices' => $prices,
        ]);
    }

    public function title(): string
    {
        return 'کاربران بازگشتی';
    }
}

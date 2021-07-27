<?php

namespace App\Exports\Monthly;

use App\Constants\PremiumDuration;
use App\MonthlyReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyUserAssessmentExport implements FromView, WithTitle
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
        $finalResult = collect(json_decode($this->report->user_assessment_data));

        $prices = PremiumDuration::toArray();
        return view('dashboard.report.monthlyUserAssessment', [
            'data' => $finalResult,
            'prices' => $prices,
        ]);
    }

    public function title(): string
    {
        return 'ارزشیابی کاربران';
    }
}

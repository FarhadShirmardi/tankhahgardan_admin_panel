<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MetricsExport implements FromView
{
    private $metrics;

    public function __construct($metrics)
    {
        $this->metrics = $metrics;
    }

    public function view(): View
    {
        $metricKeys = [];
        if ($this->metrics->count()) {
            $metricKeys = array_keys($this->metrics[0]['metric']);
        }
        return \view('dashboard.automation.metricsList', [
            'metrics' => $this->metrics,
            'metricKeys' => $metricKeys,
        ]);
    }
}

<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ReportsExport implements FromView, ShouldAutoSize, WithColumnWidths
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        // Your Blade: resources/views/exports/reports-excel.blade.php
        return view('exports.reports-excel', $this->data);
    }

    /**
     * Set explicit widths for the main columns.
     * A = text/metrics, B = counts, C = optional extra header col.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 45, // wide enough for "Persons with Disability Affairs..."
            'B' => 20, // numbers / short labels
            'C' => 25, // just in case some header uses 3 columns
        ];
    }
}

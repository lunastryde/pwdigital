<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ReportsExport implements FromView, WithColumnWidths, WithEvents
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.reports-excel', $this->data);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 50, // Wider for labels
            'B' => 30, // Value column
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // 1. GLOBAL STYLES
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
                $sheet->getStyle("A1:B{$highestRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                
                // Wrap text in Column A (Labels)
                $sheet->getStyle("A1:A{$highestRow}")->getAlignment()->setWrapText(true);

                // 2. HEADER BLOCK (Rows 1-5)
                // Merge title rows for a clean header look
                $sheet->mergeCells('A1:B1');
                $sheet->mergeCells('A2:B2');
                $sheet->mergeCells('A3:B3'); // Spacer
                $sheet->mergeCells('A4:B4'); // "PDAO Reports & Analytics"

                // Style the Main PDAO Header
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF1E40AF']], // Dark Blue
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['size' => 10, 'italic' => true, 'color' => ['argb' => 'FF6B7280']], // Gray
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FF111827']], // Black
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF3F4F6']] // Light Gray bg
                ]);

                // 3. DYNAMIC SECTION STYLING
                $sectionTitles = [
                    'Report Details',
                    'Summary',
                    'Age Distribution',
                    'Common Causes of Disability',
                    'Device Requests',
                    'Applications by Barangay',
                ];

                // Loop through rows to apply conditional styling
                for ($row = 5; $row <= $highestRow; $row++) {
                    $cellA = $sheet->getCell("A{$row}")->getValue();
                    $valA  = (string) $cellA;

                    // CHECK: Is this a Section Header? (Dark Blue Background)
                    $isHeader = in_array($valA, $sectionTitles, true) || str_starts_with($valA, 'Monthly Submission Trend');

                    if ($isHeader) {
                        $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']], // Navy Blue
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                        ]);
                        continue; 
                    }

                    // CHECK: Is this a Table Sub-Header? (e.g., "Metric | Value") (Light Blue)
                    $cellB = $sheet->getCell("B{$row}")->getValue();
                    if ($valA && $cellB && !is_numeric($cellB) && $cellB !== '') {
                        $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDBEAFE']], // Light Blue
                        ]);
                        continue;
                    }

                    // CHECK: Is this a Data Row? (Zebra Striping)
                    // If col B has a value (number or text), apply styling
                    if ($cellB !== null && $cellB !== '') {
                        // Right align numbers in Column B
                        $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        
                        // Apply Zebra Striping to even rows
                        if ($row % 2 == 0) {
                            $sheet->getStyle("A{$row}:B{$row}")->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFFFFFF'); // White
                        } else {
                            $sheet->getStyle("A{$row}:B{$row}")->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFAFAFA'); // Very Light Gray
                        }
                        
                        // Add a subtle border to data rows
                        $sheet->getStyle("A{$row}:B{$row}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_HAIR);
                    }
                }

                // 4. OUTER BORDER
                // Draw a medium border around the entire content area
                $sheet->getStyle("A5:B{$highestRow}")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->setColor(new Color('FF9CA3AF')); // Gray border
            },
        ];
    }
}
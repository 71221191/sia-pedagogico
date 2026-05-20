<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths; // Nuevo
use Maatwebsite\Excel\Concerns\WithStyles;       // Nuevo
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StudentScheduleExport implements FromView, WithColumnWidths, WithStyles, WithDrawings
{
    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View {
        return view('reports.excel_student_schedule', $this->data);
    }

    // 1. Definimos anchos de columna fijos (en unidades de Excel)
    public function columnWidths(): array {
        return [
            'A' => 20, // Más ancho para "HORA / BLOQUE"
            'B' => 35, // Más ancho para los días
            'C' => 35,
            'D' => 35,
            'E' => 35,
            'F' => 35,
        ];
    }

    // 2. Aplicamos el "Ajuste de texto" y alineación
    public function styles(Worksheet $sheet) {
        $sheet->getStyle('A5:F25')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A5:F25')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Calculamos cuántas filas de datos hay (encabezados son 4 filas)
        $totalSlots = count($this->data['timeSlots']);
        $lastRow = 4 + $totalSlots;

        // Aplicamos altura solo a las filas que tienen datos
        for ($i = 5; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(85);
        }

        return [
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']]
            ],
        ];
    }

    public function drawings() {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('img/logo-instituto.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);
        return $drawing;
    }
}

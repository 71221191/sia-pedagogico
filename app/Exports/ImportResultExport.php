<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ImportResultExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $details;

    public function __construct(array $details)
    {
        $this->details = $details;
    }

    public function collection()
    {
        return collect($this->details)->map(function ($item) {
            // Aplanamos el objeto para que quepa en filas de Excel
            return array_merge(
                ['ESTADO_SISTEMA' => $item['status'], 'MENSAJE_SISTEMA' => $item['message']],
                $item['data']
            );
        });
    }

    public function headings(): array
    {
        if (empty($this->details)) return [];
        // Las cabeceras serán: Estado, Mensaje y luego todas las del Excel original
        return array_merge(
            ['ESTADO_SISTEMA', 'MENSAJE_SISTEMA'],
            array_keys($this->details[0]['data'])
        );
    }
}

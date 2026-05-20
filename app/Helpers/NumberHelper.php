<?php

namespace App\Helpers;

class NumberHelper
{
    public static function noteToWords($note)
    {
        $words = [
            0 => 'CERO', 1 => 'UNO', 2 => 'DOS', 3 => 'TRES', 4 => 'CUATRO',
            5 => 'CINCO', 6 => 'SEIS', 7 => 'SIETE', 8 => 'OCHO', 9 => 'NUEVE',
            10 => 'DIEZ', 11 => 'ONCE', 12 => 'DOCE', 13 => 'TRECE', 14 => 'CATORCE',
            15 => 'QUINCE', 16 => 'DIECISÉIS', 17 => 'DIECISIETE', 18 => 'DIECIOCHO',
            19 => 'DIECINUEVE', 20 => 'VEINTE'
        ];

        return $words[(int)$note] ?? '';
    }
    /**
     * Convierte un número arábigo a romano (del 1 al 10)
     */
    public function toRoman($number)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V',
            6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X'
        ];

        // Convertimos a entero por si llega como string "1"
        $n = (int)$number;

        return $map[$n] ?? $number;
    }
}

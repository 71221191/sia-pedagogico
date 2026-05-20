<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait StandardizesAcademicData
{
    /**
     * Limpia y unifica nombres de Programas (Carreras)
     * Ej: "EDUCACIÓN SECUNDARIA, ESPECIALIDAD: MATEMÁTICA" -> "EDUCACIÓN SECUNDARIA ESPECIALIDAD MATEMÁTICA"
     */
    public function normalizarNombrePrograma($texto)
    {
        if (empty($texto)) return 'SIN PROGRAMA';

        // 1. A mayúsculas y quitar espacios extremos
        $texto = mb_strtoupper(trim($texto), 'UTF-8');

        // 2. Quitar dos puntos y comas (causantes de duplicados)
        $texto = str_replace([':', ';'], '', $texto);

        // 3. Convertir múltiples espacios o tabs en uno solo
        $texto = preg_replace('/\s+/', ' ', $texto);

        return trim($texto);
    }

    /**
     * Separa el Programa de la Resolución y limpia la RVM
     */
    public function extraerInfoPrograma($textoCompleto)
    {
        $info = [
            'programa' => $this->normalizarNombrePrograma($textoCompleto),
            'resolucion' => 'PLAN ANTIGUO'
        ];

        // Si el texto tiene paréntesis (Ej: EDUCACIÓN INICIAL (RVM 163-2019-MINEDU))
        if (preg_match('/\((.*?)\)/', $textoCompleto, $matches)) {
            // El programa es lo que está antes del paréntesis
            $nombreLimpio = trim(explode('(', $textoCompleto)[0]);
            $info['programa'] = $this->normalizarNombrePrograma($nombreLimpio);

            // La resolución es lo que está adentro del paréntesis, pero limpia
            $resRaw = mb_strtoupper($matches[1], 'UTF-8');
            $buscar = ['RESOLUCIÓN VICEMINISTERIAL', 'RESOLUCION VICEMINISTERIAL', 'Nº', 'N°', 'MINEDU'];
            $reemplazar = ['RVM', 'RVM', '', '', 'MINEDU'];

            $info['resolucion'] = trim(str_replace($buscar, $reemplazar, $resRaw));
        }

        return $info;
    }

    public function convertirSiNo($texto)
    {
        $texto = strtoupper(trim($texto));
        return ($texto === 'SÍ' || $texto === 'SI' || $texto === 'SIT' || $texto === 'VERDADERO');
    }

    public function extraerPeriodo($texto)
    {
        // Busca patrones como 2025-I o 2025-II
        if (preg_match('/20\d{2}-(?:I|II|1|2)/i', $texto, $matches)) {
            return strtoupper($matches[0]);
        }
        return '2025-I'; // Valor por defecto
    }

    public function generarHuellaCurso($nombre)
    {
        if (empty($nombre)) return '';

        // 1. Quitar tildes y caracteres especiales (limpieza profunda)
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ē', 'ē', 'Ī', 'ī', 'Ō', 'ō', 'Ū', 'ū');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u');
        $nombre = str_replace($a, $b, $nombre);

        // 2. Todo a minúsculas y quitar todo lo que no sea letras o números
        $nombre = strtolower($nombre);
        $nombre = preg_replace('/[^a-z0-9]/', '', $nombre);

        return $nombre; // Ejemplo: "Matemática I (FG)" -> "matematicaifg"
    }

    /**
     * Genera un código de curso único basado en el programa y un número aleatorio
     */
    public function generarCodigoCurso($planId, $ciclo)
    {
        $plan = \App\Models\StudyPlan::with('studyProgram')->find($planId);
        $prefijo = substr(preg_replace('/[^A-Z]/', '', strtoupper($plan->studyProgram->name ?? 'CUR')), 0, 3);
        $cicloNum = $this->traducirCicloANumero($ciclo);

        return $prefijo . $cicloNum . '-' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
    }

    public function normalizarSlug($texto)
    {
        return \Illuminate\Support\Str::slug($texto);
        // Ejemplo: "Matemática I" -> "matematica-i"
    }

    public function traducirCicloANumero($ciclo)
    {
        // Si ya es un número entre 1 y 10, lo devolvemos tal cual
        if (is_numeric($ciclo) && $ciclo >= 1 && $ciclo <= 10) {
            return (int)$ciclo;
        }

        $mapa = [
            'I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4, 'V' => 5,
            'VI' => 6, 'VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10,
            '01' => 1, '02' => 2, '03' => 3, '04' => 4, '05' => 5,
            '06' => 6, '07' => 7, '08' => 8, '09' => 9, '10' => 10
        ];

        $entrada = strtoupper(trim($formatCiclo = (string)$ciclo));
        return $mapa[$entrada] ?? 1;
    }
}

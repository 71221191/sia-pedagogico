<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Matrícula</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            color: #000;
            line-height: 1.1;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 4px; vertical-align: middle; }

        .header-table { border: none; margin-bottom: 5px; }
        .header-table td { border: none; padding: 0; }

        .title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin: 10px 0;
        }

        .bg-gray { background-color: #f2f2f2; font-weight: bold; }

        .student-photo-box {
            border: 1px solid #000;
            width: 75px;
            height: 95px;
            text-align: center;
            float: right;
            margin-top: -15px;
        }

        .footer-container {
            page-break-inside: avoid;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- LOGO Y TÍTULO INSTITUCIONAL (CARGA ULTRA RÁPIDA DE IMÁGENES) -->
    <table class="header-table">
        <tr>
            <td width="20%">
                @if(file_exists($logoInsti))
                    <img src="{{ $logoInsti }}" width="55">
                @endif
            </td>
            <td width="60%" style="text-align: center; vertical-align: middle;">
                <div class="title">Ficha de Matrícula</div>
            </td>
            <td width="20%" style="text-align: right; vertical-align: top;">
                <!-- Bloque para foto del estudiante, calca exacta del físico -->
                <div class="student-photo-box">
                    @if($person->official_photo_path && file_exists(storage_path('app/public/' . $person->official_photo_path)))
                        <img src="{{ storage_path('app/public/' . $person->official_photo_path) }}" width="75" height="95">
                    @else
                        <div style="font-size: 7px; color: #aaa; margin-top: 40px; font-weight: bold; text-transform: uppercase;">FOTO</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- DATOS INSTITUCIONALES (Primer Cuadro) -->
    <table>
        <tr>
            <td class="bg-gray" width="18%">Nombre de la Institución</td>
            <td colspan="5" class="font-bold">"HNO. VICTORINO ELORZ GOICOECHEA"</td>
            <td class="bg-gray" width="8%">DRE</td>
            <td width="15%">CAJAMARCA</td>
        </tr>
        <tr>
            <td class="bg-gray text-center" style="font-size: 7px;">Código Modular</td>
            <td class="bg-gray text-center" style="font-size: 7px;">Denominación</td>
            <td class="bg-gray text-center" style="font-size: 7px;">Gestión</td>
            <td class="bg-gray text-center" style="font-size: 6px;">D.S. / R.M. de Creación y R.D. de Revalidación</td>
            <td class="bg-gray text-center" style="font-size: 7px;">Dirección</td>
            <td class="bg-gray text-center" style="font-size: 7px;">UGEL</td>
            <td class="bg-gray text-center" style="font-size: 7px;">Distrito</td>
            <td class="bg-gray text-center" style="font-size: 7px;">Provincia</td>
        </tr>
        <tr>
            <td class="text-center font-bold" style="font-size: 9px;">0391151</td>
            <td class="text-center font-bold">IESP</td>
            <td class="text-center font-bold">Público</td>
            <td class="text-center" style="font-size: 7px;">D.S. 008-1983-ED</td>
            <td class="text-center" style="font-size: 7px;">AV. EL MAESTRO Nº 290</td>
            <td class="text-center">CAJAMARCA</td>
            <td class="text-center">CAJAMARCA</td>
            <td class="text-center">CAJAMARCA</td>
        </tr>
    </table>

    <!-- DATOS ACADÉMICOS (Segundo Cuadro) -->
    <table style="margin-top: 5px;">
        <tr>
            <td class="bg-gray" width="18%">Programa de estudios / Turno</td>
            <td colspan="3" class="font-bold uppercase">
                {{ $enrollment->studyPlan->studyProgram->name }} (RVM 076-2020-MINEDU) / TURNO: {{ $enrollment->shift_id == 1 ? 'MAÑANA' : 'TARDE' }}
            </td>
            <td class="bg-gray" width="12%">Periodo Académico</td>
            <td class="text-center font-bold" width="12%">{{ $enrollment->academicPeriod->name }}</td>
        </tr>
        <tr>
            <td class="bg-gray">Resolución de Autorización</td>
            <td colspan="3" class="font-bold">RD</td>
            <td class="bg-gray">Ciclo - Sección</td>
            <td class="text-center font-bold">{{ $enrollment->cycle }} - "{{ $enrollment->section_label }}"</td>
        </tr>
    </table>

    <!-- DATOS DEL ESTUDIANTE (Tercer Cuadro) -->
    <table style="margin-top: 5px;">
        <tr>
            <td class="bg-gray" width="18%">Nombres y Apellidos</td>
            <td class="font-bold uppercase" style="font-size: 9px; color: #000;">
                {{ $person->last_name_p }} {{ $person->last_name_m }}, {{ $person->names }}
            </td>
            <td class="bg-gray" width="12%">Código</td>
            <td class="text-center font-bold" width="12%">{{ $person->dni }}</td>
        </tr>
    </table>

    <!-- TABLA DE CURSOS EN LOS QUE SE MATRICULA (Calca exacta del físico) -->
    <table style="margin-top: 8px;">
        <thead>
            <tr class="bg-gray">
                <th width="30" class="text-center">N°</th>
                <th class="text-center">CURSOS EN LOS QUE SE MATRICULA</th>
                <th width="60" class="text-center">HORAS</th>
                <th width="60" class="text-center">CRÉDITOS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $index => $course)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="text-align: left;" class="uppercase font-bold">{{ $course['name'] }}</td>
                    <td class="text-center font-bold">{{ $course['hours'] }}</td>
                    <td class="text-center font-bold">{{ $course['credits'] }}</td>
                </tr>
            @endforeach
            <!-- Fila de Totales -->
            <tr class="bg-gray">
                <td colspan="2" class="text-right font-bold" style="padding-right: 15px;">Total:</td>
                <td class="text-center font-bold" style="font-size: 9px;">{{ $totalHours }}</td>
                <td class="text-center font-bold" style="font-size: 9px;">{{ $totalCredits }}</td>
            </tr>
        </tbody>
    </table>

    <!-- TABLA DE SUBSANACIÓN (Obligatoria con valores en cero para el formato) -->
    <table style="margin-top: 8px;">
        <thead>
            <tr class="bg-gray">
                <th width="30" class="text-center">N°</th>
                <th class="text-center">CURSOS DE SUBSANACIÓN</th>
                <th width="60" class="text-center">HORAS</th>
                <th width="60" class="text-center">CREDITOS</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-gray">
                <td colspan="2" class="text-right font-bold" style="padding-right: 15px;">Total:</td>
                <td class="text-center font-bold" style="font-size: 9px;">0</td>
                <td class="text-center font-bold" style="font-size: 9px;">0</td>
            </tr>
        </tbody>
    </table>

    <!-- BLOQUE DE FIRMAS DE LA FICHA -->
    <div class="footer-container">
        <table style="width: 100%; border: none; margin-top: 50px;">
            <tr>
                <td class="text-center" width="31%" style="border: none;">
                    <div style="border-top: 1px solid #000; width: 90%; margin: 0 auto;"></div>
                    <div class="font-bold" style="font-size: 7px; margin-top: 3px;">VERGARA ABANTO, FERNANDO MARTIN</div>
                    <div style="font-size: 7px; color: #555;">DIRECTOR(A) GENERAL</div>
                    <div style="font-size: 5px; color: #aaa; font-style: italic;">Firma, Post Firma y Sello</div>
                </td>
                <td width="3%" style="border: none;"></td>
                <td class="text-center" width="31%" style="border: none;">
                    <div style="border-top: 1px solid #000; width: 90%; margin: 0 auto;"></div>
                    <div class="font-bold" style="font-size: 7px; margin-top: 3px;">GONZALES CHAVEZ, RAQUEL PILAR</div>
                    <div style="font-size: 7px; color: #555;">SECRETARIO(A) ACADÉMICO</div>
                    <div style="font-size: 5px; color: #aaa; font-style: italic;">Firma, Post Firma y Sello</div>
                </td>
                <td width="3%" style="border: none;"></td>
                <td class="text-center" width="31%" style="border: none;">
                    <div style="border-top: 1px solid #000; width: 90%; margin: 0 auto;"></div>
                    <div class="font-bold uppercase" style="font-size: 7px; margin-top: 3px;">{{ $person->last_name_p }} {{ $person->last_name_m }}, {{ $person->names }}</div>
                    <div style="font-size: 7px; color: #555;">ESTUDIANTE</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>

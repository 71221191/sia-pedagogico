<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 110px 1cm 1cm 1cm;
        }
        body { font-family: Arial, sans-serif; font-size: 9px; color: #000; line-height: 1.1; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .bordered td, .bordered th { border: 1px solid #000; padding: 3px; }

        .header-box { border: 2px solid #000; padding: 10px; margin-bottom: 10px; }
        .title { font-size: 14px; font-weight: bold; margin: 10px 0; }

        .bg-gray { background-color: #eee; font-weight: bold; }
        .summary-table { width: 250px; margin-top: 10px; }
        /* 2. Definimos el encabezado como FIJO */
        #header {
            position: fixed;
            top: -95px; /* Lo subimos al espacio del margen superior */
            left: 0px;
            right: 0px;
            height: 90px;
            width: 100%;
        }

        /* 3. Evitamos que el bloque de firmas se parta a la mitad */
        .footer-container {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

    <!-- LOGO Y TÍTULO -->
    <header id="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td width="20%">
                    @if($logoInsti)
                        <img src="{{ $logoInsti }}" width="60">
                    @endif
                </td>
                <td width="60%" style="text-align: center; vertical-align: middle;">
                    <div style="font-size: 13px; font-weight: bold; margin-bottom: 2px;">REPORTE DE ESTUDIANTES</div>
                    <div style="font-size: 16px; font-weight: bold; text-decoration: underline; text-transform: uppercase;">
                        Nómina de Matrícula
                    </div>
                </td>
                <td width="20%" class="text-right">
                    @if($logoMinedu)
                        <img src="{{ $logoMinedu }}" width="110">
                    @endif
                </td>
            </tr>
        </table>
    </header>

    <!-- DATOS INSTITUCIONALES Y ACADÉMICOS -->
    <table class="bordered">
        <!-- Fila 1: Total 8 columnas (1 + 5 + 1 + 1) -->
        <tr>
            <td class="bg-gray" style="width: 18%;">Nombre de la Institución</td>
            <td colspan="5" class="font-bold">"HNO. VICTORINO ELORZ GOICOECHEA"</td>
            <td class="bg-gray" style="width: 8%;">DRE</td>
            <td style="width: 15%;">CAJAMARCA</td>
        </tr>

        <!-- Fila 2: Total 8 columnas (1 + 1 + 1 + 1 + 1 + 1 + 1 + 1) -->
        <tr>
            <td class="bg-gray text-center" style="width: 12%;">Código Modular</td>
            <td class="bg-gray text-center" style="width: 10%;">Denominación</td>
            <td class="bg-gray text-center" style="width: 10%;">Gestión</td>
            <td class="bg-gray text-center" style="width: 25%; font-size: 7px;">D.S. / R.M. de Creación y R.D. de Revalidación</td>
            <td class="bg-gray text-center" style="width: 13%;">Dirección</td>
            <td class="bg-gray text-center" style="width: 10%;">UGEL</td>
            <td class="bg-gray text-center" style="width: 10%;">Distrito</td>
            <td class="bg-gray text-center" style="width: 10%;">Provincia</td>
        </tr>

        <!-- Fila 3: Total 8 columnas -->
        <tr>
            <td class="text-center font-bold" style="font-size: 11px;">0391151</td>
            <td class="text-center font-bold">I.E.S.P.</td>
            <td class="text-center font-bold">PÚBLICA</td>
            <td class="text-center" style="font-size: 8px;">{{ $ds_creacion }}</td>
            <td class="text-center" style="font-size: 8px;">AV. EL MAESTRO # 290</td>
            <td class="text-center">CAJAMARCA</td>
            <td class="text-center">CAJAMARCA</td>
            <td class="text-center">CAJAMARCA</td>
        </tr>
    </table>

    <!-- DATOS ACADÉMICOS -->
    <table class="bordered">
        <tr>
            <td class="bg-gray" width="20%">Programa de estudio / Turno</td>
            <td>
                {{ $section->course->studyPlan->studyProgram->name }}
                <!-- AGREGAMOS LA RESOLUCIÓN DEL PLAN -->
                ({{ $section->course->studyPlan->resolution_code }})
                / TURNO: {{ $section->shift_id == 1 ? 'MAÑANA' : 'TARDE' }}
            </td>
            <td class="bg-gray" width="15%">Periodo Académico</td>
            <td width="15%" class="text-center">{{ $section->academicPeriod->name }}</td>
        </tr>
        <tr>
            <td class="bg-gray">Resolución de Autorización</td>
            <td>{{ $params['rdr'] }}</td>
            <td class="bg-gray">Ciclo - Sección</td>
            <td class="text-center">
                {{ $numberHelper->toRoman($section->course->cycle) }} - "{{ $section->name }}"
            </td>
        </tr>
    </table>

    <table class="bordered" style="margin-bottom: 15px;">
        <tr>
            <td class="bg-gray" width="20%">Director (a) General</td>
            <td>FERNANDO MARTIN VERGARA ABANTO</td>
            <td class="bg-gray" width="15%">R.D. Encargatura</td>
            <td width="15%" class="text-center">{{ $params['rdr_encargatura'] }}</td>
        </tr>
    </table>

    <!-- TABLA DE ALUMNOS -->
    <table class="bordered text-center">
        <thead>
            <tr class="bg-gray">
                <th width="30">N° Orden</th>
                <th width="70">N° Matricula</th>
                <th>APELLIDOS Y NOMBRES (Por Orden Alfabético)</th>
                <th width="50">Gratuito o Pagante</th>
                <th width="40">Sexo H / M</th>
                <th width="70">Fecha de Nacimiento</th>
                <th width="30">Edad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $index => $e)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $e->person->dni }}</td>
                <td style="text-align: left;" class="uppercase">
                    {{ $e->person->last_name_p }} {{ $e->person->last_name_m }}, {{ $e->person->names }}
                </td>
                <td>P</td>
                <td>{{ $e->person->gender }}</td>
                <td>{{ $e->person->birth_date }}</td>
                <td>{{ $e->edad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-container">

        <!-- FIRMAS -->
        <div style="margin-top: 30px; text-align: right; font-weight: bold; margin-bottom: 50px;">
            CAJAMARCA, {{ $params['dia'] }} DE {{ strtoupper($params['mes']) }} DEL {{ $params['anio'] }}
        </div>

        <!-- RESUMEN ESTADÍSTICO -->
        <div class="footer-container">

        <table class="bordered summary-table text-center" style="width: 300px;">
            <tr class="bg-gray">
                <td colspan="2">Resumen</td>
                <td>Total</td>
            </tr>
            <tr>
                <td class="bg-gray" width="40%">Hombres</td>
                <td width="30%">{{ $stats['hombres'] }}</td>
                <td rowspan="2" style="vertical-align: middle;">{{ $stats['total'] }}</td>
            </tr>
            <tr>
                <td class="bg-gray">Mujeres</td>
                <td>{{ $stats['mujeres'] }}</td>
            </tr>
            <tr>
                <td class="bg-gray">Gratuitos</td>
                <td>{{ $stats['gratuitos'] }}</td>
                <td rowspan="2" style="vertical-align: middle;">{{ $stats['total'] }}</td>
            </tr>
            <tr>
                <td class="bg-gray">Pagantes</td>
                <td>{{ $stats['pagantes'] }}</td>
            </tr>
        </table>

        <table style="margin-top: 100px; width: 100%; border: none;">
            <tr>
                <td class="text-center" width="45%">
                    <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto;"></div>
                    <div class="font-bold">FERNANDO MARTÍN VERGARA ABANTO</div>
                    <div>DIRECTOR(A) GENERAL</div>
                    <div style="font-size: 7px;">Firma, Post Firma y Sello</div>
                </td>
                <td width="10%"></td>
                <td class="text-center" width="45%">
                    <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto;"></div>
                    <div class="font-bold">SEGUNDO MARIO ROMERO LUNA</div>
                    <div>SECRETARIO ACADÉMICO</div>
                    <div style="font-size: 7px;">Firma, Post Firma y Sello</div>
                </td>
                <td width="10%"></td>
                <td class="text-center" width="45%">
                    <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto;"></div>
                    <div class="font-bold">V°B° DRE/UGEL</div>
                </td>
            </tr>
        </table>

        <div style="border: 1px solid #000; padding: 8px; margin-top: 20px; font-size: 8px; line-height: 1.2;">
            1) La inscripción de los alumnos se hará en forma clara y en riguroso orden alfabético, cuidando de anotar. 1° Apellido Paterno, 2° Apellido Materno y 3° los nombres del matriculado, tal como figura en su DNI o partida de nacimiento.<br>
            2) Las nóminas se confeccionarán por duplicado y se remitirán para su visación a la Dirección Regional de Educación o Unidad de Gestión Educativa Local, en caso de delegación.<br>
            3) Estado del registro: De acuerdo a la fecha de cierre su registro es oportuno.
        </div>
    </div>

    <!-- TEXTO DE USO EXTERNO -->
    <p style="font-size: 9px; font-weight: bold; margin-top: 5px; margin-bottom: 0;">* De uso externo</p>

</body>
</html>

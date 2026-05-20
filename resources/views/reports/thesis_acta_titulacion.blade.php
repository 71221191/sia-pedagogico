<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm 1.2cm; size: A4; }
        body { font-family: Arial, sans-serif; font-size: 8.5px; color: #000; line-height: 1.1; margin: 0; }

        table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: -1px; }
        td { border: 1px solid #000; padding: 4px; vertical-align: middle; }

        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f2f2f2; }
        .uppercase { text-transform: uppercase; }

        /* Cuadritos Código Modular */
        .mod-box {
            border: 1px solid #000;
            display: inline-block;
            width: 12px;
            height: 15px;
            text-align: center;
            line-height: 15px;
            margin-right: -1px;
            font-weight: bold;
            background: #fff;
        }

        /* Estilo Fecha arriba */
        .fecha-container { float: right; width: 110px; margin-top: -10px; }
        .fecha-table { width: 100%; border: 1px solid #000; }
        .fecha-table td { padding: 2px; font-size: 8px; }

        .header-main { font-size: 15px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>

    <!-- LOGOS Y FECHA -->
    <table style="border: none; margin-bottom: 10px;">
        <tr style="border: none;">
            <td style="border: none; width: 15%;"><img src="{{ $logoInsti }}" style="height: 60px;"></td>
            <td style="border: none; width: 65%;" class="text-center">
                <img src="{{ $logoMinedu }}" style="height: 40px;"><br>
                <div class="header-main">ACTA DE TITULACIÓN</div>
            </td>
            <td style="border: none; width: 20%; vertical-align: top;">
                <div class="fecha-container">
                    <div class="text-center font-bold" style="margin-bottom: 3px; font-size: 9px;">Fecha</div>
                    <table class="fecha-table text-center">
                        <tr class="bg-gray font-bold">
                            <td>Día</td><td>Mes</td><td>Año</td>
                        </tr>
                        <tr>
                            <!-- DATA DINÁMICA DE LA FECHA -->
                            <td>{{ $project->defenseAct->defense_date->format('d') }}</td>
                            <td>{{ $project->defenseAct->defense_date->format('m') }}</td>
                            <td>{{ $project->defenseAct->defense_date->format('Y') }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- DATOS INSTITUCIONALES -->
    <table>
        <tr>
            <td class="bg-gray" style="width: 18%;">Nombre de la Institución</td>
            <td colspan="4" class="font-bold">"HNO. VICTORINO ELORZ GOICOECHEA"</td>
            <td class="bg-gray" style="width: 8%;">DRE</td>
            <td style="width: 20%;">CAJAMARCA</td>
        </tr>

        <tr>
            <td class="bg-gray">Código Modular</td>
            <td class="bg-gray text-center" style="width: 12%;">Denominación</td>
            <td class="bg-gray text-center" style="width: 12%;">Gestión</td>
            <td class="bg-gray text-center" style="width: 25%;">D.S. / R.M. de Creación</td>
            <td class="bg-gray text-center" style="width: 10%;">Dirección</td>
            <td class="bg-gray">UGEL</td>
            <td>CAJAMARCA</td>
        </tr> 

        <tr>
            <td class="text-center font-bold" style="font-size: 11px; letter-spacing: 3px;">
                0391151
            </td>
            <td class="text-center font-bold">I.E.S.P.</td>
            <td class="text-center font-bold">PÚBLICA</td>
            <td class="text-center" style="font-size: 7px;">
                {{ $ds_creacion }}
            </td>
            <td class="font-bold text-center" style="font-size: 7px;">AV. EL MAESTRO # 290</td>
            <td class="bg-gray">Provincia</td>
            <td>CAJAMARCA</td>
        </tr>
        <tr>
            <td class="bg-gray">Carrera / Especialidad</td>
            <td colspan="4" class="font-bold uppercase">{{ $carrera }}</td>
            <td class="bg-gray">Distrito</td>
            <td>CAJAMARCA</td>
        </tr>

        <tr>
            <td class="bg-gray">Resolución de Autorización</td>
            <td colspan="6">
                {{ $project->auto_resolution }}
            </td>
        </tr>

        <tr>
            <td class="bg-gray">Director (a) General</td>
            <td colspan="6" class="font-bold uppercase">FERNANDO MARTÍN VERGARA ABANTO</td>
        </tr>

    </table>

    <!-- SECCIÓN TÍTULO PROFESIONAL DINÁMICO -->
    <table style="margin-top: 10px;">
        <tr class="bg-gray text-center font-bold">
            <td style="padding: 6px;">ACTA DE TITULACIÓN PARA OPTAR EL TÍTULO PROFESIONAL DE:</td>
        </tr>
        <tr class="text-center font-bold">
            <td style="padding: 12px; font-size: 11px; border-top: 1px solid #000;">
                @php
                    $genero = $project->authors->first()->gender === 'F' ? 'PROFESORA' : 'PROFESOR';
                @endphp

                {{ $genero }} DE {{ strtoupper($carrera) }}
            </td>
        </tr>
    </table>

    <!-- JURADO DINÁMICO -->
    <table style="margin-top: 10px;">
        <tr>
            <td rowspan="3" class="bg-gray text-center font-bold" style="width: 12%; font-size: 10px;">JURADO</td>
            @foreach($project->jurors as $juror)
                @if($loop->index > 0) <tr> @endif
                <td class="bg-gray font-bold" style="width: 15%;">{{ strtoupper($juror->role) }}</td>
                <td class="uppercase">{{ $juror->teacher->full_name }}</td>
                @if(!$loop->last) </tr> @endif
            @endforeach
        </tr>
    </table>

    <!-- TÍTULO TESIS -->
    <table style="margin-top: 10px; width: 100%; border-collapse: collapse;">
        <tr>
            <td class="bg-gray font-bold uppercase" style="border: 1px solid #000; padding: 4px 8px; font-size: 8px;">
                TÍTULO DE LA TESIS DE INVESTIGACIÓN:
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 15px; text-align: justify; font-size: 9px; line-height: 1.3;" class="uppercase">
                "{{ $project->title }}"
            </td>
        </tr>
    </table>

    <!-- TABLA DE CALIFICACIONES (EL CORAZÓN) -->
    <table style="margin-top: 10px;">
        <thead>
            <tr class="bg-gray text-center font-bold">
                <td style="width: 5%;">Nº</td>
                <td style="width: 12%;">Matrícula</td>
                <td>APELLIDOS Y NOMBRES</td>
                <td style="width: 10%;">PRESID.</td>
                <td style="width: 10%;">VOCAL</td>
                <td style="width: 10%;">SECRET.</td>
                <td style="width: 12%;">PROMEDIO</td>
            </tr>
        </thead>
        <tbody>
            @foreach($project->authors as $index => $author)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $author->dni }}</td>
                <td class="uppercase">{{ $author->full_name }}</td>
                <td class="text-center">{{ number_format($project->defenseAct->score_president, 1) }}</td>
                <td class="text-center">{{ number_format($project->defenseAct->score_vocal, 1) }}</td>
                <td class="text-center">{{ number_format($project->defenseAct->score_secretary, 1) }}</td>
                <td class="bg-gray text-center font-bold" style="{{ $project->defenseAct->score < 14 ? 'color: red;' : '' }}">
                    {{ number_format($project->defenseAct->score, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="font-bold" style="margin-top: 10px;">OBSERVACIONES:</div>
    <div style="border-bottom: 1px dotted #000; height: 18px;"></div>
    <div style="border-bottom: 1px dotted #000; height: 18px;"></div>

    <!-- SECCIÓN FIRMAS -->
    <!-- SECCIÓN FIRMAS JURADO (Con más espacio arriba) -->
    <table style="margin-top: 100px; border: none;">
        <tr style="border: none;">
            <!-- Presidente -->
            <td class="text-center" style="border: none; border-top: 1px solid #000; width: 30%; vertical-align: top;">
                <span class="font-bold">PRESIDENTE</span>
            </td>
            <!-- Espaciador -->
            <td style="border: none; width: 5%;"></td>
            <!-- Vocal -->
            <td class="text-center" style="border: none; border-top: 1px solid #000; width: 30%; vertical-align: top;">
                <span class="font-bold">VOCAL</span>
            </td>
            <!-- Espaciador -->
            <td style="border: none; width: 5%;"></td>
            <!-- Secretario -->
            <td class="text-center" style="border: none; border-top: 1px solid #000; width: 30%; vertical-align: top;">
                <span class="font-bold">SECRETARIO</span>
            </td>
        </tr>
    </table>

    <!-- SECCIÓN FIRMA DIRECTOR (Con más espacio arriba) -->
    <table style="margin-top: 80px; border: none;">
        <tr style="border: none;">
            <td style="border: none; width: 35%;"></td>
            <td class="text-center" style="border: none; border-top: 1px solid #000; width: 30%; vertical-align: top;">
                <span class="font-bold">vºBº DIRECTOR (A) GENERAL</span><br>
                <span style="font-size: 7px;">Firma, Post Firma y Sello</span>
            </td>
            <td style="border: none; width: 35%;"></td>
        </tr>
    </table>

    <!-- RECUADRO DE INSTRUCCIONES (Si aún cabe en la página) -->
    <div style="border: 1px solid #000; padding: 6px; margin-top: 20px; font-size: 7.5px;">
        <span class="font-bold">INSTRUCCIONES</span><br>
        - La nota mínima de aprobación de la sustenación es catorce (14) (escala vigesimal).<br>
        - El Vocal califica primero en forma individual a cada participante, luego el Secretario y finalmente el Presidente.<br>
        - El Presidente obtiene el Promedio general por participante.<br>
        - El presidente llena dos actas de Titulación por participante; una para archivo del Instituto o Escuela de Educació Superior y una para la DRE.<br>
        - El llenado del Acta obligatoriamente será con tinta líquida negra si el calificativo es aprobatorio, si es desaprobatorio con tinta roja.<br>
        - Las actas se llenan sin borrones ni enmendaduras.
    </div>

    <p style="font-size: 7px; margin-top: 5px;">* De uso externo</p>

</body>
</html>

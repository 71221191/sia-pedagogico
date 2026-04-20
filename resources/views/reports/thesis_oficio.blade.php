<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1.5cm 2cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; line-height: 1.3; color: #000; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        .header-table { width: 100%; border: none; margin-bottom: 5px; }
        .motto { text-align: center; font-style: italic; font-size: 10px; margin-bottom: 15px; }

        .oficio-header { font-size: 12px; font-weight: bold; text-decoration: underline; margin-bottom: 15px; }
        .recipient-block { margin-bottom: 15px; }
        .content-body { text-align: justify; margin-bottom: 15px; text-indent: 40px; }

        /* Estilo del Cuadro de Jurado (Igual al Excel) */
        .jury-table { width: 70%; border-collapse: collapse; margin-bottom: 15px; }
        .jury-table td { border: 1px solid #000; padding: 3px 8px; }
        .bg-gray { background-color: #f2f2f2; font-weight: bold; }

        /* Estilo de la tabla de datos central (Ancha para el título) */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 4px; text-align: center; vertical-align: middle; font-size: 9px; word-wrap: break-word; }
        .data-table th { background-color: #f2f2f2; font-weight: bold; }

        .signature-block { margin-top: 50px; text-align: center; }
        .initials { font-size: 8px; margin-top: 40px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="50%"><img src="{{ $logoMinedu }}" width="110"></td>
            <td width="50%" style="text-align: right;"><img src="{{ $logoInsti }}" width="60"></td>
        </tr>
    </table>

    <div class="motto text-center">"Año de la recuperación y consolidación de la economía peruana"</div>

    <div class="text-right">Cajamarca, {{ date('d') }} de {{ \Carbon\Carbon::now()->translatedFormat('F') }} del {{ date('Y') }}</div>

    <div class="oficio-header">Oficio Múltiple N° {{ $project->document_correlative }}-S.A/IESP "Hno.VEG"-C.</div>

    <div class="recipient-block">
        <span class="font-bold">Señores :</span><br>
        @foreach($project->jurors as $juror)
            Prof. <span class="uppercase">{{ $juror->teacher->full_name }}</span><br>
        @endforeach
        Prof. <span class="uppercase">{{ $project->advisor->full_name }}</span><br>
        <span class="font-bold" style="margin-left: 50px;">Miembros del Jurado</span><br>
        <span class="font-bold">Presente.-</span>
    </div>

    <table style="width: 100%; border: none; margin-bottom: 10px;">
        <tr>
            <td width="80" class="font-bold" style="vertical-align: top;">Asunto :</td>
            <td class="font-bold">Comunica Sustentación de Informe de tesis y adjunta Informe de Tesis.</td>
        </tr>
        <tr>
            <td class="font-bold" style="vertical-align: top;">Referencia :</td>
            <td>
                Resolución Directorial {{ $project->specialty_resolution }}<br>
                RD N°0510-2023-DG-IESP “Hno.VEG”-C - Lineamientos para sustentaciones<br>
                <span class="font-bold">
                    <!-- Usamos el ID formateado a 4 dígitos y la fecha de creación real -->
                    Expediente N° {{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }} &nbsp;&nbsp;
                    Fecha de registro: {{ $project->created_at->format('d/m/Y') }}
                </span>
            </td>
        </tr>
    </table>

    <div class="content-body">
        Me dirijo a Ud., para saludarle muy cordialmente, al mismo tiempo comunicarle que según RD. N° 0510-2023-DG-IESP “Hno.VEG”-C; y según los Lineamientos para Sustentaciones; el proceso de sustentación del estudiante que se detalla, se realizará de acuerdo al siguiente cuadro:
    </div>

    <!-- CUADRO DE JURADO (NUEVO) -->
    <table class="jury-table" align="center">
        <tr><td colspan="2" class="bg-gray text-center">JURADO</td></tr>
        @foreach($project->jurors as $juror)
            <tr>
                <td width="30%" class="bg-gray uppercase">{{ $juror->role }}</td>
                <td width="70%">Prof. {{ $juror->teacher->full_name }}</td>
            </tr>
        @endforeach
        <tr>
            <td class="bg-gray uppercase">ASESOR</td>
            <td>Prof. {{ $project->advisor->full_name }}</td>
        </tr>
    </table>

    <!-- TABLA DE DATOS DEL ESTUDIANTE -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">DNI</th>
                <th width="23%">Apellidos y Nombres</th>
                <th width="35%">Título</th>
                <th width="15%">Programa de Estudios</th>
                <th width="15%">Fecha y hora</th>
                <th width="10%">Promoción</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">
                    @foreach($project->authors as $author)
                        <div style="margin-bottom: 5px;">{{ $author->dni }}</div>
                    @endforeach
                </td>
                <td class="uppercase">
                    @foreach($project->authors as $author)
                        <div style="margin-bottom: 5px; font-weight: bold;">
                            {{ $author->last_name_p }} {{ $author->last_name_m }}, {{ $author->names }}
                        </div>
                    @endforeach
                </td>
                <td style="text-align: justify; font-size: 8px;">{{ $project->title }}</td>
                <td class="text-center uppercase" style="font-size: 8px;">
                    {{ $project->authors->first()->enrollments->first()->studyPlan->studyProgram->name ?? 'EDUCACIÓN' }}
                </td>
                <td class="text-center">
                    @if($project->scheduled_date)
                        <span class="font-bold">{{ \Carbon\Carbon::parse($project->scheduled_date)->format('d/m/Y') }}</span><br>
                        {{ date('h:i a', strtotime($project->scheduled_time)) }}
                    @else
                        <span style="color: red;">NO PROGRAMADO</span>
                    @endif
                </td>
                <td>{{ $project->promotion_year }}</td>
            </tr>
        </tbody>
    </table>

    <div class="content-body" style="margin-top: 15px; text-indent: 0;">
        Luego de ejecutado el proceso de sustentación el secretario de jurado entregará antes de las 48 horas las actas de sustentación y nóminas a la oficina de Secretaría Académica.
    </div>

    <div class="content-body" style="text-indent: 0;">
        Agradeciendo por anticipado la atención al presente, me suscribo de ustedes.
    </div>

    <div class="signature-block" style="width: 250px; margin-left: auto;">
        <span class="font-bold">Atentamente,</span>
        <br><br><br><br>
        <div style="border-top: 1px solid #000; padding-top: 5px;">
            <span class="font-bold uppercase">Secretaría Académica</span>
        </div>
    </div>

    <div class="initials">
        RLSM/S.A.<br>
        NGG/Sec.
    </div>
</body>
</html>

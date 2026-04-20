<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm 1.2cm; size: A4; }

        body {
            font-family: Arial, sans-serif;
            font-size: 8.5px;
            color: #000;
            line-height: 1.1;
            margin: 0; }

        table {
            width: 100%;
            border-collapse:
            collapse;
            table-layout: fixed; margin-bottom: -1px; }

        td { border: 1px solid #000;
            padding: 4px;
            vertical-align: middle; }

        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #f2f2f2; }
        .uppercase { text-transform: uppercase; }

        /* Estilo para el encabezado (SIN BORDES) */
        .no-border, .no-border tr, .no-border td { border: none !important; padding: 0; }

        .header-main { font-size: 15px; font-weight: bold; margin-top: 10px; text-align: center; }

        /* Estilo Fecha arriba a la derecha */
        .fecha-container { float: right; width: 110px; }
        .fecha-table { width: 100%; border-collapse: collapse !important; border: none !important; }
        .fecha-table td { border: 1px solid black !important; padding: 2px; font-size: 8px; text-align: center; background: #fff; }
    </style>
</head>
<body>

    <!-- LOGOS Y FECHA (ESTANDARIZADO) -->
    <table class="no-border" style="margin-bottom: 10px;">
        <tr class="no-border">
            <td class="no-border" style="width: 15%;"><img src="{{ $logoInsti }}" style="height: 60px;"></td>
            <!-- Reemplaza la celda central (la del logo del MINEDU) por esta -->
            <td class="no-border" style="width: 65%; text-align: center; vertical-align: middle;">
                <img src="{{ $logoMinedu }}" style="height: 40px; display: block; margin: 0 auto;">
                <div class="header-main">NÓMINA DE EXPEDITOS</div>
            </td>
            <td class="no-border" style="width: 20%; vertical-align: top;">
                <div class="fecha-container">
                    <div class="text-center font-bold" style="margin-bottom: 3px; font-size: 9px;">Fecha</div>
                    <table class="fecha-table text-center">
                        <tr class="bg-gray font-bold">
                            <td>Día</td><td>Mes</td><td>Año</td>
                        </tr>
                        <tr>
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
            <td class="text-center font-bold" style="font-size: 11px; letter-spacing: 3px;">0391151</td>
            <td class="text-center font-bold">I.E.S.P.</td>
            <td class="text-center font-bold">PÚBLICA</td>
            <td class="text-center" style="font-size: 7px;">{{ $ds_creacion }}</td>
            <td class="font-bold text-center" style="font-size: 7px;">AV. EL MAESTRO # 290</td>
            <td class="bg-gray">Provincia</td>
            <td>CAJAMARCA</td>
        </tr>
        <tr>
            <td class="bg-gray">Carrera / Especialidad</td>
            <td colspan="4" class="font-bold uppercase">PROFESORA DE EDUCACIÓN : {{ strtoupper($carrera) }}</td>
            <td class="bg-gray">Distrito</td>
            <td>CAJAMARCA</td>
        </tr>

        <tr>
            <td class="bg-gray">Resolución de Autorización</td>
            <td colspan="6">{{ $project->auto_resolution }}</td>
        </tr>

        <tr>
            <td class="bg-gray">Director (a) General</td>
            <td colspan="6" class="font-bold uppercase">FERNANDO MARTÍN VERGARA ABANTO</td>
        </tr>

        <!-- ESTE CAMPO ES EXCLUSIVO DE LA NÓMINA -->
        <tr>
            <td class="bg-gray">Resolución Directoral (*)</td>
            <td colspan="6">{{ $project->specialty_resolution }}</td>
        </tr>
    </table>
    <p style="font-size: 7px; margin-top: 2px;">* R.D. que autoriza la sustentación</p>

    <!-- TABLA DE ALUMNOS (SIMPLIFICADA PARA NÓMINA) -->
    <table style="margin-top: 15px;">
        <thead>
            <tr class="bg-gray text-center font-bold">
                <td style="width: 8%;">Nº Orden</td>
                <td style="width: 15%;">Nº Matrícula</td>
                <td>APELLIDOS Y NOMBRES <br><small>(Por orden alfabético)</small></td>
                <td style="width: 15%;">PROMEDIO OBTENIDO</td>
            </tr>
        </thead>
        <tbody>
            @foreach($project->authors as $index => $author)
            <tr>
                <td class="text-center">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="text-center">{{ $author->dni }}</td>
                <td class="uppercase">{{ $author->full_name }}</td>
                <td class="text-center font-bold bg-gray" style="{{ $project->defenseAct->score < 14 ? 'color: red;' : '' }}">
                    {{ number_format($project->defenseAct->score, 2) }}
                </td>
            </tr>
            @endforeach
            @for($i = count($project->authors) + 1; $i <= 3; $i++)
            <tr style="height: 18px;">
                <td class="text-center">0{{$i}}</td>
                <td></td><td></td><td class="bg-gray"></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <!-- INSTRUCCIONES ESPECÍFICAS NÓMINA -->
    <div style="border: 1px solid #000; padding: 6px; margin-top: 20px; font-size: 7.5px;">
        <span class="font-bold">INSTRUCCIONES</span><br>
        - Llenar el acta por grupo de tesis.<br>
        - Llenar sin enmendaduras, considerando sólo la relación de los egresados aprobados en la sustentación.
    </div>

    <!-- SECCIÓN FIRMAS NÓMINA (SOLO 2 FIRMAS) -->
    <table style="margin-top: 80px; border: none;">
        <tr style="border: none;">
            <td class="text-center" style="border: none; border-top: 1px solid #000; width: 40%; vertical-align: top;">
                <span class="font-bold uppercase">Secretario Académico</span><br>
                <span style="font-size: 7px;">Firma, Post Firma y Sello</span>
            </td>
            <td style="border: none; width: 20%;"></td>
            <td class="text-center" style="border: none; border-top: 1px solid #000; width: 40%; vertical-align: top;">
                <span class="font-bold uppercase">Director (A) General</span><br>
                <span style="font-size: 7px;">Firma, Post Firma y Sello</span>
            </td>
        </tr>
    </table>

    <p style="font-size: 7px; margin-top: 15px;">* De uso externo</p>

</body>
</html>

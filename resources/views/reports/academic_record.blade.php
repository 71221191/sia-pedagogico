<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1.5cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; line-height: 1.3; }
        .header-table { width: 100%; border: none; margin-bottom: 20px; }
        .title { text-align: center; font-size: 16px; font-weight: bold; text-decoration: underline; margin-bottom: 20px; }
        .student-info { margin-bottom: 20px; padding: 10px; border: 1px solid #eee; background: #f9f9f9; }

        .period-header { background: #312e81; color: white; padding: 5px 10px; font-weight: bold; margin-top: 15px; text-transform: uppercase; }
        .main-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .main-table th, .main-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .main-table th { background: #f2f2f2; font-size: 9px; text-transform: uppercase; }

        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .footer-stats { margin-top: 30px; border-top: 2px solid #312e81; padding-top: 10px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="30%"><img src="{{ $logoMinedu }}" width="110"></td>
            <td class="text-center" width="40%"><strong>IESP "HNO. VICTORINO ELORZ GOICOECHEA"</strong><br>CAJAMARCA</td>
            <td width="30%" style="text-align: right;"><img src="{{ $logoInsti }}" width="50"></td>
        </tr>
    </table>

    <div class="title">HISTORIAL ACADÉMICO / RÉCORD DE NOTAS</div>

    <div class="student-info">
        <table width="100%" style="border:none;">
            <tr>
                <td style="border:none;"><strong>ESTUDIANTE:</strong> {{ $person->last_name_p }} {{ $person->last_name_m }}, {{ $person->names }}</td>
                <td style="border:none;"><strong>DNI:</strong> {{ $person->dni }}</td>
            </tr>
        </table>
    </div>

    @foreach($history as $enrollment)
        <div class="period-header">PERIODO: {{ $enrollment->academicPeriod->name }} - CICLO: {{ $enrollment->cycle }}</div>
        <table class="main-table">
            <thead>
                <tr>
                    <th width="15%">CÓDIGO</th>
                    <th width="45%">ASIGNATURA / UNIDAD DIDÁCTICA</th>
                    <th width="10%" class="text-center">CRÉD.</th>
                    <th width="10%" class="text-center">NOTA</th>
                    <th width="20%">RESULTADO</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollment->details as $detail)
                <tr>
                    <td>{{ $detail->course->code }}</td>
                    <td>{{ $detail->course->name }}</td>
                    <td class="text-center">{{ $detail->course->credits }}</td>
                    <td class="text-center font-bold" style="{{ $detail->final_score_numeric < 11 ? 'color:red;' : '' }}">
                        {{ number_format($detail->final_score_numeric, 0) }}
                    </td>
                    <td class="text-center" style="font-size: 8px;">
                        {{ $detail->final_score_numeric >= 11 ? 'APROBADO' : 'DESAPROBADO' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="footer-stats">
        <table width="100%" style="border:none;">
            <tr>
                <td style="border:none; font-size: 12px;"><strong>PROMEDIO PONDERADO ACUMULADO:</strong> {{ number_format($ppa, 4) }}</td>
                <td style="border:none; font-size: 12px; text-align: right;"><strong>TOTAL CRÉDITOS APROBADOS:</strong> {{ $totalCredits }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px; text-align: center; font-style: italic; color: #888;">
        <p>Documento generado digitalmente por el Sistema de Gestión Académica (SIA) - {{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>

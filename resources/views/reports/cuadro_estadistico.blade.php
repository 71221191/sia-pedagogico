<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm; }
        body { font-family: sans-serif; font-size: 8px; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 4px 2px; text-align: center; }

        .header-table { border: none; margin-bottom: 15px; }
        .header-table td { border: none; }

        /* Colores y Estilos */
        .bg-gray { background-color: #f1f5f9; font-weight: bold; }
        .bg-total-career { background-color: #f8fafc; font-weight: bold; border-top: 2px solid #94a3b8; }
        .col-ciclo { width: 45px; }
        .col-gender { width: 25px; }
        .col-total-prog { width: 32px; background-color: rgba(0,0,0,0.03); font-weight: bold; }

        /* Esta columna es más ancha para resaltar */
        .col-total-final { width: 50px; background-color: #1e293b; color: white; font-weight: bold; font-size: 10px; }

        .prog-header { font-size: 7px; height: 35px; color: #000; font-weight: bold; }
        .gender-m { color: #2563eb; }
        .gender-f { color: #db2777; }

        /* Fila de cierre final */
        .footer-row { background-color: #f1f5f9; font-weight: bold; }
        .page-break { page-break-after: always; }
        /* Estilos para los cuadros de resumen (KPIs) */
        .kpi-box {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 10px;
            border-radius: 10px;
            width: 23%;
            display: inline-block;
            margin-right: 1%;
            vertical-align: top;
        }
        .kpi-val {
            font-size: 14px;
            font-weight: bold;
            display: block;
            color: #1e293b;
        }
        .kpi-lbl {
            font-size: 7px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
</head>
<body>

    @foreach($programChunks as $chunkIndex => $currentPrograms)
        <div class="{{ !$loop->last ? 'page-break' : '' }}">

            <!-- ENCABEZADO (Igual que antes) -->
            <table class="header-table">
                <tr>
                    <td width="20%"><img src="{{ $logoInsti }}" width="50"></td>
                    <td width="60%" style="text-align: center;">
                        <div style="font-size: 14px; font-weight: bold;">CUADRO ESTADÍSTICO DE MATRÍCULA</div>
                        <div style="font-size: 9px; color: #64748b;">{{ $periodo->name }}
                    </td>
                    <td width="20%" style="text-align: right;"><img src="{{ $logoMinedu }}" width="110"></td>
                </tr>
            </table>

            <!-- PEGA EL BLOQUE DE KPIs JUSTO AQUÍ -->
            @if($loop->first)
            <div style="margin-bottom: 20px; text-align: center; width: 100%;">
                <div class="kpi-box">
                    <span class="kpi-val">{{ $grandTotal }}</span>
                    <span class="kpi-lbl">Total Estudiantes</span>
                </div>
                <div class="kpi-box" style="border-left: 3px solid #2563eb;">
                    <span class="kpi-val">{{ $grandTotalM }}</span>
                    <span class="kpi-lbl">Varones ({{ $grandTotal > 0 ? round(($grandTotalM/$grandTotal)*100) : 0 }}%)</span>
                </div>
                <div class="kpi-box" style="border-left: 3px solid #db2777;">
                    <span class="kpi-val">{{ $grandTotalF }}</span>
                    <span class="kpi-lbl">Mujeres ({{ $grandTotal > 0 ? round(($grandTotalF/$grandTotal)*100) : 0 }}%)</span>
                </div>
{{--                 <div class="kpi-box">
                    <span class="kpi-val">{{ $totalDisability ?? 0 }}</span>
                    <span class="kpi-lbl">Alumnos NEE (Inclusión)</span>
                </div> --}}
            </div>
            @endif

            <table>
                <thead>
                    <tr class="bg-gray">
                        <th rowspan="2" class="col-ciclo">CICLO</th>
                        @foreach($currentPrograms as $prog)
                            <th colspan="3" class="prog-header" style="background-color: {{ $programColors[$prog] }};">
                                {{ $prog }}
                            </th>
                        @endforeach

                        {{-- IDEA 3: Solo mostramos la columna Total si es el último grupo --}}
                        @if($loop->last)
                            <th rowspan="2" class="col-total-final">TOTAL<br>INSTITUTO</th>
                        @endif
                    </tr>
                    <tr class="bg-gray">
                        @foreach($currentPrograms as $prog)
                            <th class="col-gender">M</th>
                            <th class="col-gender">F</th>
                            <th class="col-total-prog">T</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($cycles as $c)
                    <tr>
                        <td class="bg-gray">{{ $numberHelper->toRoman($c) }}</td>
                        @foreach($currentPrograms as $prog)
                            <td class="gender-m">{{ $matrix[$c]['programs'][$prog]['M'] ?: '-' }}</td>
                            <td class="gender-f">{{ $matrix[$c]['programs'][$prog]['F'] ?: '-' }}</td>
                            <td class="col-total-prog">{{ $matrix[$c]['programs'][$prog]['total'] ?: '-' }}</td>
                        @endforeach

                        {{-- Solo mostramos el total de la fila en la última página --}}
                        @if($loop->parent->last)
                            <td class="col-total-final" style="color:white;">{{ $matrix[$c]['row_total'] ?: '-' }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    {{-- TOTALES POR CARRERA (Lo que faltaba) --}}
                    <tr class="footer-row">
                        <td class="text-right bg-gray">TOTAL:</td>
                        @foreach($currentPrograms as $prog)
                            <td class="gender-m bg-total-career">{{ $totalsByProgram[$prog]['M'] }}</td>
                            <td class="gender-f bg-total-career">{{ $totalsByProgram[$prog]['F'] }}</td>
                            <td class="bg-total-career" style="background-color: #e2e8f0;">{{ $totalsByProgram[$prog]['total'] }}</td>
                        @endforeach

                        {{-- Gran Total al final de la última página --}}
                        @if($loop->last)
                            <td style="background-color: #000; color: #fff; font-size: 11px;">{{ $grandTotal }}</td>
                        @endif
                    </tr>
                </tfoot>
            </table>

            <div style="margin-top: 10px; font-size: 7px; color: #94a3b8; text-align: right;">
                Página {{ $chunkIndex + 1 }} de {{ count($programChunks) }} | SIA - {{ date('d/m/Y H:i') }}
            </div>
        </div>
    @endforeach

</body>
</html>

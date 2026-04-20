<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm; }
        body { font-family: sans-serif; font-size: 8px; color: #333; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; vertical-align: middle; }
        .header-table { border: none; margin-bottom: 10px; }
        .header-table td { border: none; }
        .bg-gray { background-color: #f2f2f2; font-weight: bold; }
        .course-box { font-weight: bold; font-size: 7px; }
        .teacher-name { font-size: 6px; font-style: italic; color: #666; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="20%"><img src="{{ $logoMinedu }}" width="100"></td>
            <td class="text-center" style="font-size: 14px; font-weight: bold;">
                HORARIO SEMANAL DE CLASES - {{ $periodName }}<br>
                <span style="font-size: 10px;">{{ $person->last_name_p }} {{ $person->last_name_m }}, {{ $person->names }}</span>
            </td>
            <td width="20%" style="text-align: right;"><img src="{{ $logoInsti }}" width="50"></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr class="bg-gray">
                <th width="60px">HORA</th>
                @foreach($days as $day)
                    <th>{{ $day['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($timeSlots as $slot)
                <tr>
                    <td class="bg-gray">
                        {{ $slot->is_break ? 'RECESO' : date('H:i', strtotime($slot->start_time)) }}
                    </td>
                    @if($slot->is_break)
                        <td colspan="5" style="background-color: #f9f9f9; font-style: italic;">Descanso</td>
                    @else

                    @foreach($days as $day)
                        @php
                            // Construimos la llave para buscar en el mapa
                            $key = $day['id'] . '-' . $slot->id;
                            $clase = $mapaHorario[$key] ?? null;
                        @endphp
                        <td>
                            @if($clase)
                                <div class="course-box">{{ $clase['curso'] }}</div>
                                <div class="teacher-name">{{ $clase['profe'] }}</div>
                                <div style="font-size: 6px; font-weight: bold; color: #312e81;">
                                    {{ $clase['aula'] }}
                                </div>
                            @endif
                        </td>
                    @endforeach

                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 7px; color: #888; text-align: center;">
        Documento generado por el Sistema de Gestión Académica (SIA) - IESPHVEG
    </div>
</body>
</html>

<table>
    <thead>
        <tr><td height="60"></td></tr>
        <tr>
            <th colspan="6" style="font-size: 16pt; font-weight: bold; text-align: center; vertical-align: middle;">
                HORARIO SEMANAL - {{ $periodName }}
            </th>
        </tr>
        <tr>
            <th colspan="6" style="font-size: 11pt; text-align: center; color: #4b5563;">
                ESTUDIANTE: {{ $person->full_name }}
            </th>
        </tr>
        <tr style="background-color: #1e293b; color: #ffffff;">
            <th style="border: 1px solid #000000; text-align: center;">HORA / BLOQUE</th>
            @foreach($days as $day)
                <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">{{ $day['name'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($timeSlots as $slot)
            <tr>
                <!-- Columna de Hora con Bloque -->
                <td style="background-color: #f8fafc; border: 1px solid #000000; text-align: center;">
                    <font size="1"><b>{{ $slot->is_break ? 'REC' : 'B' . $slot->order }}</b></font><br>
                    <font size="1" color="#64748b">{{ date('H:i', strtotime($slot->start_time)) }} - {{ date('H:i', strtotime($slot->end_time)) }}</font>
                </td>

                @if($slot->is_break)
                    <td colspan="5" style="background-color: #f1f5f9; text-align: center; font-style: italic; border: 1px solid #000000; color: #94a3b8;">
                        RECESO INSTITUCIONAL
                    </td>
                @else
                    @foreach($days as $day)
                        @php
                            $key = $day['id'] . '-' . $slot->id;
                            $clase = $mapaHorario[$key] ?? null;
                        @endphp
                        <td style="border: 1px solid #000000; vertical-align: top; padding: 5px;">
                            @if($clase)
                                <font size="1"><b>{{ $clase['curso'] }}</b></font><br>
                                <font size="1" color="#4b5563"><i>{{ $clase['profe'] }}</i></font><br>
                                <font size="1" color="#1e293b"><b>{{ $clase['aula'] }}</b></font>
                            @endif
                        </td>
                    @endforeach
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

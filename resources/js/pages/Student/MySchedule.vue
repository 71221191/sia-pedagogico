<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Calendar, Clock, MapPin, User, Download } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    schedules: any[],
    timeSlots: any[],
    days: any[],
    shiftId: number
}>();

// Función para buscar la clase en una celda
const getAssignment = (dayId: number, slotId: number) => {
    return props.schedules.find(s => s.day_of_week == dayId && s.time_slot_id == slotId);
};

// Generador de colores simple basado en el nombre del curso
const getCourseColor = (courseId: number) => {
    const colors = [
        'bg-blue-50 text-blue-700 border-blue-200',
        'bg-emerald-50 text-emerald-700 border-emerald-200',
        'bg-violet-50 text-violet-700 border-violet-200',
        'bg-amber-50 text-amber-700 border-amber-200',
        'bg-rose-50 text-rose-700 border-rose-200',
        'bg-cyan-50 text-cyan-700 border-cyan-200',
        'bg-orange-50 text-orange-700 border-orange-200'
    ];
    return colors[courseId % colors.length];
};

</script>

<template>
    <Head title="Mi Horario" />
    <AppLayout>
        <div class="p-4 md:p-8 max-w-full mx-auto bg-gray-50 min-h-screen">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Mi Horario Semanal</h1>
                    <div class="flex items-center space-x-2 mt-2">
                        <!-- Badge de Turno Dinámico -->
                        <span :class="shiftId === 1 ? 'bg-yellow-400 text-yellow-900' : 'bg-indigo-900 text-white'"
                            class="px-3 py-1 rounded-full text-[10px] font-black italic uppercase flex items-center gap-1 shadow-sm">
                            <Sun v-if="shiftId === 1" class="w-3 h-3" />
                            <Moon v-else class="w-3 h-3" />
                            {{ shiftId === 1 ? 'Turno Mañana' : 'Turno Tarde' }}
                        </span>
                        <span class="text-gray-400 font-bold text-[10px] uppercase tracking-widest pl-2">Periodo 2026-I</span>
                    </div>
                </div>
                <!-- Botón PDF -->
                <a :href="route('student.schedule.pdf')"
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-xl font-black text-[10px] uppercase hover:bg-rose-700 transition-all shadow-lg shadow-rose-100">
                    <FileText class="w-4 h-4 mr-2" />
                    Descargar Horario PDF
                </a>

                <!-- Botón EXCEL (Nuevo) -->
                <a :href="route('student.schedule.excel')"
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-xl font-black text-[10px] uppercase hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100">
                    <FileSpreadsheet class="w-4 h-4 mr-2" />
                    Descargar Horario Excel
                </a>
            </div>

            <!-- CUADRÍCULA -->
            <div class="bg-white rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden overflow-x-auto">
                <table class="w-full border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-gray-900 text-white text-[10px] uppercase font-black tracking-widest">
                            <th class="p-4 w-32 border-r border-gray-800">Hora</th>
                            <th v-for="day in days" :key="day.id" class="p-4 border-r border-gray-800 text-center">{{ day.name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="slot in timeSlots" :key="slot.id" :class="slot.is_break ? 'bg-gray-50' : ''">
                            <!-- Columna Hora -->
                            <td class="p-4 border-r border-b text-center bg-gray-50/50">
                                <div class="text-[10px] font-black text-gray-800">
                                    {{ slot.is_break ? 'DESCANSAR' : 'BLOQUE ' + slot.order }}
                                </div>
                                <div class="text-[9px] text-gray-400 font-mono">
                                    {{ slot.start_time.substring(0,5) }} - {{ slot.end_time.substring(0,5) }}
                                </div>
                            </td>

                            <!-- Celdas de Clases -->
                            <template v-if="!slot.is_break">
                                <td v-for="day in days" :key="day.id" class="border-r border-b p-1 h-28 relative">

                                    <div v-if="getAssignment(day.id, slot.id)"
                                        :class="getCourseColor(getAssignment(day.id, slot.id).course_id)"
                                        class="w-full h-full rounded-2xl p-3 flex flex-col justify-between shadow-sm transition-transform hover:scale-[1.03] border">

                                        <!-- Nombre del Curso -->
                                        <div class="text-[9px] font-black uppercase leading-tight line-clamp-2">
                                            {{ getAssignment(day.id, slot.id).course.name }}
                                        </div>

                                        <!-- Info inferior (Profe y Aula) -->
                                        <div class="mt-auto">
                                            <p class="text-[8px] font-bold opacity-90 truncate flex items-center mb-1">
                                                <User class="w-2.5 h-2.5 mr-1" />
                                                {{ getAssignment(day.id, slot.id).teacher.full_name }}
                                            </p>
                                            <div class="flex items-center text-[9px] font-black uppercase">
                                                <MapPin class="w-3 h-3 mr-1 opacity-50" />
                                                {{ getAssignment(day.id, slot.id).classroom?.name || 'Aula S.A.' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- El v-else (Celda vacía) déjalo como estaba o usa este más limpio -->
                                    <div v-else class="w-full h-full flex items-center justify-center opacity-[0.03]">
                                        <Calendar class="w-8 h-8 text-gray-900" />
                                    </div>
                                </td>
                            </template>

                            <!-- RECREO -->
                            <td v-else :colspan="days.length" class="text-center text-[10px] font-black text-gray-300 tracking-[1em] uppercase border-b border-r bg-gray-50/50 italic py-4">
                                Receso Institucional
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Leyenda Informativa -->
            <div class="mt-8 flex items-center justify-center space-x-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <div class="flex items-center"><div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div> Clases Presenciales</div>
                <div class="flex items-center"><div class="w-3 h-3 bg-gray-200 rounded-full mr-2"></div> Ambientes Especiales</div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Para que el texto largo del curso no rompa el diseño */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

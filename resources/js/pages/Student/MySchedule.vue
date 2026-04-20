<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Calendar, Clock, MapPin, User, Download } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    schedules: any[],
    timeSlots: any[],
    days: any[]
}>();

// Función para buscar la clase en una celda
const getAssignment = (dayId: number, slotId: number) => {
    return props.schedules.find(s => s.day_of_week == dayId && s.time_slot_id == slotId);
};

// Generador de colores simple basado en el nombre del curso
const getCourseColor = (courseName: string) => {
    const colors = [
        'bg-blue-100 text-blue-700 border-blue-200',
        'bg-indigo-100 text-indigo-700 border-indigo-200',
        'bg-purple-100 text-purple-700 border-purple-200',
        'bg-emerald-100 text-emerald-700 border-emerald-200',
        'bg-amber-100 text-amber-700 border-amber-200',
        'bg-rose-100 text-rose-700 border-rose-200',
    ];
    let hash = 0;
    for (let i = 0; i < courseName.length; i++) {
        hash = courseName.charCodeAt(i) + ((hash << 5) - hash);
    }
    return colors[Math.abs(hash) % colors.length];
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
                    <p class="text-gray-500 italic text-sm">Organización de clases para el presente semestre.</p>
                </div>
                <a :href="route('schedule.pdf')"
                target="_blank"
                class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl font-bold text-xs uppercase flex items-center hover:bg-gray-50 transition shadow-sm">
                    <Download class="w-4 h-4 mr-2" />
                    Descargar Horario PDF
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
                                <div class="text-[10px] font-black text-gray-400 uppercase">
                                    {{ slot.is_break ? 'Receso' : 'Bloque ' + slot.order }}
                                </div>
                                <div class="text-xs font-bold text-gray-900 font-mono">
                                    {{ slot.start_time.substring(0,5) }}
                                </div>
                            </td>

                            <!-- Celdas de Clases -->
                            <template v-if="!slot.is_break">
                                <td v-for="day in days" :key="day.id" class="border-r border-b p-1 h-28 relative">
                                    <div v-if="getAssignment(day.id, slot.id)"
                                         :class="getCourseColor(getAssignment(day.id, slot.id).course.name)"
                                         class="w-full h-full rounded-2xl p-3 flex flex-col justify-between border shadow-sm transition-transform hover:scale-[1.02]">

                                        <div class="text-[10px] font-black uppercase leading-tight line-clamp-2">
                                            {{ getAssignment(day.id, slot.id).course.name }}
                                        </div>

                                        <div class="mt-2 space-y-1">
                                            <div class="flex items-center text-[9px] font-bold opacity-80 uppercase">
                                                <User class="w-3 h-3 mr-1" />
                                                {{ getAssignment(day.id, slot.id).teacher.last_name_p }}
                                            </div>
                                            <div class="flex items-center text-[9px] font-black uppercase">
                                                <MapPin class="w-3 h-3 mr-1" />
                                                {{ getAssignment(day.id, slot.id).classroom?.name || 'Aula S.A.' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="w-full h-full flex items-center justify-center opacity-5">
                                        <Calendar class="w-6 h-6 text-gray-300" />
                                    </div>
                                </td>
                            </template>

                            <!-- RECREO -->
                            <td v-else :colspan="days.length" class="text-center text-[10px] font-black text-gray-300 tracking-[1em] uppercase border-b bg-gray-50/50 italic py-2">
                                Descanso
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

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, Head, router } from '@inertiajs/vue3';
import {
    Calendar, Clock, MapPin, Save,
    ArrowLeft, AlertCircle, CheckCircle, Trash2
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    section: any,
    timeSlots: any[],
    classrooms: any[],
    currentSchedules: any[],
    days: any[]
}>();

// Estado para el modal de asignación
const isModalOpen = ref(false);
const selectedSlot = ref<any>(null);
const selectedDay = ref<number | null>(null);

const form = useForm({
    day_of_week: '',
    time_slot_id: '',
    classroom_id: '',
    collision: '',
});

// Función para abrir el modal al hacer clic en una celda vacía
const openAssignModal = (dayId: number, slotId: number) => {
    selectedDay.value = dayId;
    selectedSlot.value = props.timeSlots.find(s => s.id === slotId);
    form.day_of_week = dayId.toString();
    form.time_slot_id = slotId.toString();
    isModalOpen.value = true;
};

const submit = () => {
    form.post(route('admin.course_sections.schedule.store', props.section.id), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset('classroom_id');
        },
    });
};

// Función para buscar si una celda ya tiene clase asignada
const getAssignment = (dayId: number, slotId: number) => {
    return props.currentSchedules.find(s => s.day_of_week == dayId && s.time_slot_id == slotId);
};

// Lógica de "Presupuesto de Horas" (Calcula cuánto falta asignar)
const assignedHours = computed(() => props.currentSchedules.length);
const totalHoursNeeded = computed(() => props.section.course.hours_total);

const deleteAssignment = (id: number) => {
    if (confirm('¿Deseas quitar este curso de este horario?')) {
        router.delete(route('admin.course_sections.schedule.destroy', id), {
            preserveScroll: true
        });
    }
};

</script>

<template>
    <Head title="Constructor de Horarios" />
    <AppLayout>
        <div class="p-4 md:p-8 max-w-full mx-auto bg-gray-50 min-h-screen">
            <!-- Header Inteligente -->
            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <Link :href="route('admin.course_sections.index')" class="text-xs font-bold text-gray-400 uppercase flex items-center mb-2 hover:text-indigo-600 transition">
                        <ArrowLeft class="w-4 h-4 mr-1" /> Volver a secciones
                    </Link>
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none">
                        {{ section.course.name }}
                    </h1>
                    <p class="text-indigo-600 font-bold text-xs uppercase mt-2">
                        Sección {{ section.name }} | Docente: {{ section.teacher ? section.teacher.names : 'Sin asignar' }}
                    </p>
                </div>

                <!-- Widget de Progreso de Horas -->
                <div class="bg-white px-6 py-4 rounded-3xl shadow-xl border border-indigo-100 flex items-center space-x-6">
                    <div class="text-center">
                        <span class="block text-[8px] font-black text-gray-400 uppercase">Horas Requeridas</span>
                        <span class="text-2xl font-black text-gray-800">{{ totalHoursNeeded }}h</span>
                    </div>
                    <div class="h-10 w-[2px] bg-gray-100"></div>
                    <div class="text-center">
                        <span class="block text-[8px] font-black text-gray-400 uppercase">Asignadas</span>
                        <span class="text-2xl font-black" :class="assignedHours == totalHoursNeeded ? 'text-green-600' : 'text-orange-500'">
                            {{ assignedHours }}h
                        </span>
                    </div>
                </div>
            </div>

            <!-- CUADRÍCULA DEL HORARIO -->
            <div class="bg-white rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden">
                <table class="w-full border-collapse table-fixed">
                    <thead>
                        <tr class="bg-gray-900 text-white text-[10px] uppercase font-black tracking-widest">
                            <th class="p-4 w-32 border-r border-gray-800">Hora / Bloque</th>
                            <th v-for="day in days" :key="day.id" class="p-4 border-r border-gray-800">{{ day.name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="slot in timeSlots" :key="slot.id" :class="slot.is_break ? 'bg-gray-50' : ''">
                            <!-- Columna de Tiempo -->
                            <td class="p-4 border-r border-b text-center bg-gray-50/50">
                                <div class="text-[10px] font-black text-gray-800">{{ slot.is_break ? 'DESCANSAR' : 'BLOQUE ' + slot.order }}</div>
                                <div class="text-[9px] text-gray-400 font-mono">{{ slot.start_time.substring(0,5) }} - {{ slot.end_time.substring(0,5) }}</div>
                            </td>

                            <!-- Celdas de los Días -->
                            <template v-if="!slot.is_break">
                                <td v-for="day in days" :key="day.id" class="border-r border-b p-1 h-24 relative group">

                                    <!-- ESTADO A: YA TIENE CLASE -->
                                    <div v-if="getAssignment(day.id, slot.id)"
                                         class="w-full h-full bg-indigo-600 text-white rounded-2xl p-3 flex flex-col justify-between shadow-lg shadow-indigo-100 animate-in zoom-in-95 duration-300">
                                        <div class="text-[9px] font-black uppercase leading-tight">En curso</div>
                                        <div class="flex items-center text-[10px] font-bold mt-auto uppercase">
                                            <MapPin class="w-3 h-3 mr-1 opacity-50" />
                                            {{ getAssignment(day.id, slot.id).classroom?.name || 'Aula pendiente' }}
                                        </div>
                                        <!-- Botón para quitar bloque -->
                                        <button @click="deleteAssignment(getAssignment(day.id, slot.id).id)"
                                                class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 text-white p-1 rounded-lg shadow-md hover:bg-red-600">
                                            <Trash2 class="w-3 h-3" />
                                        </button>
                                    </div>

                                    <!-- ESTADO B: CELDA VACÍA (Click para asignar) -->
                                    <button v-else-if="assignedHours < totalHoursNeeded"
                                            @click="openAssignModal(day.id, slot.id)"
                                            class="w-full h-full rounded-2xl border-2 border-dashed border-gray-100 hover:border-indigo-300 hover:bg-indigo-50 transition-all flex items-center justify-center text-gray-200 hover:text-indigo-400">
                                        <Plus class="w-6 h-6" />
                                    </button>

                                    <!-- ESTADO C: HORAS COMPLETADAS -->
                                    <div v-else class="w-full h-full flex items-center justify-center opacity-20">
                                        <CheckCircle class="w-6 h-6 text-gray-300" />
                                    </div>
                                </td>
                            </template>

                            <!-- RECREO -->
                            <td v-else :colspan="days.length" class="text-center text-[10px] font-black text-gray-300 tracking-[1em] uppercase border-b border-r bg-gray-50/50 italic">
                                Receso Institucional
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- MODAL DE ASIGNACIÓN RÁPIDA -->
            <div v-if="isModalOpen" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in-95">
                    <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
                        <h2 class="font-black text-gray-800 uppercase tracking-tighter">Asignar Ambiente</h2>
                        <button @click="isModalOpen = false" class="text-gray-400 hover:text-red-500 text-2xl font-black">×</button>
                    </div>

                    <form @submit.prevent="submit" class="p-8 space-y-6">
                        <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100 flex items-center space-x-4">
                            <Clock class="text-indigo-600 w-6 h-6" />
                            <div>
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest leading-none">Horario Seleccionado</p>
                                <p class="text-sm font-bold text-indigo-900 uppercase mt-1">
                                    {{ days.find(d => d.id === selectedDay)?.name }} | {{ selectedSlot?.start_time.substring(0,5) }} - {{ selectedSlot?.end_time.substring(0,5) }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Seleccionar Aula / Laboratorio</label>
                            <select v-model="form.classroom_id" class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 font-bold text-sm" required>
                                <option value="">-- Asignar después --</option>
                                <option v-for="room in classrooms" :key="room.id" :value="room.id">
                                    {{ room.name }} (Cap: {{ room.capacity }})
                                </option>
                            </select>
                        </div>

                        <div v-if="form.errors.collision" class="p-3 bg-red-50 border border-red-200 rounded-xl flex items-center text-red-600 text-[10px] font-bold italic">
                            <AlertCircle class="w-4 h-4 mr-2 flex-shrink-0" />
                            {{ form.errors.collision }}
                        </div>

                        <button :disabled="form.processing" class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black text-xs uppercase shadow-xl hover:bg-indigo-600 transition transform active:scale-95">
                            {{ form.processing ? 'Sincronizando...' : 'Confirmar Bloque Horario' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, Head, router } from '@inertiajs/vue3';
import {
    Calendar, Clock, MapPin, Save,
    ArrowLeft, AlertCircle, CheckCircle, Trash2,
    Plus, UserCircle, Sun, Moon // <--- AGREGA "Sun" y "Moon" AQUÍ
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    sections: any[], // Ahora recibimos el array de cursos
    plan: any,
    cycle: string,
    shiftId: number,
    sectionName: string,
    academicPeriod: any,
    timeSlots: any[],
    classrooms: any[],
    currentSchedules: any[],
    days: any[]
}>();

// Estado para el modal de asignación
const isModalOpen = ref(false);
const selectedSlot = ref<any>(null);
const selectedDay = ref<number | null>(null);

const selectedSectionId = ref<number | null>(null);

const getAssignment = (dayId: number, slotId: number) => {
    return props.currentSchedules.find(s =>
        s.day_of_week == dayId && s.time_slot_id == slotId
    );
};

const form = useForm({
    course_section_id: '',
    day_of_week: '',
    time_slot_id: '',
    classroom_id: '',
    collision: null as string | null, // <--- AGREGA ESTA LÍNEA para que TypeScript se calle
});

// Función para abrir el modal al hacer clic en una celda vacía
const openAssignModal = (dayId: number, slotId: number) => {
    // Validación: si no ha marcado un curso a la izquierda, no dejamos asignar
    if (!selectedSectionId.value) {
        alert('Por favor, selecciona primero un curso del panel izquierdo.');
        return;
    }

    form.clearErrors();

    selectedDay.value = dayId;
    selectedSlot.value = props.timeSlots.find(s => s.id === slotId);

    // Llenamos el form con el curso seleccionado
    form.course_section_id = selectedSectionId.value.toString();
    form.day_of_week = dayId.toString();
    form.time_slot_id = slotId.toString();
    isModalOpen.value = true;
};

const submit = () => {
    // 1. Obtenemos el ID del curso que seleccionaste a la izquierda
    const sectionId = selectedSectionId.value;

    if (!sectionId) {
        alert("Error: No se ha seleccionado un curso.");
        return;
    }

    // 2. Le pasamos el ID a la ruta para que Ziggy no se queje
    form.post(route('admin.course_sections.schedule.store', sectionId), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset('classroom_id');
            form.clearErrors();
        },
        // Mantenemos el scroll para que no salte la página al guardar
        preserveScroll: true
    });
};

// Función para buscar si una celda ya tiene clase asignada
const getAssignedCount = (sectionId: number) => {
    return props.currentSchedules.filter(s => s.course_section_id === sectionId).length;
};

// Lógica de "Presupuesto de Horas" (Calcula cuánto falta asignar)
const selectedSection = computed(() =>
    props.sections.find(s => s.id === selectedSectionId.value)
);

// USA EL SIGNO "?" AQUÍ TAMBIÉN
const totalHoursNeeded = computed(() =>
    selectedSection.value?.course?.hours_total || 0
);

const assignedHours = computed(() =>
    selectedSectionId.value
        ? props.currentSchedules.filter(s => s.course_section_id === selectedSectionId.value).length
        : 0
);

const deleteAssignment = (id: number) => {
    if (confirm('¿Deseas quitar este curso de este horario?')) {
        router.delete(route('admin.course_sections.schedule.destroy', id), {
            preserveScroll: true
        });
    }
};

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

import { onMounted } from 'vue';

onMounted(() => {
    if (props.sections.length > 0) {
        selectedSectionId.value = props.sections[0].id;
    }
});

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
                        {{ plan?.study_program?.name || 'Cargando...' }}
                    </h1>

                    <!-- En el Subtítulo -->
                    <div class="flex items-center space-x-2 mt-2">
                        <!-- Badge de Ciclo -->
                        <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-[10px] font-black italic uppercase">
                            Ciclo {{ cycle }}
                        </span>

                        <!-- Badge de Sección -->
                        <span class="bg-gray-900 text-white px-3 py-1 rounded-full text-[10px] font-black italic uppercase">
                            Sección {{ sectionName }}
                        </span>

                        <!-- NUEVO: Badge de Turno Inteligente -->
                        <span :class="shiftId === 1 ? 'bg-yellow-400 text-yellow-900' : 'bg-indigo-900 text-white'"
                            class="px-3 py-1 rounded-full text-[10px] font-black italic uppercase flex items-center gap-1 shadow-sm">
                            <Sun v-if="shiftId === 1" class="w-3 h-3" />
                            <Moon v-else class="w-3 h-3" />
                            {{ shiftId === 1 ? 'Turno Mañana' : 'Turno Tarde' }}
                        </span>

                        <!-- Nombre del Periodo -->
                        <span class="text-gray-400 font-bold text-[10px] uppercase tracking-widest pl-2">
                            {{ academicPeriod?.name }}
                        </span>
                    </div>
                </div>

                <!-- Widget de Progreso de Horas -->
                <div class="bg-white px-6 py-4 rounded-3xl shadow-xl border border-indigo-100 flex items-center space-x-6">
                    <div class="text-center">
                        <span class="block text-[8px] font-black text-gray-400 uppercase">Horas Requeridas</span>
                        <!-- Usamos totalHoursNeeded que definimos antes en el script -->
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

            <div class="flex flex-col lg:flex-row gap-6">

                <!-- PANEL IZQUIERDO: LISTA DE CURSOS (Agrégalo antes de la tabla) -->
                <div class="w-full lg:w-80 space-y-3">
                    <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Cursos de la Sección</h2>

                    <button v-for="sec in sections" :key="sec.id"
                        @click="selectedSectionId = sec.id"
                        :class="[
                            'w-full p-4 rounded-2xl border-2 text-left transition-all',
                            selectedSectionId === sec.id ? 'border-indigo-600 bg-white shadow-lg scale-105' : 'border-transparent bg-white/50 hover:bg-white'
                        ]"
                    >
                        <p class="text-[9px] font-black text-gray-400 uppercase">{{ sec.course.code }}</p>
                        <h4 class="text-xs font-black text-gray-800 uppercase leading-tight">{{ sec?.course?.name || 'Curso sin nombre' }}</h4>
                        <p class="text-[9px] font-bold mb-2 flex items-center" :class="sec.teacher ? 'text-indigo-600' : 'text-rose-500'">
                            <UserCircle class="w-3 h-3 mr-1 opacity-50" />
                            {{ sec.teacher?.full_name || 'Sin docente asignado' }}
                        </p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-[9px] font-bold text-indigo-600">{{ getAssignedCount(sec.id) }} / {{ sec.course.hours_total }}h</span>
                        </div>
                    </button>
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

                                        <!-- REEMPLAZA EL DIV DEL ESTADO A POR ESTE -->
                                        <div v-if="getAssignment(day.id, slot.id)"
                                            :class="getCourseColor(getAssignment(day.id, slot.id).course_id)"
                                            class="w-full h-full rounded-2xl p-3 flex flex-col justify-between border shadow-sm animate-in zoom-in-95 duration-300 relative group">

                                            <div class="text-[9px] font-black uppercase leading-tight text-gray-800">
                                                {{ getAssignment(day.id, slot.id)?.course?.name || 'Error: Sin curso' }}
                                            </div>

                                            <p class="text-[8px] font-bold mt-1 text-gray-500 truncate">
                                                {{ getAssignment(day.id, slot.id).section?.teacher?.full_name || 'Sin Docente' }}
                                            </p>

                                            <div class="flex items-center text-[10px] font-bold mt-auto uppercase text-gray-400">
                                                <MapPin class="w-3 h-3 mr-1 opacity-50" />
                                                {{ getAssignment(day.id, slot.id).classroom?.name || 'Aula pendiente' }}
                                            </div>

                                            <!-- El botón de borrar (el tacho) mantenlo igual -->
                                            <button @click="deleteAssignment(getAssignment(day.id, slot.id).id)"
                                                    class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-rose-500 text-white p-1 rounded-lg shadow-md hover:bg-rose-600">
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
            </div>



            <!-- MODAL DE ASIGNACIÓN RÁPIDA -->
            <div v-if="isModalOpen" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in-95">
                    <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
                        <h2 class="font-black text-gray-800 uppercase tracking-tighter">Asignar Ambiente</h2>
                        <button @click="() => { isModalOpen = false; form.clearErrors(); }" class="text-gray-400 hover:text-red-500 text-2xl font-black">
                            ×
                        </button>
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

                        <div v-if="form.errors.collision"
                            class="p-4 bg-rose-50 border-2 border-rose-200 rounded-2xl flex items-start text-rose-700 animate-bounce">
                            <AlertCircle class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" />
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-widest leading-none mb-1">¡Conflicto Detectado!</span>
                                <span class="text-xs font-bold leading-tight">{{ form.errors.collision }}</span>
                            </div>
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

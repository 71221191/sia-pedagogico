<script setup>
import { ref } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import {
    Layers,
    ArrowLeft,
    CheckCircle2,
    AlertCircle,
    Zap,
    CheckSquare,
    Square
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    academicPeriods: Array,
    studyPlans: Array,
    availableCycles: Array,
});

const form = useForm({
    academic_period_id: '',
    study_plan_id: '',
    cycles: [], // Aquí se guardarán los ciclos marcados (ej: ['I', 'III'])
});

// Función para marcar/desmarcar ciclos rápidamente
const toggleCycle = (cycle) => {
    if (form.cycles.includes(cycle)) {
        form.cycles = form.cycles.filter(c => c !== cycle);
    } else {
        form.cycles.push(cycle);
    }
};

const submit = () => {
    form.post(route('admin.course_sections.bulk-store'), {
        onSuccess: () => {
            // No reseteamos para que vea el mensaje de éxito en la lista
        },
    });
};

// Helpers para seleccionar grupos
const selectAll = () => form.cycles = [...props.availableCycles];
const selectNone = () => form.cycles = [];
</script>

<template>
    <Head title="Generación Masiva de Secciones" />

    <AppLayout>
        <div class="p-4 md:p-8 max-w-4xl mx-auto bg-gray-50 min-h-screen">
            <!-- Header -->
            <div class="mb-8">
                <Link :href="route('admin.course_sections.index')" class="flex items-center text-sm text-gray-500 hover:text-blue-600 font-bold uppercase tracking-widest mb-4">
                    <ArrowLeft class="w-4 h-4 mr-1" /> Volver al listado
                </Link>
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter flex items-center">
                    <Zap class="mr-3 w-8 h-8 text-yellow-500" />
                    Asistente de Apertura de Semestre
                </h1>
                <p class="text-gray-500 mt-2 italic">Crea automáticamente las secciones "A" para todos los cursos de los ciclos seleccionados.</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- PASO 1: Contexto -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">1. Periodo Académico Destino</label>
                            <select v-model="form.academic_period_id" class="w-full border-gray-200 rounded-2xl focus:ring-blue-500 font-bold text-gray-700">
                                <option value="">Seleccione el periodo...</option>
                                <option v-for="p in academicPeriods" :key="p.id" :value="p.id">{{ p.name }}</option>
                            </select>
                            <div v-if="form.errors.academic_period_id" class="text-red-500 text-xs mt-1 font-bold">{{ form.errors.academic_period_id }}</div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">2. Programa de Estudios / Plan</label>
                            <select v-model="form.study_plan_id" class="w-full border-gray-200 rounded-2xl focus:ring-blue-500 font-bold text-gray-700">
                                <option value="">Seleccione la carrera...</option>
                                <option v-for="plan in studyPlans" :key="plan.id" :value="plan.id">
                                    {{ plan.study_program.name }} - {{ plan.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.study_plan_id" class="text-red-500 text-xs mt-1 font-bold">{{ form.errors.study_plan_id }}</div>
                        </div>
                    </div>
                </div>

                <!-- PASO 2: Selección de Ciclos -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">3. ¿Qué ciclos desea abrir?</label>
                        <div class="flex space-x-4">
                            <button type="button" @click="selectAll" class="text-[10px] font-bold text-blue-600 hover:underline">TODOS</button>
                            <button type="button" @click="selectNone" class="text-[10px] font-bold text-gray-400 hover:underline">NINGUNO</button>
                        </div>
                    </div>

                    <!-- Grid de Ciclos -->
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                        <div v-for="cycle in availableCycles" :key="cycle"
                             @click="toggleCycle(cycle)"
                             :class="form.cycles.includes(cycle) ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-gray-50 border-gray-100 text-gray-400 hover:border-blue-200'"
                             class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex flex-col items-center justify-center group">
                            <span class="text-xl font-black">{{ cycle }}</span>
                            <span class="text-[8px] font-bold uppercase opacity-60">Ciclo</span>
                            <div class="mt-2">
                                <CheckSquare v-if="form.cycles.includes(cycle)" class="w-4 h-4" />
                                <Square v-else class="w-4 h-4 opacity-20 group-hover:opacity-100" />
                            </div>
                        </div>
                    </div>
                    <div v-if="form.errors.cycles" class="text-red-500 text-xs mt-4 font-bold text-center">{{ form.errors.cycles }}</div>
                </div>

                <!-- Botón de Acción -->
                <div class="flex flex-col items-center">
                    <button :disabled="form.processing || form.cycles.length === 0"
                            class="group relative bg-gray-900 text-white px-12 py-4 rounded-2xl font-black uppercase text-sm tracking-widest shadow-2xl hover:bg-blue-600 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="flex items-center">
                            <Layers class="mr-2 w-5 h-5" />
                            {{ form.processing ? 'Procesando...' : 'Generar Secciones Automáticamente' }}
                        </span>
                    </button>
                    <p class="mt-4 text-[10px] text-gray-400 text-center max-w-xs italic">
                        * Se creará la sección "A" con 30 vacantes para cada curso de los ciclos seleccionados que no exista ya en el periodo.
                    </p>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

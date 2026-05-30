<script setup lang="ts">
import { computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import {
    CheckCircle2,
    BookOpen,
    AlertCircle,
    Lock,
    Calendar,
    User,
    ClipboardList,
    Sparkles
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

interface Section {
    id: number;
    cycle: string;
    course_code: string;
    course_name: string;
    credits: number;
    section_name: string;
    teacher_name: string;
    status: 'available' | 'locked' | 'passed' | 'no_vacancies' | 'enrolled';
    lock_reason: string | null;
    vacancy_limit: number;
    remaining_vacancies: number;
}

const props = defineProps<{
    requirements: {
        can_enroll: boolean;
        details: { ficha: boolean; pago: boolean; biblioteca: boolean };
    };
    currentPeriod: string;
    currentPeriodId: number;
    availableSections: Section[];
    studentStudyPlanId: number | null;
    student: string;
    shiftName: string; // Recibe "mañana" o "tarde"
}>();

// Formulario para las selecciones de matrícula
const form = useForm({
    sections: [] as number[], // IDs de las secciones que el alumno selecciona
    academic_period_id: props.currentPeriodId,
});

// Agrupar cursos por ciclo de forma cronológica
const sectionsByCycle = computed(() => {
    const grouped: Record<string, Section[]> = {};
    props.availableSections.forEach(section => {
        if (!grouped[section.cycle]) {
            grouped[section.cycle] = [];
        }
        grouped[section.cycle].push(section);
    });
    return Object.keys(grouped).sort((a, b) => parseInt(a) - parseInt(b)).reduce((obj, key) => {
        obj[key] = grouped[key];
        return obj;
    }, {} as Record<string, Section[]>);
});

// Lógica de cálculo de créditos en tiempo real (Para el panel derecho)
const selectedSectionsData = computed(() => {
    return props.availableSections.filter(section => form.sections.includes(section.id));
});

const totalSelectedCredits = computed(() => {
    return selectedSectionsData.value.reduce((sum, section) => sum + parseFloat(section.credits as any), 0);
});

// Límite de créditos sugerido para un ciclo normal (ej: 22 créditos)
const maxCreditsLimit = 22;

const creditsPercentage = computed(() => {
    return Math.min((totalSelectedCredits.value / maxCreditsLimit) * 100, 100);
});

const submitEnrollment = () => {
    form.post(route('enrollment.store'), {
        onSuccess: () => {
            // El backend nos redirigirá de forma automática a la vista de constancia
        },
        onError: (errors) => {
            console.error('Errores de matrícula:', errors);
        }
    });
};

const toRoman = (num: any) => {
    const map: any = { 1:'I', 2:'II', 3:'III', 4:'IV', 5:'V', 6:'VI', 7:'VII', 8:'VIII', 9:'IX', 10:'X' };
    return map[num] || num;
};
</script>

<template>
    <Head title="Matrícula Online" />
    <AppLayout>
        <div class="p-4 md:p-8 max-w-7xl mx-auto bg-gray-50 min-h-screen">

            <!-- ENCABEZADO -->
            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter flex items-center">
                        <ClipboardList class="mr-3 w-8 h-8 text-indigo-600" />
                        Proceso de Matrícula Online
                    </h1>
                    <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mt-1">
                        Hola {{ student }}, selecciona tus asignaturas para el período: <span class="text-indigo-600 font-black">{{ currentPeriod }}</span>
                    </p>
                </div>

                <!-- Turno Asignado Dinámico -->
                <span class="px-5 py-2.5 bg-indigo-900 text-white rounded-full text-xs font-black uppercase tracking-widest shadow-lg flex items-center gap-2">
                    <Sparkles class="w-4 h-4 text-amber-400" />
                    Turno Asignado: {{ shiftName }}
                </span>
            </div>

            <!-- BANNER DE REQUISITOS PENDIENTES -->
            <div v-if="!requirements.can_enroll" class="mb-8 bg-white border border-red-200 p-6 shadow-xl rounded-[2rem] border-l-8 border-l-red-500">
                <h3 class="text-red-900 font-black mb-4 flex items-center text-sm uppercase tracking-widest">
                    <AlertCircle class="mr-2 w-5 h-5 text-red-500 animate-pulse" /> Requisitos de Matrícula Pendientes
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Ficha Socioeconómica -->
                    <div class="flex items-center p-4 rounded-2xl border transition-all"
                        :class="requirements.details.ficha ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50/50 border-red-100 text-red-700'">
                        <span class="mr-3 text-lg">{{ requirements.details.ficha ? '✅' : '❌' }}</span>
                        <div>
                            <span class="text-xs font-black uppercase block">Ficha Socioeconómica</span>
                            <span class="text-[10px] opacity-70">{{ requirements.details.ficha ? 'Validada y Aprobada' : 'Pendiente o Sin Validar' }}</span>
                        </div>
                    </div>

                    <!-- Pago de Matrícula -->
                    <div class="flex items-center p-4 rounded-2xl border transition-all"
                        :class="requirements.details.pago ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50/50 border-red-100 text-red-700'">
                        <span class="mr-3 text-lg">{{ requirements.details.pago ? '✅' : '❌' }}</span>
                        <div>
                            <span class="text-xs font-black uppercase block">Derecho de Matrícula</span>
                            <span class="text-[10px] opacity-70">{{ requirements.details.pago ? 'Pago Validado / Beca' : 'Sin Registro de Pago Aprobado' }}</span>
                        </div>
                    </div>

                    <!-- Biblioteca -->
                    <div class="flex items-center p-4 rounded-2xl border transition-all"
                        :class="requirements.details.biblioteca ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50/50 border-red-100 text-red-700'">
                        <span class="mr-3 text-lg">{{ requirements.details.biblioteca ? '✅' : '❌' }}</span>
                        <div>
                            <span class="text-xs font-black uppercase block">Deuda de Libros</span>
                            <span class="text-[10px] opacity-70">{{ requirements.details.biblioteca ? 'Sin deudas pendientes' : 'Registra deudas en biblioteca' }}</span>
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-[9px] text-gray-400 font-bold uppercase tracking-wider italic">
                    * Debes subsanar todos tus requisitos pendientes antes de que el sistema te permita enviar tu matrícula.
                </p>
            </div>

            <!-- BLOQUE DE ERRORES DEL SERVIDOR (VACANTES / PRERREQUISITOS) -->
            <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 border border-red-300 text-red-800 px-6 py-4 rounded-2xl mb-8 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <AlertCircle class="w-5 h-5 shrink-0" />
                    <h3 class="font-bold text-sm uppercase">Por favor, corrige los siguientes problemas:</h3>
                </div>
                <ul class="list-disc pl-5 space-y-1 text-xs">
                    <li v-for="(error, field) in form.errors" :key="field">
                        <template v-if="Array.isArray(error)">
                            <span v-for="(subError, subIndex) in error" :key="subIndex">{{ subError }}</span>
                        </template>
                        <template v-else>
                            {{ error }}
                        </template>
                    </li>
                </ul>
            </div>

            <!-- CUERPO EN PANTALLA DIVIDIDA (SPLIT LAYOUT) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- COLUMNA IZQUIERDA: CURSOS DISPONIBLES POR CICLO -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Estado vacío si no hay asignaturas -->
                    <div v-if="Object.keys(sectionsByCycle).length === 0" class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed">
                        <BookOpen class="w-16 h-16 mx-auto text-gray-200 mb-4" />
                        <h2 class="text-xl font-bold text-gray-400 uppercase">Sin Cursos Disponibles</h2>
                        <p class="text-gray-400 italic">No hay asignaturas programadas para tu plan de estudios en este turno.</p>
                    </div>

                    <!-- Bucles de Cursos Agrupados por Ciclo -->
                    <div v-for="(sections, cycle) in sectionsByCycle" :key="cycle" class="space-y-4">
                        <h3 class="text-lg font-black text-indigo-700 uppercase tracking-tighter pl-2">
                            Ciclo {{ toRoman(cycle) }}
                        </h3>

                        <div class="space-y-4">
                            <div v-for="section in sections" :key="section.id"
                                :class="{
                                    'bg-green-50/50 border-l-green-500': section.status === 'passed',
                                    'bg-gray-100 opacity-60 border-l-gray-300': section.status === 'locked',
                                    'bg-orange-50/30 border-l-orange-400': section.status === 'no_vacancies',
                                    'bg-indigo-50 border-l-indigo-500': section.status === 'enrolled',
                                    'bg-white border-l-gray-200 hover:shadow-md': section.status === 'available'
                                }"
                                class="rounded-3xl border border-gray-100 p-5 flex flex-col md:flex-row justify-between items-center gap-4 transition-all border-l-8">

                                <!-- Información del curso -->
                                <div class="flex items-start gap-4 w-full md:w-auto">
                                    <!-- Selector Checkbox -->
                                    <div class="pt-1">
                                        <input v-if="section.status === 'available'"
                                            type="checkbox"
                                            :value="section.id"
                                            v-model="form.sections"
                                            class="form-checkbox h-6 w-6 text-indigo-600 rounded-xl cursor-pointer border-2 border-gray-200 focus:ring-0">

                                        <span v-else-if="section.status === 'passed'" class="text-green-600 text-xl font-bold">✅</span>
                                        <span v-else-if="section.status === 'enrolled'" class="text-blue-600 text-xl font-bold">📝</span>
                                        <span v-else-if="section.status === 'locked'" class="text-gray-400 text-lg">🔒</span>
                                        <span v-else-if="section.status === 'no_vacancies'" class="text-orange-500 text-lg">🚫</span>
                                    </div>

                                    <div>
                                        <h4 class="font-black text-gray-900 text-sm uppercase leading-tight">
                                            {{ section.course_name }}
                                        </h4>
                                        <div class="flex flex-wrap items-center gap-2 mt-1 text-[10px] font-bold uppercase text-gray-400 tracking-wider">
                                            <span class="font-mono">{{ section.course_code }}</span>
                                            <span>•</span>
                                            <span>Sección {{ section.section_name }}</span>
                                            <span>•</span>
                                            <span>{{ section.credits }} Créditos</span>
                                        </div>

                                        <!-- Mensajes condicionales en el curso -->
                                        <p v-if="section.status === 'locked'" class="text-[9px] text-red-500 font-bold uppercase mt-1">
                                            ⚠️ {{ section.lock_reason }}
                                        </p>
                                        <p v-if="section.status === 'passed'" class="text-[9px] text-green-600 font-bold uppercase mt-1">
                                            Curso ya aprobado en tu historial
                                        </p>
                                        <p v-if="section.status === 'enrolled'" class="text-[9px] text-blue-600 font-bold uppercase mt-1">
                                            Ya estás matriculado en esta asignatura
                                        </p>
                                    </div>
                                </div>

                                <!-- Detalles de Docente y Vacantes -->
                                <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 pt-3 md:pt-0">
                                    <div class="text-left md:text-right">
                                        <span class="block text-[8px] font-black text-gray-400 uppercase">Docente</span>
                                        <span class="text-xs font-bold text-gray-700 uppercase flex items-center gap-1">
                                            <User class="w-3.5 h-3.5 text-gray-400" />
                                            {{ section.teacher_name }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-[8px] font-black text-gray-400 uppercase">Vacantes libres</span>
                                        <span class="text-xs font-black uppercase" :class="section.remaining_vacancies <= 0 ? 'text-red-500' : 'text-green-600'">
                                            {{ section.remaining_vacancies }} / {{ section.vacancy_limit }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: SIDEBAR DE RESUMEN (FIJO) -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-xl sticky top-8 space-y-6">
                        <div class="border-b pb-4">
                            <h3 class="font-black text-gray-900 text-sm uppercase tracking-widest flex items-center gap-2">
                                <BookOpen class="w-5 h-5 text-indigo-600" />
                                Resumen de Matrícula
                            </h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Control de carga de créditos</p>
                        </div>

                        <!-- Cursos seleccionados interactivamente -->
                        <div class="space-y-3">
                            <span class="block text-[9px] font-black text-gray-400 uppercase">Cursos Seleccionados ({{ selectedSectionsData.length }})</span>

                            <div v-if="selectedSectionsData.length > 0" class="max-h-60 overflow-y-auto space-y-2 pr-1">
                                <div v-for="sel in selectedSectionsData" :key="sel.id"
                                    class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex justify-between items-center text-xs">
                                    <span class="font-bold text-gray-700 truncate max-w-[150px] uppercase">{{ sel.course_name }}</span>
                                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-100 font-black rounded-lg text-[9px]">{{ sel.credits }} CR</span>
                                </div>
                            </div>
                            <div v-else class="text-center py-6 bg-gray-50 rounded-2xl border-2 border-dashed">
                                <p class="text-[10px] text-gray-400 font-bold uppercase italic">Ningún curso seleccionado aún</p>
                            </div>
                        </div>

                        <!-- Barra de progreso de créditos -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-black">
                                <span class="text-gray-500 uppercase text-[9px]">Créditos Acumulados</span>
                                <span class="text-indigo-600 font-mono">{{ totalSelectedCredits }} / {{ maxCreditsLimit }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div :style="{ width: creditsPercentage + '%' }"
                                    :class="totalSelectedCredits > maxCreditsLimit ? 'bg-red-500' : 'bg-indigo-600'"
                                    class="h-full rounded-full transition-all duration-500"></div>
                            </div>
                            <span v-if="totalSelectedCredits > maxCreditsLimit" class="block text-[9px] text-red-500 font-black uppercase animate-pulse">
                                ⚠️ Has excedido el límite máximo de créditos permitido.
                            </span>
                        </div>

                        <!-- Botón de Matricularse definitivo -->
                        <button @click="submitEnrollment"
                            :disabled="form.processing || form.sections.length === 0 || !requirements.can_enroll || totalSelectedCredits > maxCreditsLimit"
                            :class="{
                                'bg-indigo-900 hover:bg-indigo-800 text-white shadow-lg': !form.processing && form.sections.length > 0 && requirements.can_enroll && totalSelectedCredits <= maxCreditsLimit,
                                'bg-gray-200 text-gray-400 cursor-not-allowed': form.processing || form.sections.length === 0 || !requirements.can_enroll || totalSelectedCredits > maxCreditsLimit
                            }"
                            class="w-full py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl flex items-center justify-center gap-2 transform active:scale-95">
                            <CheckCircle2 v-if="requirements.can_enroll" class="w-5 h-5" />
                            <Lock v-else class="w-5 h-5" />
                            <span>{{ form.processing ? 'Sincronizando...' : (requirements.can_enroll ? 'Matricularme Ahora' : 'Requisitos Pendientes') }}</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </AppLayout>
</template>

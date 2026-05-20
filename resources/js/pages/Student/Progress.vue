<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    BookOpen,
    AlertTriangle,
    CheckCircle,
    TrendingUp,
    FileText,
    Download,
    ChevronDown,
    ChevronUp,
    Calendar, // <--- AGREGA ESTE
    Lock      // <--- AGREGA ESTE
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

interface CourseProgress {
    section_id: number;
    course_code: string;
    course_name: string;
    current_grade: number;
    competencies: any[]; // <--- AGREGA ESTA LÍNEA
    attendance: {
        percentage: number;
        is_danger: boolean;
    };
}

interface HistoricalCourse {
    code: string;
    name: string;
    credits: number;
    grade: number;
    status: 'approved' | 'disapproved';
}

interface AcademicPeriod {
    period_name: string;
    cycle: string;
    courses: HistoricalCourse[];
}

const props = defineProps({
    currentProgress: Array as () => CourseProgress[],
    academicHistory: Array as () => AcademicPeriod[],
    ppa: {
        type: Number,
        default: 0
    },
    totalCredits: Number,
    studentName: String,
    periodName: String
});

// Estado para cambiar entre "Mi Semestre" e "Historial"
const activeTab = ref('current'); // 'current' o 'history'

// Estado para los acordeones del historial
const expandedPeriods = ref([0]); // El primer periodo abierto por defecto

const togglePeriod = (index: number) => {
    if (expandedPeriods.value.includes(index)) {
        expandedPeriods.value = expandedPeriods.value.filter(i => i !== index);
    } else {
        expandedPeriods.value.push(index);
    }
};

const getMiniGradeColor = (value: number) => {
    if (value === 0) return 'bg-orange-100 text-orange-700 border-orange-200'; // NP
    if (value < 3) return 'bg-rose-100 text-rose-700 border-rose-200'; // Desaprobado
    return 'bg-emerald-100 text-emerald-700 border-emerald-200'; // Aprobado
};

</script>

<template>
    <Head title="Mi Progreso" />
    <AppLayout>
        <div class="p-4 md:p-8 max-w-6xl mx-auto bg-gray-50 min-h-screen">

            <!-- 1. RESUMEN DE RENDIMIENTO (ESTILO UNC PRO) -->
            <div class="bg-indigo-900 rounded-[2.5rem] p-8 text-white shadow-2xl mb-8 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>

                <div class="flex gap-8 relative z-10">
                    <div class="text-center">
                        <span class="block text-[10px] font-black text-indigo-400 uppercase mb-1">Promedio Ponderado</span>
                        <span class="text-4xl font-black font-mono">{{ ppa.toFixed(4) }}</span>
                    </div>
                    <div class="w-[1px] bg-white/20"></div>
                    <div class="text-center">
                        <span class="block text-[10px] font-black text-indigo-400 uppercase mb-1">Créditos Totales</span>
                        <span class="text-4xl font-black font-mono">{{ totalCredits }}</span>
                    </div>
                </div>

                <div class="relative z-10 text-center md:text-left">
                    <h1 class="text-3xl font-black uppercase italic tracking-tighter">Expediente Académico</h1>
                    <p class="text-indigo-300 text-xs font-bold uppercase tracking-[0.3em]">{{ studentName }}</p>

                    <!-- BOTÓN DE DESCARGA (AÑADIR ESTO) -->
                    <a :href="route('student.progress.pdf')" target="_blank"
                    class="mt-4 inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-[10px] font-black uppercase transition-all shadow-lg">
                        <Download class="w-4 h-4 mr-2" /> Descargar Récord PDF
                    </a>
                </div>
            </div>

            <!-- 2. SELECTOR DE PESTAÑAS -->
            <div class="flex p-1 bg-gray-200 rounded-2xl mb-8 w-fit mx-auto md:mx-0">
                <button @click="activeTab = 'current'"
                        :class="activeTab === 'current' ? 'bg-white text-indigo-600 shadow-md' : 'text-gray-500 hover:text-gray-700'"
                        class="px-6 py-2 rounded-xl text-xs font-black uppercase transition-all flex items-center">
                    <TrendingUp class="w-4 h-4 mr-2" /> Mi Semestre
                </button>
                <button @click="activeTab = 'history'"
                        :class="activeTab === 'history' ? 'bg-white text-indigo-600 shadow-md' : 'text-gray-500 hover:text-gray-700'"
                        class="px-6 py-2 rounded-xl text-xs font-black uppercase transition-all flex items-center">
                    <FileText class="w-4 h-4 mr-2" /> Historial / Kárdex
                </button>
            </div>

            <!-- 3. CONTENIDO: MI SEMESTRE -->
            <div v-if="activeTab === 'current'" class="animate-in fade-in slide-in-from-bottom-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-for="item in currentProgress" :key="item.course_code"
                        class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 hover:shadow-xl transition-all border-l-8"
                        :class="item.attendance.is_danger ? 'border-l-red-500' : 'border-l-indigo-500'">

                        <h2 class="text-lg font-bold text-gray-800 uppercase leading-tight mb-4">{{ item.course_name }}</h2>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-2xl text-center">
                                <span class="text-[9px] font-black text-gray-400 uppercase block mb-1">Nota Actual</span>
                                <span class="text-2xl font-black" :class="item.current_grade >= 11 ? 'text-green-600' : 'text-red-500'">
                                    {{ item.current_grade ? String(item.current_grade).padStart(2, '0') : '--' }}
                                </span>
                            </div>
                            <div class="p-4 rounded-2xl text-center" :class="item.attendance.is_danger ? 'bg-red-50' : 'bg-blue-50'">
                                <span class="text-[9px] font-black uppercase block mb-1" :class="item.attendance.is_danger ? 'text-red-400' : 'text-blue-400'">Faltas</span>
                                <span class="text-2xl font-black" :class="item.attendance.is_danger ? 'text-red-600' : 'text-blue-600'">
                                    {{ item.attendance.percentage }}%
                                </span>
                            </div>
                        </div>

                        <!-- Alerta DPI -->
                        <div v-if="item.attendance.is_danger" class="mt-4 p-3 bg-red-600 text-white rounded-xl flex items-center justify-center animate-pulse">
                            <AlertTriangle class="w-4 h-4 mr-2" />
                            <span class="text-[10px] font-black uppercase">¡RIESGO CRÍTICO DE DPI!</span>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-50">
                            <Link :href="route('student.courses.show', item.section_id)"
                                classa="w-full flex items-center justify-center gap-2 py-3 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all transform active:scale-95 shadow-sm">
                                <BookOpen class="w-4 h-4" />
                                Entrar al Aula Virtual
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. CONTENIDO: HISTORIAL COMPLETO (KÁRDEX) -->
            <div v-if="activeTab === 'history'" class="space-y-4 animate-in fade-in slide-in-from-bottom-4">
                <div v-for="(period, index) in academicHistory" :key="period.period_name" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <button @click="togglePeriod(index)" class="w-full p-6 flex justify-between items-center hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-4">
                            <div class="bg-indigo-100 text-indigo-700 p-2 rounded-lg font-black text-sm">
                                {{ period.period_name }}
                            </div>
                            <span class="font-bold text-gray-700 uppercase text-sm">Ciclo {{ period.cycle }}</span>
                        </div>
                        <ChevronDown v-if="!expandedPeriods.includes(index)" class="text-gray-400" />
                        <ChevronUp v-else class="text-gray-400" />
                    </button>

                    <div v-show="expandedPeriods.includes(index)" class="px-6 pb-6 overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b">
                                    <th class="py-3">Cód.</th>
                                    <th class="py-3">Asignatura</th>
                                    <th class="py-3 text-center">Créditos</th>
                                    <th class="py-3 text-center">Nota</th>
                                    <th class="py-3 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="course in period.courses" :key="course.code" class="text-xs">
                                    <td class="py-3 font-mono text-gray-400">{{ course.code }}</td>
                                    <td class="py-3 font-bold text-gray-700 uppercase">{{ course.name }}</td>
                                    <td class="py-3 text-center font-bold text-gray-500">{{ course.credits }}</td>
                                    <td class="py-3 text-center font-black" :class="course.grade < 11 ? 'text-red-500' : 'text-green-600'">
                                        {{ course.grade ? String(course.grade).padStart(2, '0') : '--' }}
                                    </td>
                                    <td class="py-3 text-center">
                                        <span :class="course.status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                              class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase">
                                            {{ course.status === 'approved' ? 'Aprobado' : 'Desaprobado' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

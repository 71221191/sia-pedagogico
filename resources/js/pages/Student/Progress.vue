<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import {
    FileText,
    Download,
    ChevronDown,
    ChevronUp
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

interface HistoricalCourse {
    code: string;
    name: string;
    credits: number;
    grade: number;
    status: 'approved' | 'failed' | 'enrolled';
}

interface AcademicPeriod {
    period_name: string;
    cycle: string;
    courses: HistoricalCourse[];
}

const props = defineProps({
    academicHistory: Array as () => AcademicPeriod[],
    ppa: {
        type: Number,
        default: 0
    },
    totalCredits: Number,
    studentName: String,
    periodName: String
});

// Estado para los acordeones del historial
const expandedPeriods = ref([0]); // El primer periodo abierto por defecto

const togglePeriod = (index: number) => {
    if (expandedPeriods.value.includes(index)) {
        expandedPeriods.value = expandedPeriods.value.filter(i => i !== index);
    } else {
        expandedPeriods.value.push(index);
    }
};
</script>

<template>
    <Head title="Récord de Notas" />
    <AppLayout>
        <div class="p-4 md:p-8 max-w-6xl mx-auto bg-gray-50 min-h-screen">

            <!-- 1. RESUMEN DE RENDIMIENTO -->
            <div class="bg-indigo-900 rounded-[2.5rem] p-8 text-white shadow-2xl mb-8 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>

                <div class="flex gap-8 relative z-10">
                    <div class="text-center">
                        <span class="block text-[10px] font-black text-indigo-400 uppercase mb-1">Promedio Ponderado</span>
                        <span class="text-4xl font-black font-mono">{{ ppa.toFixed(4) }}</span>
                    </div>
                    <div class="w-[1px] bg-white/20"></div>
                    <div class="text-center">
                        <span class="block text-[10px] font-black text-indigo-400 uppercase mb-1">Créditos Aprobados</span>
                        <span class="text-4xl font-black font-mono">{{ totalCredits }}</span>
                    </div>
                </div>

                <div class="relative z-10 text-center md:text-left">
                    <h1 class="text-3xl font-black uppercase italic tracking-tighter">Expediente Académico</h1>
                    <p class="text-indigo-300 text-xs font-bold uppercase tracking-[0.3em]">{{ studentName }}</p>

                    <!-- BOTÓN DE DESCARGA -->
                    <a :href="route('student.progress.pdf')" target="_blank"
                    class="mt-4 inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-[10px] font-black uppercase transition-all shadow-lg">
                        <Download class="w-4 h-4 mr-2" /> Descargar Récord PDF
                    </a>
                </div>
            </div>

            <!-- Título de Sección -->
            <div class="mb-6 flex items-center space-x-2">
                <FileText class="w-5 h-5 text-indigo-600" />
                <h2 class="text-xs font-black uppercase tracking-widest text-gray-500">Historial de Notas / Kárdex</h2>
            </div>

            <!-- 2. CONTENIDO: HISTORIAL COMPLETO (KÁRDEX) -->
            <div class="space-y-4 animate-in fade-in slide-in-from-bottom-4">
                <div v-for="(period, index) in academicHistory" :key="period.period_name" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <button @click="togglePeriod(index)" class="w-full p-6 flex justify-between items-center hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-4">
                            <div class="bg-indigo-100 text-indigo-700 p-2 rounded-lg font-black text-sm font-mono">
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
                                        <span :class="course.status === 'approved' ? 'bg-green-100 text-green-700' : (course.status === 'enrolled' ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700')"
                                              class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase">
                                            {{ course.status === 'approved' ? 'Aprobado' : (course.status === 'enrolled' ? 'En Curso' : 'Desaprobado') }}
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

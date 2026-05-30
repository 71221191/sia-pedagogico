<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Download, Calendar, FileText, CheckCircle2, FileSpreadsheet } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

interface EnrolledCourse {
    code: string;
    name: string;
    credits: number;
    section: string;
    teacher: string;
    enrolled_at: string;
    observation: string;
    plan: string;
}

interface EnrollmentInfo {
    id: number;
    admission_year: string;
    plan_name: string;
    cycle: string;
    courses_count: number;
    date: string;
    total_credits: number;
    shift_name: string; // Recibe "MAÑANA" o "TARDE"
}

const props = defineProps<{
    enrollment: EnrollmentInfo;
    courses: EnrolledCourse[];
    periodName: string;
    studentName: string;
}>();

const toRoman = (num: any) => {
    const map: any = { 1:'I', 2:'II', 3:'III', 4:'IV', 5:'V', 6:'VI', 7:'VII', 8:'VIII', 9:'IX', 10:'X' };
    return map[num] || num;
};
</script>

<template>
    <Head title="Ficha de Matrícula" />
    <AppLayout>
        <div class="p-4 md:p-8 max-w-6xl mx-auto bg-gray-50 min-h-screen">

            <!-- CABECERA PRINCIPAL CON BOTONES DE DESCARGA DIRECTA (FICHA Y HORARIOS) -->
            <div class="mb-8 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 border-b pb-6">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 uppercase italic tracking-tighter flex items-center">
                        <CheckCircle2 class="mr-3 w-8 h-8 text-emerald-600 animate-bounce" />
                        Matrícula Oficial - Semestre {{ periodName }}
                    </h1>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mt-1">
                        Estudiante: <span class="text-gray-700 font-black">{{ studentName }}</span>
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- 1. Descargar Ficha PDF -->
                    <a :href="route('student.enrollment.pdf', enrollment.id)" target="_blank"
                        class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md flex items-center gap-1.5 transition-all">
                        <Download class="w-4 h-4" />
                        Descargar Ficha (PDF)
                    </a>

                    <!-- 4. Ver Horario interactivo en pantalla -->
                    <Link :href="route('student.schedule')"
                        class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm flex items-center gap-1.5 transition-all">
                        <Calendar class="w-4 h-4 text-indigo-500" />
                        Ver Horario
                    </Link>
                </div>
            </div>

            <!-- PROCESO CERRADO BANNER -->
            <div class="mb-8 p-6 bg-red-50 border-2 border-dashed border-red-200 rounded-[2rem] text-center flex flex-col items-center justify-center shadow-inner">
                <span class="bg-red-600 text-white text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest animate-pulse">
                    Proceso Cerrado / Oficializado
                </span>
                <p class="text-xs text-red-700 font-bold uppercase mt-3 max-w-md">
                    Tu proceso de matrícula para este semestre ha sido consolidado exitosamente. No se admiten más modificaciones o cambios de sección.
                </p>
            </div>

            <!-- DATOS RESUMEN DE FICHA DE MATRÍCULA -->
            <div class="bg-indigo-900 text-white rounded-[2.5rem] p-8 shadow-2xl mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 relative z-10 text-center md:text-left">
                    <div>
                        <span class="block text-[9px] font-black text-indigo-400 uppercase mb-1">Año de Ingreso</span>
                        <span class="text-xl font-bold font-mono">{{ enrollment.admission_year }}</span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-black text-indigo-400 uppercase mb-1">Currículo de Estudios</span>
                        <span class="text-xl font-bold font-mono">{{ enrollment.plan_name }}</span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-black text-indigo-400 uppercase mb-1">Turno Asignado</span>
                        <span class="text-xl font-bold font-mono text-amber-300">{{ enrollment.shift_name }}</span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-black text-indigo-400 uppercase mb-1">Ciclo Matriculado</span>
                        <span class="text-xl font-bold font-mono">Ciclo {{ toRoman(enrollment.cycle) }}</span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-black text-indigo-400 uppercase mb-1">Asignaturas</span>
                        <span class="text-xl font-bold font-mono">{{ enrollment.courses_count }} Cursos</span>
                    </div>
                </div>

                <div class="w-full bg-white/20 h-[1px] my-6 relative z-10"></div>

                <div class="flex flex-col md:flex-row justify-between items-center gap-4 relative z-10">
                    <div class="text-center md:text-left">
                        <span class="block text-[9px] font-black text-indigo-400 uppercase">Fecha Oficial de Registro</span>
                        <span class="text-sm font-bold uppercase">{{ enrollment.date }}</span>
                    </div>
                    <div class="text-center md:text-right">
                        <span class="block text-[9px] font-black text-indigo-400 uppercase mb-1">Total de Créditos Matriculados</span>
                        <span class="px-5 py-1 bg-white/10 backdrop-blur rounded-full text-lg font-black font-mono">
                            {{ enrollment.total_credits }} Créditos
                        </span>
                    </div>
                </div>
            </div>

            <!-- TABLA DE CURSOS INSCRITOS -->
            <div class="space-y-4">
                <div class="mb-2 flex items-center space-x-2">
                    <FileText class="w-5 h-5 text-indigo-600" />
                    <h2 class="text-xs font-black uppercase tracking-widest text-gray-500">Cursos Registrados Oficialmente</h2>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-900 text-white text-[10px] tracking-widest uppercase font-black">
                                <th class="p-4 border-r border-gray-800">Código</th>
                                <th class="p-4 border-r border-gray-800">Asignatura</th>
                                <th class="p-4 text-center border-r border-gray-800">Sección</th>
                                <th class="p-4 text-center border-r border-gray-800">Créditos</th>
                                <th class="p-4 text-center border-r border-gray-800">Inscripción</th>
                                <th class="p-4 text-center border-r border-gray-800">Docente</th>
                                <th class="p-4 text-center">Plan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="course in courses" :key="course.code" class="text-xs">
                                <td class="p-4 font-mono text-gray-400 font-bold border-r">{{ course.code }}</td>
                                <td class="p-4 font-bold text-gray-900 uppercase border-r">{{ course.name }}</td>
                                <td class="p-4 text-center border-r font-black text-gray-700">{{ course.section }}</td>
                                <td class="p-4 text-center border-r font-black text-gray-700">{{ course.credits }}</td>
                                <td class="p-4 text-center border-r font-black text-indigo-600">{{ course.observation }}</td>
                                <td class="p-4 text-center border-r font-black text-gray-500">{{ course.teacher }}</td>
                                <td class="p-4 text-center font-bold text-gray-500">{{ course.plan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

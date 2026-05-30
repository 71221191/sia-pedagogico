<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, BookOpen, Download, Eye, Award, Calendar, Bookmark } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    section: any,
    competencies: any[],
    finalScaleName: string,
    schedule: any[],
    status: string
}>();

// Estilo del badge de Escala Cualitativa Final
const getScaleStyle = (scale: string) => {
    const s = scale.toLowerCase();
    if (s.includes('destacado')) return 'bg-purple-50 text-purple-700 border-purple-200';
    if (s.includes('logrado')) return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    if (s.includes('proceso')) return 'bg-amber-50 text-amber-700 border-amber-200';
    if (s.includes('inicio')) return 'bg-rose-50 text-rose-700 border-rose-200';
    return 'bg-gray-100 text-gray-700 border-gray-200';
};

const toRoman = (num: any) => {
    const map: any = { 1:'I', 2:'II', 3:'III', 4:'IV', 5:'V', 6:'VI', 7:'VII', 8:'VIII', 9:'IX', 10:'X' };
    return map[num] || num;
};
</script>

<template>
    <Head :title="section.course.name" />
    <AppLayout>
        <div class="p-4 md:p-8 max-w-7xl mx-auto bg-gray-50 min-h-screen">

            <!-- Header -->
            <div class="mb-8">
                <Link :href="route('student.courses.index')" class="text-sm font-bold text-gray-500 uppercase flex items-center mb-2">
                    <ArrowLeft class="w-4 h-4 mr-1" /> Volver a mis cursos
                </Link>
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-tight">{{ section.course.name }}</h1>
                <p class="text-gray-500 font-mono text-sm uppercase">Ficha Integral de Curso | Ciclo {{ toRoman(section.course.cycle) }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- COLUMNA IZQUIERDA: INFORMACIÓN Y RECURSOS -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Ficha Informativa -->
                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm space-y-4">
                        <h2 class="font-black text-gray-800 text-xs uppercase tracking-widest flex items-center border-b pb-3">
                            <Bookmark class="mr-2 w-4 h-4 text-indigo-600" /> Información del Curso
                        </h2>
                        <div class="space-y-3 text-xs uppercase">
                            <div>
                                <span class="block text-[9px] font-black text-gray-400">Código de Curso</span>
                                <span class="font-bold text-gray-700">{{ section.course.code }}</span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-black text-gray-400">Programa de Estudios</span>
                                <span class="font-bold text-gray-700">{{ section.course.study_plan.study_program.name }}</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <span class="block text-[9px] font-black text-gray-400">Créditos</span>
                                    <span class="font-bold text-gray-700">{{ section.course.credits }}</span>
                                </div>
                                <div>
                                    <span class="block text-[9px] font-black text-gray-400">H. Teoría</span>
                                    <span class="font-bold text-gray-700">{{ section.course.hours_theory }}</span>
                                </div>
                                <div>
                                    <span class="block text-[9px] font-black text-gray-400">H. Práctica</span>
                                    <span class="font-bold text-gray-700">{{ section.course.hours_practice }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ficha Docente -->
                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm space-y-4">
                        <h2 class="font-black text-gray-800 text-xs uppercase tracking-widest flex items-center border-b pb-3">
                            <Award class="mr-2 w-4 h-4 text-emerald-600" /> Profesor Asignado
                        </h2>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center overflow-hidden">
                                <img v-if="section.teacher.official_photo_path" :src="'/storage/' + section.teacher.official_photo_path" class="object-cover w-full h-full" />
                                <span v-else class="font-black text-lg text-gray-400">P</span>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 text-xs uppercase leading-tight">{{ section.teacher.full_name }}</h3>
                                <p class="text-[9px] text-gray-400 uppercase font-mono mt-1">docente pedagógico</p>
                            </div>
                        </div>
                    </div>

                    <!-- Horario del Curso -->
                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm space-y-4">
                        <h2 class="font-black text-gray-800 text-xs uppercase tracking-widest flex items-center border-b pb-3">
                            <Calendar class="mr-2 w-4 h-4 text-blue-600" /> Mi Horario de Clases
                        </h2>
                        <div v-if="schedule.length > 0" class="space-y-3">
                            <div v-for="item in schedule" class="p-3 bg-gray-50 rounded-xl flex justify-between items-center text-xs">
                                <div>
                                    <span class="block font-black text-gray-700 uppercase">{{ item.day_name }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium">{{ item.start }} a {{ item.end }}</span>
                                </div>
                                <span class="px-2.5 py-1 bg-white border rounded-lg font-bold text-gray-600 uppercase text-[9px]">{{ item.classroom }}</span>
                            </div>
                        </div>
                        <p v-else class="text-[10px] text-gray-400 font-bold uppercase text-center italic py-2">Horario no asignado</p>
                    </div>

                    <!-- Sílabo -->
                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm space-y-4">
                        <h2 class="font-black text-gray-800 text-xs uppercase tracking-widest flex items-center border-b pb-3">
                            <BookOpen class="mr-2 w-4 h-4 text-rose-600" /> Sílabo de la Asignatura
                        </h2>
                        <div v-if="section.syllabus_path" class="flex items-center justify-between gap-3">
                            <a :href="'/storage/' + section.syllabus_path" target="_blank"
                                class="flex-1 flex items-center justify-center gap-2 py-3 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl font-black text-[10px] uppercase transition-all border border-rose-100 shadow-sm">
                                <Eye class="w-4 h-4" /> Visualizar Sílabo
                            </a>
                            <a :href="'/storage/' + section.syllabus_path" download
                                class="p-3 bg-gray-900 hover:bg-rose-600 text-white rounded-xl transition-all shadow-lg shadow-gray-100">
                                <Download class="w-4 h-4" />
                            </a>
                        </div>
                        <p v-else class="text-[10px] text-gray-400 font-bold uppercase text-center italic py-2">El docente aún no ha subido el sílabo</p>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: NOTAS POR COMPETENCIA (DCBN) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Tarjeta de Promedio Cualitativo Final -->
                    <div :class="getScaleStyle(finalScaleName)"
                        class="p-8 rounded-[2.5rem] shadow-xl border-2 flex flex-col md:flex-row items-center justify-between gap-6 transition-all duration-300">
                        <div>
                            <span class="block text-[10px] font-black uppercase opacity-60 tracking-widest mb-1">Escala Cualitativa Final</span>
                            <span class="text-4xl md:text-5xl font-black tracking-tighter uppercase italic leading-none">{{ finalScaleName }}</span>
                        </div>
                        <div class="text-center md:text-right">
                            <span class="block text-[9px] font-black uppercase opacity-50 tracking-widest mb-1">Estado de Matrícula</span>
                            <span class="px-4 py-1.5 bg-white/60 backdrop-blur border rounded-full text-[10px] font-black uppercase tracking-widest">
                                {{ status === 'approved' ? 'Aprobado' : (status === 'enrolled' ? 'En Curso' : 'Desaprobado') }}
                            </span>
                        </div>
                    </div>

                    <!-- Desglose de Competencias -->
                    <div class="space-y-4">
                        <h2 class="font-black text-gray-800 text-xs uppercase tracking-widest pl-2">Desglose de Calificaciones por Competencia</h2>

                        <div v-for="comp in competencies" :key="comp.code"
                            class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-6 flex flex-col md:flex-row items-center justify-between hover:shadow-md transition-all gap-4">

                            <div class="flex items-start space-x-4 w-full md:w-auto">
                                <div class="bg-indigo-50 text-indigo-700 p-3 rounded-2xl font-black text-xs font-mono">
                                    {{ comp.code }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-700 uppercase text-xs leading-normal max-w-lg">{{ comp.description }}</h3>
                                </div>
                            </div>

                            <span :class="getScaleStyle(comp.scale_name)"
                                class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase border-2 flex items-center gap-1 w-full md:w-auto justify-center md:justify-end">
                                {{ comp.scale_name }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

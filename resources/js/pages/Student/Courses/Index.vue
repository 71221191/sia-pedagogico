<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { BookOpen, User, Calendar, LayoutGrid } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    courses: any[],
    periodName: string
}>();

// Función de colores para las tarjetas
const getCourseColor = (index: number) => {
    const colors = [
        'bg-blue-50 text-blue-700 border-blue-100',
        'bg-emerald-50 text-emerald-700 border-emerald-100',
        'bg-violet-50 text-violet-700 border-violet-100',
        'bg-amber-50 text-amber-700 border-amber-100',
        'bg-rose-50 text-rose-700 border-rose-100',
        'bg-cyan-50 text-cyan-700 border-cyan-100'
    ];
    return colors[index % colors.length];
};

const toRoman = (num: any) => {
    const map: any = { 1:'I', 2:'II', 3:'III', 4:'IV', 5:'V', 6:'VI', 7:'VII', 8:'VIII', 9:'IX', 10:'X' };
    return map[num] || num;
};
</script>

<template>
    <Head title="Mis Cursos" />
    <AppLayout>
        <div class="p-8 max-w-7xl mx-auto bg-gray-50 min-h-screen">

            <!-- Header -->
            <div class="mb-10">
                <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter italic flex items-center">
                    <LayoutGrid class="mr-3 w-8 h-8 text-indigo-600" />
                    Mis Cursos Activos
                </h1>
                <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mt-2">Semestre actual: {{ periodName }}</p>
            </div>

            <!-- Grid de Tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="(course, index) in courses" :key="course.section_id"
                    class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl overflow-hidden hover:shadow-2xl transition-all group flex flex-col justify-between">

                    <!-- Cabecera con Color -->
                    <div :class="getCourseColor(index)" class="p-6 border-b">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-3 py-1 bg-white/50 rounded-full text-[10px] font-black uppercase tracking-widest">
                                Ciclo {{ toRoman(course.cycle) }}
                            </span>
                            <span class="px-3 py-1 bg-white/50 rounded-full text-[9px] font-black uppercase">
                                Sección {{ course.section_name }}
                            </span>
                        </div>
                        <h2 class="text-xl font-black uppercase leading-tight tracking-tighter">{{ course.course_name }}</h2>
                        <p class="text-[10px] font-bold opacity-70 mt-1 uppercase tracking-widest">{{ course.course_code }}</p>
                    </div>

                    <!-- Datos y Botón -->
                    <div class="p-6 space-y-4">
                        <div class="space-y-2 text-gray-500 text-xs">
                            <div class="flex items-center gap-2">
                                <User class="w-4 h-4 text-gray-400" />
                                <span class="font-bold">Prof. {{ course.teacher_name }}</span>
                            </div>
                        </div>

                        <Link :href="route('student.courses.show', course.section_id)"
                            class="w-full flex items-center justify-center gap-2 py-3 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                            <BookOpen class="w-4 h-4" /> Ver Detalles del Curso
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Estado Vacío -->
            <div v-if="courses.length === 0" class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed">
                <BookOpen class="w-16 h-16 mx-auto text-gray-200 mb-4" />
                <h2 class="text-xl font-bold text-gray-400 uppercase">Sin Cursos Inscritos</h2>
                <p class="text-gray-400 italic">No tienes asignaturas registradas para el semestre actual.</p>
            </div>
        </div>
    </AppLayout>
</template>

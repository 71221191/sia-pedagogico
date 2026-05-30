<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Users, BookOpen, ClipboardList, CheckCircle2,
    AlertCircle, LayoutGrid, FileText
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    sections: any[]
}>();

// Función Maestra de Colores Pastel
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

// Función para convertir ciclo a Romano
const toRoman = (num: any) => {
    const map: any = { 1:'I', 2:'II', 3:'III', 4:'IV', 5:'V', 6:'VI', 7:'VII', 8:'VIII', 9:'IX', 10:'X' };
    return map[num] || num;
};
</script>

<template>
    <Head title="Mis Cursos" />
    <AppLayout>
        <div class="p-8 max-w-7xl mx-auto bg-gray-50 min-h-screen">

            <!-- Encabezado de la Vista -->
            <div class="mb-10">
                <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter italic flex items-center">
                    <LayoutGrid class="mr-3 w-8 h-8 text-emerald-600" />
                    Carga Académica Actual
                </h1>
                <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mt-2">Gestiona tus notas y sílabos</p>
            </div>

            <!-- Grid de Tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="section in sections" :key="section.id"
                     class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl overflow-hidden hover:shadow-2xl transition-all group">

                    <!-- Cabecera de Tarjeta con Color de Carrera -->
                    <div :class="getCourseColor(section.course.id)" class="p-6 border-b">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-3 py-1 bg-white/50 rounded-full text-[10px] font-black uppercase tracking-widest">
                                Ciclo {{ toRoman(section.course.cycle) }}
                            </span>
                            <div class="flex items-center gap-1 px-3 py-1 bg-white/50 rounded-full text-[9px] font-black uppercase">
                                <div :class="section.is_closed ? 'bg-red-500' : 'bg-green-500'" class="w-2 h-2 rounded-full animate-pulse"></div>
                                {{ section.is_closed ? 'Acta Cerrada' : 'Activa' }}
                            </div>
                        </div>
                        <h2 class="text-xl font-black uppercase leading-tight tracking-tighter">{{ section.course.name }}</h2>
                        <p class="text-[10px] font-bold opacity-70 mt-1 uppercase tracking-widest">{{ section.course.code }} | SECCIÓN {{ section.name }}</p>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Stats Rápidos -->
                        <div class="flex items-center justify-between text-gray-500">
                            <div class="flex items-center gap-2">
                                <Users class="w-4 h-4 text-emerald-600" />
                                <span class="text-xs font-black">{{ section.enrollment_details_count }} Alumnos</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <ClipboardList class="w-4 h-4 text-blue-600" />
                                <span class="text-xs font-black">Periodo {{ section.academic_period.name }}</span>
                            </div>
                        </div>

                        <!-- Botones de Acción Modificados (Limpio y Estético) -->
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <!-- 1. NOTAS -->
                            <Link :href="route('teacher.sections.show', section.id)"
                                class="flex flex-col items-center justify-center gap-2 p-3 bg-gray-50 rounded-2xl hover:bg-gray-900 hover:text-white transition-all">
                                <CheckCircle2 class="w-5 h-5 text-indigo-600" />
                                <span class="text-[8px] font-black uppercase">Notas</span>
                            </Link>

                            <!-- 2. SÍLABO -->
                            <Link :href="route('teacher.syllabus.index', section.id)"
                                class="flex flex-col items-center justify-center gap-2 p-3 bg-gray-50 rounded-2xl hover:bg-rose-600 hover:text-white transition-all">
                                <FileText class="w-5 h-5 text-rose-600" />
                                <span class="text-[8px] font-black uppercase">Sílabo</span>
                            </Link>

                            <!-- 3. COMPETENCIAS (Abarca ambas columnas abajo) -->
                            <Link :href="route('teacher.sections.configure', section.id)"
                                class="flex flex-col items-center justify-center gap-2 p-3 bg-amber-50 rounded-2xl hover:bg-amber-500 hover:text-white transition-all col-span-2">
                                <div class="flex items-center gap-2">
                                    <BookOpen class="w-4 h-4 text-amber-600 group-hover:text-white" />
                                    <span class="text-[9px] font-black uppercase">Configurar Competencias del Curso</span>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado Vacío -->
            <div v-if="sections.length === 0" class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed">
                <AlertCircle class="w-16 h-16 mx-auto text-gray-200 mb-4" />
                <h2 class="text-xl font-bold text-gray-400 uppercase">Sin Carga Académica</h2>
                <p class="text-gray-400 italic">No tienes secciones asignadas para el periodo actual.</p>
            </div>
        </div>
    </AppLayout>
</template>

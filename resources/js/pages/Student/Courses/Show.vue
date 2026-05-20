<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    BookOpen, FileText, Link as LinkIcon,
    Download, ExternalLink, Clock, CheckCircle,
    AlertCircle, MessageSquare, ArrowLeft, GraduationCap
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    section: any,
    units: any[]
}>();

// Función para convertir ciclo a Romano
const toRoman = (num: any) => {
    const map: any = { 1:'I', 2:'II', 3:'III', 4:'IV' };
    return map[num] || num;
};

// Lógica para el estado de la tarea del alumno
const getTaskStatus = (task: any) => {
    if (!task.my_submission) return { label: 'Pendiente', class: 'bg-gray-100 text-gray-500', icon: Clock };

    if (task.my_submission.status === 'graded') {
        return { label: `Calificado: ${task.my_submission.score}`, class: 'bg-emerald-100 text-emerald-700', icon: CheckCircle };
    }

    return { label: 'Enviado', class: 'bg-blue-100 text-blue-700', icon: CheckCircle };
};
</script>

<template>
    <Head :title="section.course.name" />
    <AppLayout>
        <div class="p-4 md:p-8 max-w-5xl mx-auto bg-gray-50 min-h-screen">

            <!-- ENCABEZADO DEL CURSO -->
            <div class="mb-10">
                <Link :href="route('dashboard')" class="text-[10px] font-black text-gray-400 uppercase flex items-center mb-4 hover:text-indigo-600 transition-all">
                    <ArrowLeft class="w-3 h-3 mr-1" /> Regresar al Panel
                </Link>
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-5">
                        <GraduationCap class="w-32 h-32" />
                    </div>
                    <div class="relative z-10">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                            Curso Académico
                        </span>
                        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mt-2 leading-tight">
                            {{ section.course.name }}
                        </h1>
                        <div class="flex items-center gap-4 mt-4 text-gray-500 text-xs font-bold uppercase tracking-widest">
                            <span class="flex items-center gap-1"><BookOpen class="w-4 h-4" /> Ciclo {{ toRoman(section.course.cycle) }}</span>
                            <span>•</span>
                            <span class="flex items-center gap-1"><CheckCircle class="w-4 h-4" /> Prof. {{ section.teacher.names }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LISTADO DE UNIDADES -->
            <div class="space-y-10">
                <div v-for="unit in units" :key="unit.id" class="animate-in slide-in-from-bottom-4">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-gray-900 text-white w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg shadow-lg">
                            {{ toRoman(unit.order) }}
                        </div>
                        <h2 class="text-xl font-black text-gray-800 uppercase tracking-tight">{{ unit.name }}</h2>
                        <div class="h-[2px] flex-1 bg-gray-200"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- BLOQUE DE RECURSOS (Materiales) -->
                        <div class="space-y-3">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center ml-2">
                                <FileText class="w-3 h-3 mr-2" /> Materiales de Estudio
                            </h3>
                            <div v-for="res in unit.resources" :key="res.id"
                                 class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:bg-indigo-50/30 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div :class="res.type === 'file' ? 'text-blue-600 bg-blue-50' : 'text-amber-600 bg-amber-50'" class="p-2 rounded-lg">
                                        <FileText v-if="res.type === 'file'" class="w-4 h-4" />
                                        <LinkIcon v-else class="w-4 h-4" />
                                    </div>
                                    <span class="text-xs font-bold text-gray-700 uppercase leading-none">{{ res.title }}</span>
                                </div>
                                <a :href="res.type === 'file' ? '/storage/' + res.file_path : res.url" target="_blank"
                                   class="p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                                    <Download v-if="res.type === 'file'" class="w-4 h-4" />
                                    <ExternalLink v-else class="w-4 h-4" />
                                </a>
                            </div>
                            <p v-if="unit.resources.length === 0" class="text-[10px] text-gray-300 italic ml-2">No hay materiales publicados.</p>
                        </div>

                        <!-- BLOQUE DE ACTIVIDADES (Tareas) -->
                        <div class="space-y-3">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center ml-2">
                                <AlertCircle class="w-3 h-3 mr-2" /> Actividades Calificables
                            </h3>
                            <div v-for="task in unit.tasks" :key="task.id"
                                 class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm space-y-4">

                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="text-xs font-black text-gray-800 uppercase leading-tight">{{ task.title }}</h4>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Vence: {{ new Date(task.due_date).toLocaleDateString() }}</p>
                                    </div>
                                    <span :class="getTaskStatus(task).class" class="px-3 py-1 rounded-full text-[8px] font-black uppercase border flex items-center gap-1">
                                        <component :is="getTaskStatus(task).icon" class="w-2 h-2" />
                                        {{ getTaskStatus(task).label }}
                                    </span>
                                </div>

                                <!-- Botón para ir a la tarea -->
                                <Link :href="route('student.tasks.show', task.id)"
                                      class="block w-full text-center py-2.5 bg-gray-50 text-gray-900 rounded-xl text-[9px] font-black uppercase hover:bg-indigo-600 hover:text-white transition-all">
                                    {{ task.my_submission ? 'Ver Mi Entrega' : 'Realizar Entrega' }}
                                </Link>

                                <!-- Feedback si está calificado -->
                                <div v-if="task.my_submission?.teacher_feedback" class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                                    <span class="flex items-center text-[8px] font-black text-amber-700 uppercase mb-1">
                                        <MessageSquare class="w-2 h-2 mr-1" /> Nota del Docente:
                                    </span>
                                    <p class="text-[9px] text-amber-800 italic leading-tight">{{ task.my_submission.teacher_feedback }}</p>
                                </div>
                            </div>
                            <p v-if="unit.tasks.length === 0" class="text-[10px] text-gray-300 italic ml-2">No hay tareas pendientes.</p>
                        </div>

                        <!-- BLOQUE DE FOROS DE DEBATE (NUEVO) -->
                        <div class="mt-6 space-y-3">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center ml-2">
                                <MessageSquare class="w-3 h-3 mr-2 text-purple-500" /> Espacios de Debate
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="forum in unit.forums" :key="forum.id"
                                    class="bg-white p-5 rounded-[2rem] border border-gray-100 shadow-sm flex items-center justify-between hover:border-purple-200 transition-all group">

                                    <div class="flex items-center gap-4">
                                        <div class="bg-purple-50 text-purple-600 p-3 rounded-2xl">
                                            <MessagesSquare class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-gray-800 uppercase leading-tight">{{ forum.title }}</h4>
                                            <span class="text-[8px] font-bold text-gray-400 uppercase">{{ forum.posts_count }} Intervenciones</span>
                                        </div>
                                    </div>

                                    <Link :href="route('student.forums.show', forum.id)"
                                        class="p-3 bg-gray-900 text-white rounded-xl hover:bg-purple-600 transition-all transform active:scale-95">
                                        <ArrowRight class="w-4 h-4" />
                                    </Link>
                                </div>
                            </div>
                            <p v-if="unit.forums.length === 0" class="text-[10px] text-gray-300 italic ml-2">No hay foros abiertos en esta unidad.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

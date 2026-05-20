<script setup lang="ts">
import { useForm, Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft, FileUp, Clock, CheckCircle,
    AlertTriangle, Download, MessageSquare, ShieldCheck
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    task: any,
    submission: any
}>();

const form = useForm({
    file: null as File | null
});

const handleFile = (e: any) => {
    form.file = e.target.files[0];
};

const submit = () => {
    form.post(route('student.tasks.submit', props.task.id), {
        onSuccess: () => form.reset()
    });
};

// Saber si el sistema aún acepta entregas
const isClosed = new Date() > new Date(props.task.closing_date);
</script>

<template>
    <Head :title="task.title" />
    <AppLayout>
        <div class="p-4 md:p-8 max-w-4xl mx-auto bg-gray-50 min-h-screen">

            <Link :href="route('student.courses.show', task.unit.course_section_id)" class="text-[10px] font-black text-gray-400 uppercase flex items-center mb-6 hover:text-indigo-600 transition-all">
                <ArrowLeft class="w-3 h-3 mr-1" /> Volver al curso
            </Link>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- DETALLE DE LA TAREA (Izquierda) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-gray-100">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[9px] font-black uppercase tracking-widest">Actividad Calificable</span>
                        <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mt-3 mb-6">{{ task.title }}</h1>

                        <div class="prose prose-sm max-w-none text-gray-600 font-medium leading-relaxed mb-8">
                            {{ task.description }}
                        </div>

                        <!-- Info de Fechas -->
                        <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 rounded-xl text-gray-500"><Clock class="w-4 h-4" /></div>
                                <div>
                                    <p class="text-[8px] font-black text-gray-400 uppercase">Fecha Límite</p>
                                    <p class="text-xs font-bold text-gray-700">{{ new Date(task.due_date).toLocaleString() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-rose-50 text-rose-500"><AlertTriangle class="w-4 h-4" /></div>
                                <div>
                                    <p class="text-[8px] font-black text-gray-400 uppercase">Cierre de Sistema</p>
                                    <p class="text-xs font-bold text-rose-600">{{ new Date(task.closing_date).toLocaleString() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FEEDBACK DEL DOCENTE (Si ya está calificado) -->
                    <div v-if="submission?.status === 'graded'" class="bg-emerald-600 p-8 rounded-[3rem] shadow-2xl text-white">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-white/20 p-3 rounded-2xl"><MessageSquare class="w-6 h-6" /></div>
                            <h3 class="text-xl font-black uppercase tracking-tighter">Retroalimentación del Docente</h3>
                        </div>
                        <p class="text-sm font-medium italic opacity-90 mb-6">"{{ submission.teacher_feedback || 'Sin comentarios adicionales.' }}"</p>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black uppercase opacity-60">Calificación Obtenida:</span>
                            <span class="text-3xl font-black">{{ submission.score }}</span>
                            <span class="text-sm opacity-60">/ {{ task.max_score }}</span>
                        </div>
                    </div>
                </div>

                <!-- ZONA DE ENTREGA (Derecha) -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100 sticky top-8 text-center">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Estado de la Entrega</h3>

                        <!-- Estado: ENVIADO -->
                        <div v-if="submission" class="mb-8">
                            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                <CheckCircle class="w-8 h-8" />
                            </div>
                            <p class="text-xs font-black text-gray-800 uppercase">Archivo Enviado</p>
                            <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase">{{ new Date(submission.submitted_at).toLocaleString() }}</p>

                            <a :href="'/storage/' + submission.file_path" target="_blank"
                               class="mt-6 inline-flex items-center gap-2 text-indigo-600 font-black text-[10px] uppercase hover:underline">
                                <Download class="w-4 h-4" /> Ver mi archivo
                            </a>
                        </div>

                        <!-- Estado: PENDIENTE -->
                        <div v-else class="mb-8">
                            <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-3xl flex items-center justify-center mx-auto mb-4 border-2 border-dashed">
                                <FileUp class="w-8 h-8" />
                            </div>
                            <p class="text-xs font-black text-gray-400 uppercase">Sin entregar</p>
                        </div>

                        <!-- FORMULARIO (Si no ha cerrado) -->
                        <form v-if="!isClosed && submission?.status !== 'graded'" @submit.prevent="submit" class="space-y-4">
                            <div class="relative group">
                                <input type="file" @change="handleFile" :accept="'.' + task.allowed_formats.replace(',', ',.')"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-4 group-hover:border-indigo-400 transition-all">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                        {{ form.file ? form.file.name : 'Seleccionar Archivo' }}
                                    </span>
                                </div>
                            </div>
                            <button :disabled="!form.file || form.processing"
                                    class="w-full py-4 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase shadow-xl hover:bg-indigo-600 transition-all transform active:scale-95 disabled:opacity-20">
                                {{ submission ? 'REEMPLAZAR ENTREGA' : 'ENVIAR TAREA' }}
                            </button>
                            <p class="text-[8px] text-gray-400 font-bold uppercase italic">Formatos: {{ task.allowed_formats }} | Max: {{ task.max_file_size_kb / 1024 }}MB</p>
                        </form>

                        <!-- Mensaje de Cierre -->
                        <div v-else-if="isClosed" class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-700 text-[10px] font-black uppercase">
                            La plataforma está cerrada para esta actividad.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

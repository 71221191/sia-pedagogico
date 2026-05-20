<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Head, Link, router, usePage } from '@inertiajs/vue3';

import {
    Plus, Trash2, Calendar, Clock,
    FileUp, ArrowLeft, ClipboardCheck, Info,
    AlertCircle, FileType
} from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    unit: any,
    tasks: any[]
}>();

const isModalOpen = ref(false);

const form = useForm({
    title: '',
    description: '',
    due_date: '',
    closing_date: '',
    max_score: 20,
    allowed_formats: 'pdf,docx',
    max_file_size_kb: 5120 // 5MB
});

const submit = () => {
    form.post(route('teacher.tasks.store', props.unit.id), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        }
    });
};

const page = usePage<any>();

const formatDate = (date: string) => {
    return new Date(date).toLocaleString('es-ES', {
        day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit'
    });
};

const deleteTask = (id: number) => {
    if (confirm('¿Estás seguro de eliminar esta tarea?')) {
        router.delete(route('teacher.tasks.destroy', id), {
            preserveScroll: true,
        });
    }
};

</script>

<template>
    <Head title="Gestión de Tareas" />
    <AppLayout>
        <div class="p-8 max-w-5xl mx-auto bg-gray-50 min-h-screen">

            <!-- HEADER -->
            <div class="mb-10">
                <Link :href="route('teacher.units.index', unit.course_section_id)" class="text-[10px] font-black text-gray-400 uppercase flex items-center mb-2 hover:text-indigo-600 transition-all">
                    <ArrowLeft class="w-3 h-3 mr-1" /> Volver a unidades
                </Link>
                <div class="flex justify-between items-end">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter italic">Tareas de {{ unit.name }}</h1>
                        <p class="text-indigo-600 font-bold text-xs uppercase">{{ unit.section.course.name }}</p>
                    </div>
                    <button @click="isModalOpen = true" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase shadow-xl hover:bg-indigo-700 transition transform hover:scale-105">
                        <Plus class="w-4 h-4 mr-2 inline" /> Crear Tarea
                    </button>
                </div>
            </div>

            <!-- ALERTA DE MENSAJES (Error/Éxito) -->
            <div v-if="page.props.flash?.error"
                class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-2xl flex items-center gap-3 shadow-sm">
                <AlertCircle class="w-5 h-5 shrink-0" />
                <span class="text-xs font-bold uppercase tracking-tight">{{ page.props.flash.error }}</span>
            </div>

            <div v-if="page.props.flash?.success"
                class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl flex items-center gap-3 shadow-sm">
                <CheckCircle2 class="w-5 h-5 shrink-0" />
                <span class="text-xs font-bold uppercase tracking-tight">{{ page.props.flash.success }}</span>
            </div>

            <!-- LISTA DE TAREAS -->
            <div class="space-y-4">
                <div v-for="task in tasks" :key="task.id"
                     class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group">

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="bg-indigo-50 text-indigo-600 p-4 rounded-2xl shadow-sm">
                                <FileUp class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="font-black text-gray-800 text-base uppercase leading-tight">{{ task.title }}</h3>
                                <div class="flex flex-wrap gap-4 mt-2">
                                    <div class="flex items-center text-[10px] font-bold text-gray-400 uppercase">
                                        <Calendar class="w-3 h-3 mr-1" /> Vence: {{ formatDate(task.due_date) }}
                                    </div>
                                    <div class="flex items-center text-[10px] font-bold text-rose-400 uppercase">
                                        <Clock class="w-3 h-3 mr-1" /> Cierre: {{ formatDate(task.closing_date) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Badge de Entregas -->
                        <div class="flex items-center gap-4">
                            <Link :href="route('teacher.submissions.index', task.id)"
                                class="text-center px-6 py-2 bg-indigo-50 text-indigo-600 rounded-2xl border border-indigo-100 hover:bg-indigo-600 hover:text-white transition-all transform hover:scale-105">
                                <span class="block text-xl font-black">{{ task.submissions_count }}</span>
                                <span class="text-[8px] font-black uppercase tracking-widest">Entregas</span>
                            </Link>

                            <button @click="router.delete(route('teacher.tasks.destroy', task.id))"
                                    class="p-3 text-gray-300 hover:text-red-600 transition-all">
                                <Trash2 class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="tasks.length === 0" class="p-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-200">
                    <ClipboardCheck class="w-12 h-12 text-gray-200 mx-auto mb-2" />
                    <p class="text-gray-400 font-bold uppercase text-[10px]">No has creado tareas en esta unidad</p>
                </div>
            </div>

            <!-- MODAL DE CREACIÓN -->
            <div v-if="isModalOpen" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
                    <div class="p-6 border-b bg-gray-50 flex justify-between items-center sticky top-0 bg-white z-10">
                        <h2 class="font-black text-gray-800 uppercase text-sm tracking-tighter italic">Nueva Actividad Calificable</h2>
                        <button @click="isModalOpen = false" class="text-gray-400 hover:text-red-500 text-2xl font-black">×</button>
                    </div>

                    <form @submit.prevent="submit" class="p-8 space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Título de la Tarea</label>
                            <input v-model="form.title" type="text" class="w-full border-gray-200 rounded-xl text-sm font-bold" required />
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Instrucciones</label>
                            <textarea v-model="form.description" class="w-full border-gray-200 rounded-2xl text-sm" rows="4" required></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Fecha Entrega</label>
                                <input v-model="form.due_date" type="datetime-local" class="w-full border-gray-200 rounded-xl text-xs font-bold" required />
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Fecha Cierre</label>
                                <input v-model="form.closing_date" type="datetime-local" class="w-full border-gray-200 rounded-xl text-xs font-bold" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Formatos Permitidos</label>
                                <input v-model="form.allowed_formats" type="text" placeholder="pdf,docx,jpg" class="w-full border-gray-200 rounded-xl text-xs font-mono" />
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Nota Máxima</label>
                                <input v-model="form.max_score" type="number" class="w-full border-gray-200 rounded-xl text-sm font-black text-center" />
                            </div>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-2xl flex gap-3">
                            <Info class="w-5 h-5 text-blue-600 flex-shrink-0" />
                            <p class="text-[9px] text-blue-700 font-bold leading-tight uppercase">
                                El alumno podrá entregar después de la fecha límite hasta que llegue la fecha de cierre. Los envíos tardíos serán marcados automáticamente.
                            </p>
                        </div>

                        <button :disabled="form.processing" class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black text-xs uppercase shadow-xl hover:bg-indigo-600 transition-all transform active:scale-95">
                            {{ form.processing ? 'Publicando...' : 'Publicar Tarea Oficial' }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

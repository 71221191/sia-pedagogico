<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    Plus, Trash2, MessagesSquare, ArrowLeft,
    MessageCircle, Power, ExternalLink, Info
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    unit: any,
    forums: any[]
}>();

const isModalOpen = ref(false);

const form = useForm({
    title: '',
    description: '',
});

const submit = () => {
    form.post(route('teacher.forums.store', props.unit.id), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        }
    });
};

const page = usePage<any>();

const toggleStatus = (id: number) => {
    router.patch(route('teacher.forums.toggle', id));
};

const deleteForum = (id: number) => {
    if (confirm('¿Eliminar este foro y todos sus mensajes? Esta acción no se puede deshacer.')) {
        router.delete(route('teacher.forums.destroy', id));
    }
};

// 1. Variable para el foro seleccionado
const forumToDelete = ref<any>(null);

// 2. Función para abrir el modal
const confirmDelete = (forum: any) => {
    forumToDelete.value = forum;
};

// 3. Función para ejecutar el borrado real
const executeDelete = () => {
    router.delete(route('teacher.forums.destroy', forumToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => forumToDelete.value = null
    });
};


</script>

<template>
    <Head title="Foros de Debate" />
    <AppLayout>
        <div class="p-8 max-w-5xl mx-auto bg-gray-50 min-h-screen">

            <!-- HEADER -->
            <div class="mb-10">
                <Link :href="route('teacher.units.index', unit.course_section_id)" class="text-[10px] font-black text-gray-400 uppercase flex items-center mb-2 hover:text-purple-600 transition-all">
                    <ArrowLeft class="w-3 h-3 mr-1" /> Volver a unidades
                </Link>
                <div class="flex justify-between items-end">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter italic">Debates de {{ unit.name }}</h1>
                        <p class="text-purple-600 font-bold text-xs uppercase">{{ unit.section.course.name }}</p>
                    </div>
                    <button @click="isModalOpen = true" class="bg-purple-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase shadow-xl hover:bg-purple-700 transition transform hover:scale-105">
                        <Plus class="w-4 h-4 mr-2 inline" /> Abrir Debate
                    </button>
                </div>
            </div>

            <!-- ALERTA DE MENSAJES (Éxito / Error) -->
            <div v-if="page.props.flash?.error"
                class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-2xl flex items-center gap-3 shadow-sm animate-in slide-in-from-top-2">
                <AlertCircle class="w-5 h-5 shrink-0" />
                <span class="text-xs font-bold uppercase tracking-tight">{{ page.props.flash.error }}</span>
            </div>

            <div v-if="page.props.flash?.success"
                class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl flex items-center gap-3 shadow-sm animate-in slide-in-from-top-2">
                <CheckCircle2 class="w-5 h-5 shrink-0" />
                <span class="text-xs font-bold uppercase tracking-tight">{{ page.props.flash.success }}</span>
            </div>

            <!-- LISTA DE FOROS -->
            <div class="space-y-4">
                <div v-for="forum in forums" :key="forum.id"
                     class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group">

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-start gap-4 flex-1">
                            <div :class="forum.is_active ? 'bg-purple-50 text-purple-600' : 'bg-gray-50 text-gray-400'" class="p-4 rounded-2xl shadow-sm transition-colors">
                                <MessagesSquare class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="font-black text-gray-800 text-base uppercase leading-tight">{{ forum.title }}</h3>
                                <p class="text-[10px] text-gray-400 font-medium mt-1 line-clamp-2">{{ forum.description }}</p>
                            </div>
                        </div>

                        <!-- Acciones de Moderación -->
                        <div class="flex items-center gap-3">
                            <div class="text-center px-4">
                                <span class="block text-xl font-black text-purple-600">{{ forum.posts_count }}</span>
                                <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Intervenciones</span>
                            </div>

                            <!-- Botón Entrar al Foro (Para leer y moderar) -->
                            <Link :href="route('student.forums.show', forum.id)"
                                  class="p-3 bg-gray-900 text-white rounded-xl hover:bg-purple-600 transition-all shadow-lg shadow-gray-100">
                                <ExternalLink class="w-5 h-5" />
                            </Link>

                            <!-- Switch de Estado (Cerrar/Abrir Foro) -->
                            <button @click="toggleStatus(forum.id)"
                                    :class="forum.is_active ? 'text-emerald-500 hover:text-emerald-600' : 'text-gray-300 hover:text-rose-500'"
                                    class="p-3 transition-colors" :title="forum.is_active ? 'Cerrar Foro' : 'Abrir Foro'">
                                <Power class="w-5 h-5" />
                            </button>

                            <button @click="confirmDelete(forum)" class="p-3 text-gray-300 hover:text-rose-600 transition-all">
                                <Trash2 class="w-5 h-5" />
                            </button>

                        </div>
                    </div>
                </div>

                <div v-if="forums.length === 0" class="p-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-200">
                    <MessageCircle class="w-12 h-12 text-gray-200 mx-auto mb-2" />
                    <p class="text-gray-400 font-bold uppercase text-[10px]">No hay foros activos en esta unidad</p>
                </div>
            </div>

            <!-- MODAL DE CREACIÓN -->
            <div v-if="isModalOpen" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-in zoom-in-95">
                    <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
                        <h2 class="font-black text-gray-800 uppercase text-sm tracking-tighter italic">Lanzar Pregunta de Debate</h2>
                        <button @click="isModalOpen = false" class="text-gray-400 hover:text-red-500 text-2xl font-black">×</button>
                    </div>
                    <form @submit.prevent="submit" class="p-8 space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Tema del Foro</label>
                            <input v-model="form.title" type="text" placeholder="Ej: ¿Qué es la Didáctica Crítica?" class="w-full border-gray-200 rounded-xl text-sm font-bold" required />
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Pregunta o Instrucción</label>
                            <textarea v-model="form.description" class="w-full border-gray-200 rounded-2xl text-sm" rows="4" placeholder="Plantea el tema para que tus alumnos debatan..." required></textarea>
                        </div>

                        <div class="p-4 bg-purple-50 rounded-2xl flex gap-3">
                            <Info class="w-5 h-5 text-purple-600 flex-shrink-0" />
                            <p class="text-[9px] text-purple-700 font-bold leading-tight uppercase">
                                Al crear el foro, los alumnos podrán empezar a publicar sus comentarios de inmediato.
                            </p>
                        </div>

                        <button :disabled="form.processing" class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black text-xs uppercase shadow-xl hover:bg-purple-600 transition-all transform active:scale-95">
                            {{ form.processing ? 'Iniciando...' : 'Abrir Foro a los Alumnos' }}
                        </button>
                    </form>
                </div>
            </div>
            <!-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN DE FORO -->
            <div v-if="forumToDelete" class="fixed inset-0 bg-rose-900/40 backdrop-blur-sm z-[70] flex items-center justify-center p-4">
                <div class="bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
                    <div class="p-8 text-center">
                        <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <Trash2 class="w-10 h-10" />
                        </div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter mb-2">¿Eliminar Debate?</h3>
                        <p class="text-sm text-gray-500 leading-tight">
                            Estás a punto de borrar el foro <strong>"{{ forumToDelete.title }}"</strong>.
                            Se perderán todas las intervenciones de los alumnos de forma permanente.
                        </p>
                    </div>
                    <div class="p-6 bg-gray-50 flex gap-3">
                        <button @click="forumToDelete = null" class="flex-1 py-4 text-xs font-black uppercase text-gray-400 hover:text-gray-600 transition-colors">
                            Cancelar
                        </button>
                        <button @click="executeDelete" class="flex-1 bg-rose-600 text-white py-4 rounded-2xl font-black text-xs uppercase shadow-xl shadow-rose-100 hover:bg-rose-700 transition-all">
                            Sí, Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

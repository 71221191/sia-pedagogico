<script setup lang="ts">
import { ref, nextTick } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft, Send, User, MessageSquare,
    CornerDownRight, Lock, ShieldCheck, Clock
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    forum: any,
    currentUser: any
}>();

const form = useForm({
    content: '',
    parent_id: null as number | null
});

// Para gestionar a quién estamos respondiendo
const replyingTo = ref<any>(null);

const setReply = (post: any) => {
    replyingTo.value = post;
    form.parent_id = post.id;
    // Hacemos scroll al formulario
    nextTick(() => {
        document.getElementById('comment-form')?.scrollIntoView({ behavior: 'smooth' });
    });
};

const cancelReply = () => {
    replyingTo.value = null;
    form.parent_id = null;
};

const submit = () => {
    form.post(route('student.forums.store-post', props.forum.id), {
        onSuccess: () => {
            form.reset();
            cancelReply();
        },
        preserveScroll: true
    });
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleString('es-ES', {
        day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit'
    });
};

// Función para verificar si un usuario tiene el rol de docente de forma segura
const checkIsDocente = (roles: any[]) => {
    return roles.some((r: any) => r.name === 'docente');
};

</script>

<template>
    <Head :title="forum.title" />
    <AppLayout>
        <div class="p-4 md:p-8 max-w-4xl mx-auto bg-gray-50 min-h-screen pb-40">

            <!-- REGRESAR -->
            <Link :href="route('student.courses.show', forum.unit.course_section_id)"
                  class="text-[10px] font-black text-gray-400 uppercase flex items-center mb-6 hover:text-indigo-600 transition-all">
                <ArrowLeft class="w-3 h-3 mr-1" /> Volver al Aula Virtual
            </Link>

            <!-- ENCABEZADO DEL DEBATE (La Pregunta Generadora) -->
            <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-gray-100 mb-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-5 rotate-12">
                    <MessageSquare class="w-32 h-32" />
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-[9px] font-black uppercase tracking-widest">Foro de Debate</span>
                        <span class="text-gray-300">•</span>
                        <span class="text-[9px] font-bold text-gray-400 uppercase">{{ forum.unit.name }}</span>
                    </div>
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-tight mb-4">
                        {{ forum.title }}
                    </h1>
                    <p class="text-sm text-gray-600 font-medium leading-relaxed bg-gray-50 p-6 rounded-2xl border-l-4 border-purple-500">
                        {{ forum.description }}
                    </p>
                </div>
            </div>

            <!-- LISTA DE INTERVENCIONES -->
            <div class="space-y-6">
                <div v-for="post in forum.posts" :key="post.id"
                     :class="{'ml-12': post.parent_id}"
                     class="group animate-in slide-in-from-left-4">

                    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 relative"
                         :class="{'border-indigo-200 bg-indigo-50/20': checkIsDocente(post.author.user.roles)}"

                        <!-- Etiqueta de Docente -->
                        <div v-if="checkIsDocente(post.author.user.roles)"
                             class="absolute -top-3 left-8 px-3 py-1 bg-indigo-600 text-white text-[8px] font-black uppercase rounded-full shadow-lg">
                            Guía Docente
                        </div>

                        <div class="flex gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 overflow-hidden shadow-inner">
                                    <img v-if="post.author.official_photo_path" :src="'/storage/' + post.author.official_photo_path" class="object-cover w-full h-full" />
                                    <User v-else class="w-5 h-5" />
                                </div>
                            </div>

                            <!-- Contenido -->
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-xs font-black text-gray-900 uppercase tracking-tight">{{ post.author.full_name }}</h4>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase flex items-center">
                                        <Clock class="w-2 h-2 mr-1" /> {{ formatDate(post.created_at) }}
                                    </span>
                                </div>

                                <!-- Si es una respuesta, mostrar a quién -->
                                <div v-if="post.parent_id" class="mb-2 flex items-center gap-1 text-[9px] text-indigo-400 font-bold italic">
                                    <CornerDownRight class="w-3 h-3" /> respondió a un comentario
                                </div>

                                <p class="text-sm text-gray-700 leading-relaxed break-words whitespace-pre-line">{{ post.content }}</p>

                                <!-- Botón Responder -->
                                <div v-if="forum.is_active" class="mt-4 flex justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="setReply(post)" class="text-[9px] font-black text-indigo-600 uppercase hover:underline flex items-center gap-1">
                                        Responder <CornerDownRight class="w-3 h-3" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje si no hay posts -->
                <div v-if="forum.posts.length === 0" class="py-10 text-center">
                    <p class="text-gray-400 text-xs italic uppercase tracking-widest">Aún no hay intervenciones. ¡Sé el primero en participar!</p>
                </div>
            </div>

            <!-- ÁREA DE COMENTARIO (FIJA ABAJO) -->
            <div class="fixed bottom-0 left-0 right-0 p-4 md:p-8 bg-gradient-to-t from-gray-50 via-gray-50 to-transparent pointer-events-none">
                <div class="max-w-4xl mx-auto pointer-events-auto">

                    <!-- Alerta de Foro Cerrado -->
                    <div v-if="!forum.is_active" class="bg-gray-900 text-white p-4 rounded-2xl flex items-center justify-center gap-3 shadow-2xl">
                        <Lock class="w-5 h-5 text-rose-500" />
                        <span class="text-[10px] font-black uppercase tracking-widest">Este debate ha sido cerrado por el docente</span>
                    </div>

                    <!-- Formulario de Envío -->
                    <div v-else id="comment-form" class="bg-white p-4 rounded-[2.5rem] shadow-2xl border border-gray-100">
                        <!-- Indicador de Respuesta -->
                        <div v-if="replyingTo" class="px-4 py-2 mb-3 bg-indigo-50 rounded-xl flex justify-between items-center">
                            <span class="text-[9px] font-bold text-indigo-600 uppercase italic">Respondiendo a: {{ replyingTo.author.full_name }}</span>
                            <button @click="cancelReply" class="text-rose-500 font-black text-xs">×</button>
                        </div>

                        <div class="flex items-end gap-3">
                            <textarea
                                v-model="form.content"
                                rows="1"
                                placeholder="Escribe tu aporte pedagógico aquí..."
                                class="flex-1 border-none focus:ring-0 text-sm font-medium resize-none max-h-32"
                            ></textarea>
                            <button
                                @click="submit"
                                :disabled="!form.content || form.processing"
                                class="p-4 bg-gray-900 text-white rounded-2xl hover:bg-indigo-600 transition-all shadow-xl disabled:opacity-20 transform active:scale-90"
                            >
                                <Send class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </AppLayout>
</template>

<style scoped>
/* Para que el textarea crezca solo (opcional con JS, pero aquí lo dejamos limpio) */
textarea { overflow-y: auto; }
</style>

<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft, FileText, CheckCircle, Clock,
    Download, MessageSquare, Save, AlertCircle, User
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    task: any,
    submissions: any[]
}>();

// Formulario para calificar (se llena dinámicamente)
const gradeForm = useForm({
    score: '',
    teacher_feedback: ''
});

const selectedSubmission = ref<any>(null);

const selectSubmission = (sub: any) => {
    selectedSubmission.value = sub;
    gradeForm.score = sub.score || '';
    gradeForm.teacher_feedback = sub.teacher_feedback || '';
};

const submitGrade = () => {
    gradeForm.patch(route('teacher.submissions.grade', selectedSubmission.value.id), {
        onSuccess: () => {
            alert('Nota guardada con éxito');
            selectedSubmission.value = null;
        }
    });
};

const getStatusLabel = (sub: any) => {
    if (sub.status === 'graded') return { text: 'Calificado', class: 'bg-emerald-100 text-emerald-700' };
    const isLate = new Date(sub.submitted_at) > new Date(props.task.due_date);
    return isLate
        ? { text: 'Fuera de plazo', class: 'bg-rose-100 text-rose-700' }
        : { text: 'Enviado', class: 'bg-blue-100 text-blue-700' };
};
</script>

<template>
    <Head title="Revisar Tareas" />
    <AppLayout>
        <div class="p-8 max-w-7xl mx-auto bg-gray-50 min-h-screen">

            <!-- HEADER -->
            <div class="mb-10">
                <Link :href="route('teacher.tasks.index', task.academic_unit_id)" class="text-[10px] font-black text-gray-400 uppercase flex items-center mb-2 hover:text-indigo-600">
                    <ArrowLeft class="w-3 h-3 mr-1" /> Volver a tareas
                </Link>
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter italic">{{ task.title }}</h1>
                <p class="text-indigo-600 font-bold text-[10px] uppercase">Revisión de entregas | Máximo {{ task.max_score }} pts.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- LISTA DE ALUMNOS (Izquierda) -->
                <div class="lg:col-span-1 space-y-3">
                    <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Estudiantes ({{ submissions.length }})</h2>
                    <button v-for="sub in submissions" :key="sub.id"
                        @click="selectSubmission(sub)"
                        :class="[
                            'w-full p-4 rounded-[1.5rem] border-2 text-left transition-all',
                            selectedSubmission?.id === sub.id ? 'border-indigo-600 bg-white shadow-xl scale-105' : 'border-transparent bg-white/50 hover:bg-white'
                        ]"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <User class="w-4 h-4" />
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-gray-800 uppercase leading-none">{{ sub.student.last_name_p }} {{ sub.student.names }}</p>
                                <span :class="getStatusLabel(sub).class" class="text-[7px] font-black uppercase px-2 py-0.5 rounded-full mt-1 inline-block">
                                    {{ getStatusLabel(sub).text }}
                                </span>
                            </div>
                            <div v-if="sub.score" class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg font-black text-xs">
                                {{ sub.score }}
                            </div>
                        </div>
                    </button>
                </div>

                <!-- PANEL DE CALIFICACIÓN (Derecha) -->
                <div class="lg:col-span-2">
                    <div v-if="selectedSubmission" class="bg-white rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden animate-in slide-in-from-right-4">
                        <div class="p-8 bg-gray-900 text-white flex justify-between items-center">
                            <div>
                                <p class="text-[8px] font-black uppercase tracking-[0.2em] text-indigo-300">Expediente de Entrega</p>
                                <h3 class="text-xl font-black uppercase leading-tight">{{ selectedSubmission.student.last_name_p }} {{ selectedSubmission.student.names }}</h3>
                            </div>
                            <a :href="'/storage/' + selectedSubmission.file_path" target="_blank"
                               class="bg-white/10 hover:bg-white/20 p-4 rounded-2xl transition-all flex items-center gap-2">
                                <Download class="w-5 h-5" />
                                <span class="text-[10px] font-black uppercase">Ver Trabajo</span>
                            </a>
                        </div>

                        <form @submit.prevent="submitGrade" class="p-10 space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                                <!-- Input Nota -->
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest text-center">Nota Final</label>
                                    <input v-model="gradeForm.score" type="number" step="0.01"
                                           class="w-full text-4xl font-black text-center border-2 border-indigo-50 rounded-[1.5rem] py-6 focus:ring-indigo-500 focus:border-indigo-500"
                                           :placeholder="task.max_score.toString()" required />
                                </div>

                                <!-- Feedback -->
                                <div class="md:col-span-3">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest flex items-center">
                                        <MessageSquare class="w-3 h-3 mr-1" /> Comentario Pedagógico
                                    </label>
                                    <textarea v-model="gradeForm.teacher_feedback"
                                              class="w-full border-2 border-indigo-50 rounded-[2rem] p-6 text-sm font-medium focus:ring-indigo-500"
                                              rows="4" placeholder="Escribe aquí tus observaciones para el estudiante..."></textarea>
                                </div>
                            </div>

                            <button :disabled="gradeForm.processing" class="w-full bg-indigo-600 text-white py-5 rounded-[2rem] font-black text-xs uppercase shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center justify-center gap-3">
                                <Save class="w-5 h-5" />
                                {{ gradeForm.processing ? 'Registrando...' : 'Confirmar Calificación' }}
                            </button>
                        </form>
                    </div>

                    <!-- Estado Inicial -->
                    <div v-else class="h-full min-h-[400px] flex flex-col items-center justify-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100 text-gray-300">
                        <CheckCircle class="w-16 h-16 mb-4 opacity-10" />
                        <p class="text-[10px] font-black uppercase tracking-widest">Selecciona un estudiante para calificar</p>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

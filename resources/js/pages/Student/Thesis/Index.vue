<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import {
    Plus, FileText, User, Users, Upload, CheckCircle, Eye,
    ArrowRight, BookOpen, ShieldCheck, GraduationCap, Clock
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue'; // Ajusta la ruta a tu layout real

const props = defineProps({
    project: Object
});

// Lógica para la Línea de Tiempo
const steps = [
    { id: 1, name: 'Registro' },
    { id: 2, name: 'Asesoría' },
    { id: 3, name: 'Citación' }, // Paso intermedio: cuando ya hay fecha
    { id: 4, name: 'Resultado' }, // Paso final: calificado
];

const currentStepIndex = computed(() => {
    if (!props.project) return 0;
    // Si ya tiene acta o está defendido, paso 4
    if (props.project.status === 'defended' || props.project.status === 'closed' || props.project.defense_act) return 4;
    // Si ya tiene fecha programada, paso 3
    if (props.project.scheduled_date) return 3;
    // Si tiene asesor, paso 2
    if (props.project.advisor_id) return 2;
    return 1;
});

const uploadForm = useForm({
    name: '',
    type: 'report',
    file: null
});

const submitDocument = () => {
    uploadForm.post(route('student.thesis.upload-document', props.project.id), {
        onSuccess: () => {
            uploadForm.reset();
            alert('Documento cargado al expediente digital.');
        }
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Mi Tesis / Grados" />

        <div class="p-4 md:p-8 max-w-7xl mx-auto bg-gray-50 min-h-screen">

            <!-- ESCENARIO: SIN PROYECTO (Igual al anterior pero con diseño del layout) -->
            <div v-if="!project" class="...">
                <!-- (Mantenemos tu bloque de registro aquí) -->
            </div>

            <!-- ESCENARIO: PROYECTO ACTIVO -->
            <div v-else class="space-y-8">

                <!-- 1. BARRA DE PROGRESO (ROADMAP) -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                        <div>
                            <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Ruta de Titulación</h2>
                            <p class="text-sm font-bold text-indigo-600 uppercase">Estado Actual: {{ project.status }}</p>
                        </div>
                        <div v-if="project.is_imported" class="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-[10px] font-black uppercase border border-amber-100">
                            Registro Migrado de Excel
                        </div>
                    </div>

                    <div class="relative flex justify-between items-center w-full max-w-4xl mx-auto">
                        <!-- Línea de fondo -->
                        <div class="absolute h-1 bg-gray-100 w-full top-1/2 -translate-y-1/2 z-0"></div>
                        <!-- Línea de progreso activa -->
                        <div class="absolute h-1 bg-indigo-500 transition-all duration-1000 top-1/2 -translate-y-1/2 z-0"
                             :style="{ width: ((currentStepIndex - 1) / (steps.length - 1)) * 100 + '%' }"></div>

                        <div v-for="step in steps" :key="step.id" class="relative z-10 flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 border-4"
                                 :class="currentStepIndex >= step.id ? 'bg-indigo-600 border-white text-white shadow-lg shadow-indigo-200' : 'bg-white border-gray-100 text-gray-300'">
                                <CheckCircle v-if="currentStepIndex > step.id" class="w-5 h-5" />
                                <component :is="currentStepIndex === step.id ? Clock : BookOpen" v-else class="w-4 h-4" />
                            </div>
                            <span class="mt-2 text-[10px] font-black uppercase tracking-tighter"
                                  :class="currentStepIndex >= step.id ? 'text-gray-900' : 'text-gray-300'">{{ step.name }}</span>
                        </div>
                    </div>
                </div>

                <!-- PANEL DE CITACIÓN (Solo si tiene fecha y no ha sustentado) -->
                <div v-if="project.scheduled_date && !project.defense_act" class="mb-8 p-8 bg-indigo-600 rounded-[3rem] shadow-2xl text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div>
                            <div class="inline-flex items-center px-3 py-1 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest mb-4">
                                🔔 Citación Oficial
                            </div>
                            <h2 class="text-3xl font-black uppercase tracking-tighter mb-2 italic">Fecha de Sustentación</h2>
                            <p class="text-indigo-100 text-sm font-medium">Su acto de sustentación ha sido programado. Por favor, asista puntualmente.</p>
                        </div>
                        <div class="flex flex-col items-center bg-white text-indigo-600 p-6 rounded-[2.5rem] shadow-xl min-w-[220px]">
                            <Calendar class="w-6 h-6 mb-2" />
                            <span class="text-2xl font-black">{{ new Date(project.scheduled_date).toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' }) }}</span>
                            <div class="flex items-center mt-2 space-x-2 text-indigo-400 font-bold">
                                <Clock class="w-4 h-4" />
                                <span>{{ project.scheduled_time.substring(0, 5) }} hrs</span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-indigo-50 w-full text-center">
                                <span class="text-[9px] font-black text-gray-400 uppercase block mb-1">Lugar / Aula</span>
                                <span class="text-xs font-bold text-gray-800 uppercase tracking-tighter">{{ project.scheduled_location || 'Aula Magna' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- COLUMNA IZQUIERDA: INFORMACIÓN Y EQUIPO -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Card Principal -->
                        <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-gray-100">
                            <h1 class="text-2xl font-black text-gray-900 uppercase leading-tight mb-4">{{ project.title }}</h1>
                            <div class="flex items-center space-x-2 text-indigo-500 font-bold text-xs">
                                <BookOpen class="w-4 h-4" />
                                <span class="uppercase tracking-widest">{{ project.research_line }}</span>
                            </div>

                            <!-- Equipo Académico -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10 pt-8 border-t border-gray-50">
                                <div>
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-widest">Tesistas Autorizados</h3>
                                    <div v-for="author in project.authors" :key="author.id" class="flex items-center p-3 bg-gray-50 rounded-2xl mb-2">
                                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3 text-indigo-500 shadow-sm">
                                            <User class="w-5 h-5" />
                                        </div>
                                        <span class="text-xs font-black text-gray-700 uppercase leading-tight">{{ author.full_name }}</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-widest">Docente Asesor</h3>
                                    <div v-if="project.advisor" class="flex items-center p-3 bg-indigo-50 rounded-2xl border border-indigo-100">
                                        <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center mr-3 text-white shadow-md">
                                            <CheckCircle class="w-5 h-5" />
                                        </div>
                                        <span class="text-xs font-black text-indigo-900 uppercase leading-tight">{{ project.advisor.full_name }}</span>
                                    </div>
                                    <div v-else class="p-4 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 text-center text-gray-400">
                                        <Clock class="w-6 h-6 mx-auto mb-2 opacity-20" />
                                        <span class="text-[10px] font-bold uppercase">Pendiente de Asignación</span>
                                    </div>
                                </div>
                            </div>

                            <!-- PANEL DE CALIFICACIONES (Solo si ya sustentó) -->
                            <div v-if="project.defense_act" class="mt-10 pt-8 border-t border-gray-100">
                                <h3 class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-6">Resultado Final de Evaluación</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Notas Individuales -->
                                    <div class="md:col-span-2 grid grid-cols-3 gap-3">
                                        <!-- Fíjate en el cambio: (campoBD, nombreVisible) -->
                                        <div v-for="(campoBD, nombreVisible) in { 'Presidente': 'score_president', 'Vocal': 'score_vocal', 'Secretario': 'score_secretary' }"
                                            :key="campoBD"
                                            class="bg-gray-50 p-4 rounded-3xl text-center border border-gray-100">

                                            <!-- Ahora sí, nombreVisible será 'Presidente' -->
                                            <span class="text-[8px] font-black text-gray-400 uppercase block mb-2">{{ nombreVisible }}</span>

                                            <!-- Y campoBD será 'score_president', por eso ya no saldrá 00 -->
                                            <span class="text-xl font-black text-gray-700">
                                                {{ project.defense_act[campoBD] ? Number(project.defense_act[campoBD]).toFixed(0) : '00' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Promedio Final -->
                                    <div class="bg-indigo-900 p-4 rounded-3xl text-center flex flex-col justify-center shadow-xl shadow-indigo-100">
                                        <span class="text-[8px] font-black text-indigo-300 uppercase block mb-1">Promedio General</span>
                                        <span class="text-3xl font-black text-white">
                                            {{ Number(project.defense_act.score).toFixed(2) }}
                                        </span>
                                        <span class="mt-2 text-[10px] font-black uppercase px-3 py-1 rounded-full inline-block mx-auto"
                                            :class="project.defense_act.result === 'aprobado' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
                                            {{ project.defense_act.result }}
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Card de Jurados (Solo si existen) -->
                        <div v-if="project.jurors?.length" class="bg-gray-900 p-8 rounded-[3rem] shadow-2xl text-white">
                            <h2 class="text-lg font-black uppercase mb-6 flex items-center">
                                <ShieldCheck class="mr-3 w-6 h-6 text-indigo-400" /> Jurado Calificador Designado
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div v-for="juror in project.jurors" :key="juror.id" class="bg-white/5 p-4 rounded-2xl border border-white/10 text-center">
                                    <span class="text-[8px] font-black text-indigo-300 uppercase block mb-1">{{ juror.role }}</span>
                                    <span class="text-[10px] font-bold uppercase leading-none">{{ juror.teacher.full_name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA DERECHA: EXPEDIENTE DIGITAL -->
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-gray-100">
                            <h2 class="text-lg font-black uppercase mb-6 flex items-center text-gray-900">
                                <Upload class="mr-3 w-5 h-5 text-indigo-600" /> Cargar Avances
                            </h2>

                            <form v-if="project.status !== 'defended' && project.status !== 'closed'" @submit.prevent="submitDocument" class="space-y-4 mb-8">
                                <input v-model="uploadForm.name" type="text" placeholder="Ej: Informe de Marzo" class="w-full border-gray-100 bg-gray-50 rounded-xl text-xs" required />
                                <select v-model="uploadForm.type" class="w-full border-gray-100 bg-gray-50 rounded-xl text-xs">
                                    <option value="report">Informe de Avance</option>
                                    <option value="final_draft">Borrador Final</option>
                                </select>
                                <input type="file" @input="uploadForm.file = $event.target.files[0]" accept=".pdf" class="w-full text-[10px] text-gray-400" required />
                                <button :disabled="uploadForm.processing" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-black text-[10px] uppercase hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                                    {{ uploadForm.processing ? 'Sincronizando...' : 'Enviar al Expediente' }}
                                </button>
                            </form>

                            <!-- Si ya terminó, mostramos un mensaje de bloqueo -->
                            <div v-else class="mb-8 p-6 bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl text-center">
                                <Lock class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                                <p class="text-[10px] font-black text-gray-400 uppercase">Expediente cerrado. Ya no se permiten nuevos informes.</p>
                            </div>

                            <div class="space-y-3">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b pb-2 mb-4">Documentos Subidos</h3>
                                <div v-for="doc in project.documents" :key="doc.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition group">
                                    <div class="flex items-center truncate">
                                        <FileText class="w-4 h-4 text-gray-400 mr-2 group-hover:text-red-500 transition" />
                                        <div class="truncate">
                                            <div class="text-[10px] font-bold text-gray-700 uppercase truncate">{{ doc.name }}</div>
                                            <div class="text-[8px] text-gray-400 uppercase">{{ new Date(doc.created_at).toLocaleDateString() }}</div>
                                        </div>
                                    </div>
                                    <a :href="'/storage/' + doc.file_path" target="_blank" class="p-2 text-indigo-500 hover:bg-indigo-100 rounded-lg transition">
                                        <Eye class="w-4 h-4" />
                                    </a>
                                </div>
                                <p v-if="!project.documents?.length" class="text-center text-[10px] text-gray-400 italic">No hay archivos aún.</p>
                            </div>
                        </div>

                        <!-- PANEL DE ACCIÓN SUGERIDA -->
                        <div class="bg-indigo-600 p-6 rounded-[2.5rem] shadow-xl text-white">
                            <h3 class="font-black uppercase text-sm mb-2">Próximo Paso:</h3>
                            <p class="text-xs text-indigo-100 leading-relaxed mb-4">
                                {{ currentStepIndex === 2 ? 'Debe coordinar con el Prof. ' + project.advisor.names + ' para la revisión de sus informes de avance.' : 'Siga las indicaciones de Secretaría Académica.' }}
                            </p>
                            <div class="flex justify-end">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center animate-bounce">
                                    <ArrowRight class="w-5 h-5 text-white" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Transiciones suaves para la barra de progreso */
.transition-all {
    transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>

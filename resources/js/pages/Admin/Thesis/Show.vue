<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import {
    Users,
    UserCheck,
    ShieldCheck,
    AlertCircle,
    Save,
    GraduationCap,
    Printer,
    Calendar,      // <--- FALTA ESTE
    FileText,      // <--- FALTA ESTE
    ClipboardList, // <--- FALTA ESTE
    Lock,          // <--- FALTA ESTE
    CheckCircle    // <--- FALTA ESTE
} from 'lucide-vue-next';

import { watch } from 'vue';

const props = defineProps({
    project: Object,
    teachers: Array // Docentes para elegir
});

const advisorForm = useForm({ advisor_id: props.project.advisor_id || '' });

// Formulario para los 3 Jurados
const jurorsForm = useForm({
    jurors: [
        { teacher_id: props.project.jurors?.find(j => j.role === 'presidente')?.teacher_id || '', role: 'presidente' },
        { teacher_id: props.project.jurors?.find(j => j.role === 'secretario')?.teacher_id || '', role: 'secretario' },
        { teacher_id: props.project.jurors?.find(j => j.role === 'vocal')?.teacher_id || '', role: 'vocal' },
    ]
});

const submitAdvisor = () => { advisorForm.patch(route('admin.thesis.assign-advisor', props.project.id)); };
const submitJurors = () => { jurorsForm.post(route('admin.thesis.assign-jurors', props.project.id)); };

const defenseForm = useForm({
    defense_date: props.project.defense_act?.defense_date || '',
    defense_time: props.project.defense_act?.defense_time || '',
    modality: props.project.defense_act?.modality || 'presencial',
    score: props.project.defense_act?.score || 0,
    result: props.project.defense_act?.result || 'pendiente',
    // Usamos || '' para que el input salga vacío y no con un cero inicial
    score_president: props.project.defense_act?.score_president || '',
    score_secretary: props.project.defense_act?.score_secretary || '',
    score_vocal: props.project.defense_act?.score_vocal || '',
});


const officialForm = useForm({
    type_of_research: props.project.type_of_research || '',
    promotion_year: props.project.promotion_year || '',
    specialty_resolution: props.project.specialty_resolution || '',
    document_correlative: props.project.document_correlative || '',
});

// --- NUEVO FORMULARIO PARA PROGRAMAR ---
const scheduleForm = useForm({
    // ASEGÚRATE DE QUE TENGA ESTOS VALORES EXACTOS:
    scheduled_date: props.project.scheduled_date || '',
    scheduled_time: props.project.scheduled_time || '',
    scheduled_location: props.project.scheduled_location || '',
});

// --- FUNCIÓN PARA GUARDAR LA CITA ---
const submitSchedule = () => {
    scheduleForm.patch(route('admin.thesis.schedule-defense', props.project.id), {
        preserveScroll: true,
        onSuccess: () => alert('✅ Sustentación programada y alumno notificado.')
    });
};

const submitOfficialData = () => {
    officialForm.patch(route('admin.thesis.update-official-data', props.project.id), {
        preserveScroll: true,
        onSuccess: () => alert('Datos oficiales guardados.')
    });
};

const submitDefense = () => {
    defenseForm.post(route('admin.thesis.record-defense', props.project.id), {
        preserveScroll: true,
        onSuccess: () => alert('Resultado de sustentación registrado con éxito.')
    });
};

watch(
    () => [defenseForm.score_president, defenseForm.score_vocal, defenseForm.score_secretary],
    ([p, v, s]) => {
        const n1 = parseFloat(p) || 0;
        const n2 = parseFloat(v) || 0;
        const n3 = parseFloat(s) || 0;

        if (n1 === 0 && n2 === 0 && n3 === 0) {
            defenseForm.score = 0;
            defenseForm.result = 'pendiente';
            return;
        }

        // Promedio matemático puro
        const avg = (n1 + n2 + n3) / 3;

        // Guardamos el promedio con 2 decimales para el acta
        defenseForm.score = Number(avg.toFixed(2));

        // REGLA: 14.00 es la valla. 13.99 es desaprobado.
        defenseForm.result = avg >= 14 ? 'aprobado' : 'desaprobado';
    }
);

</script>

<template>
    <Head title="Gestionar Tesis" />

    <!-- Contenedor Principal -->
    <div class="p-8 max-w-6xl mx-auto space-y-8">
        <h1 class="text-3xl font-black text-gray-900 uppercase">Expediente de Grado</h1>

        <!-- 1. MENSAJES DE FEEDBACK -->
        <div v-if="$page.props.flash.success" class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded shadow-sm animate-bounce">
            ✅ {{ $page.props.flash.success }}
        </div>

        <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-bold rounded shadow-sm">
            <p>⚠️ Hubo problemas con el registro:</p>
            <ul class="list-disc ml-5 text-xs font-medium">
                <li v-for="error in $page.props.errors" :key="error">{{ error }}</li>
            </ul>
        </div>

        <!-- 2. REJILLA DE GESTIÓN -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- COLUMNA IZQUIERDA: INFORMACIÓN (Ocupa 1/3) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Información General -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h2 class="text-xs font-black text-gray-400 uppercase mb-4 tracking-widest">Información General</h2>
                    <p class="font-bold text-gray-800 uppercase leading-tight">{{ project.title }}</p>
                    <div class="mt-4 space-y-2 text-[10px] font-bold uppercase text-gray-500">
                        <div class="text-indigo-600 italic">{{ project.research_line }}</div>
                        <div v-for="author in project.authors" :key="author.id">Tesista: {{ author.full_name }}</div>
                    </div>
                </div>

                <!-- Panel de Datos Oficiales MINEDU (Moverlo aquí hace que el diseño no se rompa) -->
                <div class="bg-amber-50 p-6 rounded-3xl shadow-xl border border-amber-200">
                    <h2 class="font-black text-amber-900 uppercase text-sm mb-4 flex items-center">
                        <FileText class="mr-2 w-4 h-4 text-amber-600" /> Datos Oficiales (Automáticos)
                    </h2>
                    <div class="space-y-3">
                        <!-- Mostramos la data que el sistema ya calculó -->
                        <div>
                            <span class="text-[9px] font-black text-amber-700 uppercase block">Resolución del Plan</span>
                            <p class="text-xs font-bold text-amber-900">{{ project.auto_resolution }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-amber-700 uppercase block">Año de Promoción</span>
                            <p class="text-xs font-bold text-amber-900">{{ project.auto_promotion || 'Sincronizando...' }}</p>
                        </div>
                        <div class="pt-2 border-t border-amber-200">
                            <label class="text-[9px] font-black text-amber-700 uppercase">N° Oficio Correlativo</label>
                            <div class="flex items-center space-x-2">
                                <input v-model="officialForm.document_correlative" type="text" placeholder="Nro-Año" class="w-full border-amber-200 rounded-xl text-xs bg-white" />
                            </div>
                            <p class="text-[8px] text-amber-600 mt-1 italic">* El número se genera solo al asignar jurados, pero puede editarlo aquí.</p>
                        </div>
                        <button @click="submitOfficialData" class="w-full bg-amber-600 text-white py-2 rounded-xl font-black text-[10px] uppercase">
                            {{ officialForm.processing ? 'GUARDANDO...' : 'ACTUALIZAR CORRELATIVO' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: AUTORIDADES (Ocupa 2/3) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Panel Asesor -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100">
                    <h2 class="font-black text-gray-900 uppercase text-lg mb-6 flex items-center">
                        <UserCheck class="mr-2 w-5 h-5 text-blue-600" /> Docente Asesor
                    </h2>
                    <form @submit.prevent="submitAdvisor" class="flex gap-4">
                        <select v-model="advisorForm.advisor_id" class="flex-1 border-gray-200 rounded-2xl text-xs">
                            <option value="">Seleccione al asesor...</option>
                            <option v-for="t in teachers" :key="t.id" :value="t.id">{{ t.full_name }}</option>
                        </select>
                        <button class="bg-gray-900 text-white px-6 rounded-2xl font-bold text-xs uppercase hover:bg-blue-600 transition">Asignar</button>
                    </form>
                </div>

                <!-- Panel Jurados -->
                <div class="bg-indigo-900 p-8 rounded-[2.5rem] shadow-2xl text-white">
                    <h2 class="font-black uppercase text-lg mb-6 flex items-center">
                        <ShieldCheck class="mr-2 w-6 h-6 text-indigo-400" /> Jurado Calificador
                    </h2>
                    <form @submit.prevent="submitJurors" class="space-y-4">
                        <div v-for="(juror, index) in jurorsForm.jurors" :key="index" class="grid grid-cols-3 items-center gap-4">
                            <span class="text-[10px] font-black uppercase opacity-60">{{ juror.role }}</span>
                            <select v-model="juror.teacher_id" class="col-span-2 bg-white/10 border-white/20 rounded-xl text-xs text-white focus:bg-white focus:text-gray-900">
                                <option value="" class="text-gray-900">Seleccione docente...</option>
                                <option v-for="t in teachers" :key="t.id" :value="t.id" class="text-gray-900">{{ t.full_name }}</option>
                            </select>
                        </div>
                        <button :disabled="jurorsForm.processing" class="w-full mt-4 bg-white text-indigo-900 py-4 rounded-2xl font-black uppercase text-xs shadow-xl hover:bg-indigo-50 transition">
                            {{ jurorsForm.processing ? 'PROCESANDO...' : 'OFICIALIZAR JURADO' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- PASO 3: PROGRAMACIÓN DE SUSTENTACIÓN (Solo si ya hay 3 jurados) -->
        <div v-if="project.jurors.length === 3" class="bg-blue-50 p-8 rounded-[2.5rem] shadow-xl border border-blue-100 mt-8">
            <h2 class="font-black text-blue-900 uppercase text-lg mb-6 flex items-center">
                <Calendar class="mr-2 w-6 h-6" /> Programar Sustentación
            </h2>

            <form @submit.prevent="submitSchedule" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-blue-400 uppercase mb-1">Fecha Programada</label>
                        <input v-model="scheduleForm.scheduled_date" type="date" class="w-full border-blue-200 rounded-xl text-xs focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-400 uppercase mb-1">Hora</label>
                        <input v-model="scheduleForm.scheduled_time" type="time" class="w-full border-blue-200 rounded-xl text-xs focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-400 uppercase mb-1">Lugar / Aula</label>
                        <input v-model="scheduleForm.scheduled_location" type="text" placeholder="Ej: Aula Magna / Meet" class="w-full border-blue-200 rounded-xl text-xs focus:ring-blue-500" />
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-blue-100">
                    <!-- El Oficio de citación se puede imprimir DESDE QUE SE PROGRAMA -->
                    <a :href="route('admin.thesis.download-oficio', project.id)" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl font-bold text-[10px] uppercase shadow-md hover:bg-blue-700 transition">
                        <Printer class="w-4 h-4 mr-2" /> Generar Oficio de Citación
                    </a>

                    <button :disabled="scheduleForm.processing" class="bg-blue-900 text-white px-8 py-2 rounded-xl font-black text-[10px] uppercase shadow-lg hover:bg-black transition">
                        {{ project.scheduled_date ? 'ACTUALIZAR CITA' : 'CONFIRMAR PROGRAMACIÓN' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- PASO 4: CALIFICACIÓN FINAL (Solo si ya se programó la fecha) -->
        <div v-if="project.scheduled_date" class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-indigo-100 mt-8">
            <h2 class="font-black text-gray-900 uppercase text-lg mb-6 flex items-center">
                <GraduationCap class="mr-2 w-6 h-6 text-indigo-600" /> Acta de Sustentación Final
            </h2>

            <form @submit.prevent="submitDefense" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Notas Individuales del Jurado -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-3 text-center tracking-widest">Calificaciones del Jurado</label>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="text-center">
                                <span class="text-[8px] font-bold text-gray-400 uppercase">Presid.</span>
                                <input v-model="defenseForm.score_president" :disabled="project.status === 'defended' || project.status === 'closed'" type="number" step="0.1" class="w-full border-gray-200 rounded-xl text-center font-bold" />
                            </div>
                            <div class="text-center">
                                <span class="text-[8px] font-bold text-gray-400 uppercase">Vocal</span>
                                <input v-model="defenseForm.score_vocal" :disabled="project.status === 'defended' || project.status === 'closed'" type="number" step="0.1" class="w-full border-gray-200 rounded-xl text-center font-bold" />
                            </div>
                            <div class="text-center">
                                <span class="text-[8px] font-bold text-gray-400 uppercase">Secret.</span>
                                <input v-model="defenseForm.score_secretary" :disabled="project.status === 'defended' || project.status === 'closed'" type="number" step="0.1" class="w-full border-gray-200 rounded-xl text-center font-bold" />
                            </div>
                        </div>
                    </div>

                    <!-- Resultado Automático -->
                    <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100 flex flex-col justify-center items-center">
                        <label class="text-[9px] font-black text-indigo-400 uppercase mb-1">Promedio General</label>
                        <div class="text-4xl font-black text-indigo-700">{{ defenseForm.score || '0.00' }}</div>
                        <div class="text-[10px] font-bold mt-2 uppercase px-4 py-1 rounded-full border"
                            :class="defenseForm.result === 'aprobado' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200'">
                            {{ defenseForm.result }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-50 items-center">

                    <!-- Botones de Documentos Finales (Se mantienen igual) -->
                    <template v-if="project.defense_act">
                        <a :href="route('admin.thesis.download-nomina', project.id)" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-xl font-bold text-[10px] uppercase shadow-md hover:bg-black transition">
                            <ClipboardList class="w-4 h-4 mr-2" /> Nómina Expeditos
                        </a>
                        <a :href="route('admin.thesis.download-acta-titulacion', project.id)" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl font-bold text-[10px] uppercase shadow-md hover:bg-green-700 transition">
                            <CheckCircle class="w-4 h-4 mr-2" /> Descargar Acta
                        </a>
                    </template>

                    <!-- CAMBIO AQUÍ: Lógica del Botón vs Mensaje de Bloqueo -->

                    <!-- 1. Si el proyecto NO está defendido ni cerrado, mostramos el botón de guardar -->
                    <button v-if="project.status !== 'defended' && project.status !== 'closed'"
                            :disabled="defenseForm.processing"
                            class="bg-indigo-600 text-white px-10 py-2 rounded-xl font-black text-[10px] uppercase shadow-lg hover:bg-indigo-700 transition">
                        {{ defenseForm.processing ? 'GUARDANDO...' : 'REGISTRAR RESULTADO' }}
                    </button>

                    <!-- 2. Si el proyecto YA TIENE NOTA FINAL, mostramos este aviso -->
                    <div v-else class="flex items-center space-x-2 text-green-700 bg-green-50 px-4 py-2 rounded-xl border border-green-200 font-black text-[10px] uppercase tracking-widest shadow-sm">
                        <Lock class="w-3.5 h-3.5" />
                        <span>Expediente Finalizado e Inmutable</span>
                    </div>
                </div>
            </form>
        </div>

    </div> <!-- FIN CONTENEDOR PRINCIPAL -->
</template>

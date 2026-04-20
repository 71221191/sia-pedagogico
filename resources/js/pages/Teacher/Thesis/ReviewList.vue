<script setup>

import { Head, Link, usePage, router } from '@inertiajs/vue3';

import {
    FileText,
    Eye,
    Users,
    UserCheck,
    ShieldCheck,
    Clock,
    GraduationCap,
    BookOpen,
    Save // <--- ESTA ES LA QUE FALTA
} from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';

import { computed, ref } from 'vue';

const props = defineProps({
    projects: Array
});

const page = usePage();
// Obtenemos el ID de la persona logueada
const teacherId = computed(() => {
    // Intentamos sacarlo de diferentes lugares por si acaso
    return page.props.auth.user.person_id || page.props.auth.user.id;
});


// FUNCIÓN DE ROL MEJORADA: Ahora detecta el cargo específico
const getTeacherRole = (project) => {
    // 1. Limpiamos y aseguramos que el ID sea un número para comparar
    const myId = Number(teacherId.value);

    // 2. ¿Es el Asesor?
    if (Number(project.advisor_id) === myId) return 'Asesor Principal';

    // 3. ¿Es parte del Jurado?
    // Buscamos ignorando mayúsculas y tipos de datos
    const jurorInfo = project.jurors?.find(j => Number(j.teacher_id) === myId);

    if (jurorInfo) {
        // Ponemos la primera letra en mayúscula para que se vea bien
        const roleName = jurorInfo.role.charAt(0).toUpperCase() + jurorInfo.role.slice(1);
        return 'Jurado: ' + roleName;
    }

    return 'Revisor';
};

const getStatusBadge = (status) => {
    const styles = {
        'approved': 'bg-green-100 text-green-700 border-green-200',
        'registered': 'bg-blue-100 text-blue-700 border-blue-200',
        'defended': 'bg-purple-100 text-purple-700 border-purple-200',
    };
    return styles[status] || 'bg-gray-100 text-gray-700 border-gray-200';
};

// Creamos un objeto reactivo para guardar las notas temporales mientras el profe escribe
const scores = ref({});

// Inicializamos las notas con lo que viene de la DB
props.projects.forEach(p => {
    const myJuror = p.jurors.find(j => j.teacher_id == teacherId.value);
    scores.value[p.id] = myJuror ? myJuror.score : 0;
});

const saveScore = (projectId) => {
    router.patch(route('teacher.thesis-review.update-score', projectId), {
        score: scores.value[projectId]
    }, {
        preserveScroll: true,
        onSuccess: () => alert('Nota guardada correctamente')
    });
};

</script>

<template>
    <AppLayout>
        <Head title="Revisión de Tesis" />

        <div class="p-4 md:p-8 max-w-5xl mx-auto bg-gray-50 min-h-screen">
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter flex justify-center items-center">
                    <GraduationCap class="mr-3 w-10 h-10 text-indigo-600" />
                    Bandeja de Revisión
                </h1>

                <p class="text-gray-500 font-medium italic mt-2">
                    Visualización de expedientes y documentos para Asesores y Miembros del Jurado.
                </p>
            </div>

            <div v-if="projects.length > 0" class="space-y-8">
                <!-- DISEÑO DE LISTA ANCHA (MEJOR PARA TÍTULOS LARGOS) -->
                <div v-for="project in projects" :key="project.id"
                     class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden flex flex-col transition hover:shadow-2xl">

                    <div class="p-8">
                        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                            <!-- Cargo Específico del Docente -->
                            <div class="flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest border shadow-sm"
                                 :class="project.advisor_id === teacherId ? 'bg-indigo-600 text-white border-indigo-700' : 'bg-amber-50 text-amber-700 border-amber-200'">
                                <component :is="project.advisor_id === teacherId ? UserCheck : ShieldCheck" class="w-4 h-4 mr-2" />
                                {{ getTeacherRole(project) }}
                            </div>

                            <!-- Estado de la Tesis -->
                            <span :class="getStatusBadge(project.status)" class="px-4 py-1.5 rounded-full text-xs font-black uppercase border">
                                Estado: {{ project.status }}
                            </span>
                        </div>

                        <!-- TÍTULO SIN RECORTE: Ahora puede ocupar todo el espacio necesario -->
                        <h2 class="text-2xl font-black text-gray-900 leading-tight mb-6 uppercase border-l-8 border-indigo-500 pl-6">
                            {{ project.title }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-gray-50">
                            <!-- EQUIPO -->
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Tesistas a Cargo</h3>
                                    <div class="flex flex-col gap-2">
                                        <div v-for="author in project.authors" :key="author.id" class="flex items-center text-sm font-bold text-gray-700 uppercase">
                                            <div class="w-2 h-2 bg-indigo-400 rounded-full mr-3"></div>
                                            {{ author.last_name_p }} {{ author.last_name_m }}, {{ author.names }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección de Calificación para el Jurado -->
                                <div v-if="getTeacherRole(project).includes('Jurado')" class="mt-6 p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-[10px] font-black text-indigo-600 uppercase block leading-none mb-1">Mi Calificación</span>
                                            <span class="text-[9px] text-indigo-400 italic">Como {{ getTeacherRole(project).replace('Jurado: ', '') }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <!-- CAMBIO 1: Agregamos el v-model amarrado al ID del proyecto -->
                                            <input type="number"
                                                v-model="scores[project.id]"
                                                step="1" min="0" max="20"
                                                placeholder="00"
                                                class="w-16 text-center font-black text-lg text-indigo-700 border-none bg-white rounded-xl focus:ring-2 focus:ring-indigo-500 shadow-sm" />

                                            <!-- CAMBIO 2: Agregamos el evento click para llamar a la función de guardado -->
                                            <button @click="saveScore(project.id)"
                                                    class="bg-indigo-600 text-white p-2.5 rounded-xl hover:bg-indigo-700 transition shadow-md active:scale-95">
                                                <Save class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado del Jurado Completo -->
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <h4 class="text-[9px] font-black text-gray-400 uppercase mb-3 tracking-widest text-center">Estado del Quórum de Calificación</h4>
                                    <div class="flex justify-around">
                                        <div v-for="juror in project.jurors" :key="juror.id" class="text-center">
                                            <div class="w-2 h-2 rounded-full mx-auto mb-1"
                                                :class="juror.score ? 'bg-green-500 shadow-sm shadow-green-200' : 'bg-gray-200 animate-pulse'"></div>
                                            <span class="text-[8px] font-bold uppercase" :class="juror.score ? 'text-gray-700' : 'text-gray-300'">
                                                {{ juror.role }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Línea: {{ project.research_line }}</span>
                                </div>
                            </div>

                            <!-- NUEVO BLOQUE: INFORMACIÓN DE SUSTENTACIÓN PARA EL DOCENTE -->
                            <div v-if="project.scheduled_date" class="mb-6 p-5 bg-indigo-900 rounded-3xl text-white shadow-xl relative overflow-hidden">
                                <!-- Decoración de fondo -->
                                <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -mr-10 -mt-10"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-indigo-300">📅 Cita Programada</span>
                                        <!-- Badge de urgencia si es pronto -->
                                        <span v-if="project.status === 'scheduled'" class="bg-amber-400 text-amber-900 text-[8px] font-black px-2 py-0.5 rounded-full uppercase animate-pulse">
                                            Próximamente
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="text-center bg-white/10 p-2 rounded-xl border border-white/10">
                                                <span class="block text-[8px] font-black uppercase opacity-60">Día</span>
                                                <span class="text-xl font-black">{{ new Date(project.scheduled_date).getDate() + 1 }}</span>
                                            </div>
                                            <div>
                                                <p class="text-xs font-black uppercase">{{ new Date(project.scheduled_date).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' }) }}</p>
                                                <p class="text-[10px] text-indigo-200">Hora: {{ project.scheduled_time.substring(0, 5) }} hrs</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="block text-[8px] font-black uppercase opacity-60">Ubicación</span>
                                            <span class="text-xs font-bold uppercase tracking-tighter">{{ project.scheduled_location }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DOCUMENTOS -->
                            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 flex items-center">
                                    <FileText class="w-3 h-3 mr-2" /> Documentos del Expediente
                                </h3>

                                <div class="space-y-2">
                                    <div v-for="doc in project.documents" :key="doc.id"
                                         class="flex items-center justify-between p-3 bg-white rounded-2xl border border-gray-100 hover:border-indigo-400 transition group shadow-sm">
                                        <div class="flex items-center truncate">
                                            <FileText class="w-4 h-4 text-red-400 mr-3 group-hover:scale-110 transition" />
                                            <div class="truncate">
                                                <div class="text-[10px] font-bold text-gray-800 uppercase truncate">{{ doc.name }}</div>
                                                <div class="text-[8px] text-gray-400 font-mono italic">Publicado el {{ new Date(doc.created_at).toLocaleDateString() }}</div>
                                            </div>
                                        </div>
                                        <a :href="'/storage/' + doc.file_path" target="_blank"
                                           class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                                            <Eye class="w-4 h-4" />
                                        </a>
                                    </div>

                                    <div v-if="!project.documents?.length" class="text-center py-4 text-gray-400">
                                        <p class="text-[10px] font-bold uppercase italic">Esperando carga de archivos...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ESTADO VACÍO -->
            <div v-else class="text-center py-24 bg-white rounded-[3rem] shadow-sm border-2 border-dashed border-gray-200">
                <BookOpen class="w-16 h-16 mx-auto text-gray-200 mb-4" />
                <h3 class="text-xl font-bold text-gray-400 uppercase">No hay proyectos asignados</h3>
                <p class="text-gray-400 text-sm italic">Usted no figura como asesor o jurado en el ciclo actual.</p>
            </div>
        </div>
    </AppLayout>
</template>

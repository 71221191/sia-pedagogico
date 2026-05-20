<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import {
    Calendar, Plus, Edit, Trash2, CheckCircle2,
    AlertCircle, BookOpen, UserCircle, XCircle, Zap,
    ChevronRight, Users, Layers, ClipboardList, BarChart3   // <--- ESTOS FALTABAN
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';

// 2. Variables para el Modal de Nómina
const isNominaModalOpen = ref(false);
const nominaForm = ref({
    sectionId: null as number | null,
    rdr: 'RDR N° 3068-2025- DREC', // Valor por defecto que me dijiste
    rdr_encargatura: 'RDR N° 3260-2024-DREC', // Valor del PDF
    fecha_cierre: new Date().toISOString().split('T')[0], // Fecha de hoy por defecto
});

// 3. Función para abrir el modal
const openNominaModal = (sectionId: number) => {
    nominaForm.value.sectionId = sectionId;
    isNominaModalOpen.value = true;
};

// 4. Función para lanzar la descarga
const downloadNomina = () => {
    if (!nominaForm.value.sectionId) return;

    // Construimos la URL con los parámetros
    const url = route('admin.reports.nomina', {
        courseSection: nominaForm.value.sectionId,
        rdr: nominaForm.value.rdr,
        rdr_encargatura: nominaForm.value.rdr_encargatura,
        fecha_cierre: nominaForm.value.fecha_cierre
    });

    // Abrimos en una pestaña nueva para que se descargue el PDF
    window.open(url, '_blank');
    isNominaModalOpen.value = false;
};

// --- Función de conversión de Arábigos a Romanos ---
const toRoman = (num: string | number | null): string => {
    if (num === null) return 'N/A';
    const n = typeof num === 'string' ? parseInt(num, 10) : num;
    if (isNaN(n) || n <= 0 || n > 10) return String(num);
    const romanMap: Record<number, string> = {
        1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V',
        6: 'VI', 7: 'VII', 8: 'VIII', 9: 'IX', 10: 'X'
    };
    return romanMap[n] || String(num);
};

const props = defineProps<{
    courseSections: {
        data: any[];
        links: any[];
        total: number;
    };
    flash: { success?: string; error?: string };
    academicPeriods: any[];
    studyPlans: any[];
}>();

// --- LÓGICA DE AGRUPAMIENTO ---
const groupedData = computed(() => {
    const groups: any = {};

    // Agregamos el signo "?" y verificamos que exista data
    const data = props.courseSections?.data || [];

    data.forEach(section => {
        const period = section.academic_period?.name || 'Sin Periodo';
        const program = section.course?.study_plan?.study_program?.name || 'Sin Programa';
        const cycle = section.course?.cycle || '1';
        const sectionKey = `${section.name}-${section.shift_id}`;

        if (!groups[period]) groups[period] = {};
        if (!groups[period][program]) groups[period][program] = {};
        if (!groups[period][program][cycle]) groups[period][program][cycle] = {};
        if (!groups[period][program][cycle][sectionKey]) {
            groups[period][program][cycle][sectionKey] = [];
        }

        groups[period][program][cycle][sectionKey].push(section);
    });

    return groups;
});

const deleteForm = useForm({});
const deleteCourseSection = (sectionId: number, sectionName: string | number, courseName: string) => {
    const sectionLabel = String(sectionName);

    if (confirm(`¿Estás seguro de que quieres ELIMINAR la sección "${sectionLabel}" del curso "${courseName}"?`)) {
        deleteForm.delete(route('admin.course_sections.destroy', sectionId), {
            preserveScroll: true
        });
    }
};

const filterForm = ref({ academic_period_id: '', study_plan_id: '' });

// Objeto para controlar qué programas están expandidos
const expandedPrograms = ref<Record<string, boolean>>({});

// Función para contar secciones y cursos por programa
const getProgramSummary = (cycles: any) => {
    let totalSections = 0;
    let totalCourses = 0;
    let assignedTeachers = 0;

    Object.values(cycles).forEach((sections: any) => {
        totalSections += Object.keys(sections).length;
        Object.values(sections).forEach((courses: any) => {
            totalCourses += courses.length;
            assignedTeachers += courses.filter((c: any) => c.teacher).length;
        });
    });

    return { totalSections, totalCourses, assignedTeachers };
};

const toggleProgram = (programName: string | number) => {
    // Forzamos a que se trate como string al usarlo como llave del objeto
    const key = String(programName);
    expandedPrograms.value[key] = !expandedPrograms.value[key];
};

// Función opcional para abrir/cerrar todos a la vez
const toggleAll = (action: boolean) => {
    Object.keys(groupedData.value).forEach(period => {
        Object.keys(groupedData.value[period]).forEach(prog => {
            expandedPrograms.value[prog] = action;
        });
    });
};

const goToAssignment = () => {
    router.get(route('admin.course_sections.teacher-assignment'), {
        academic_period_id: filterForm.value.academic_period_id,
        study_plan_id: filterForm.value.study_plan_id
    });
};
</script>

<template>
    <Head title="Secciones de Cursos" />
    <AppLayout>
        <div class="min-h-screen bg-gray-50/50 py-8 px-4 sm:px-6 lg:px-8 font-sans">
            <div class="max-w-[1600px] mx-auto">

                <!-- ENCABEZADO MODERNO -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
                    <div>
                        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase italic">Control de Secciones</h1>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mt-1">Organización por Cohortes Académicas</p>
                    </div>
                    <div class="flex gap-3">
                        <!-- NUEVO BOTÓN: CUADRO ESTADÍSTICO -->
                        <a :href="route('admin.reports.cuadro-estadistico', { academic_period_id: filterForm.academic_period_id || 1 })"
                        target="_blank"
                        class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white text-xs font-black uppercase rounded-xl shadow-xl shadow-emerald-100 hover:bg-emerald-700 transition-all">
                            <BarChart3 class="w-4 h-4 mr-2" />
                            Cuadro Estadístico
                        </a>
                        <Link :href="route('admin.course_sections.create')"
                              class="inline-flex items-center px-5 py-2.5 bg-gray-900 text-white text-xs font-black uppercase rounded-xl shadow-xl hover:bg-indigo-600 transition-all">
                            <Plus class="w-4 h-4 mr-2" /> Nueva Sección
                        </Link>
                        <Link :href="route('admin.course_sections.bulk-create')"
                            class="inline-flex items-center px-5 py-2.5 bg-yellow-400 text-yellow-950 text-xs font-black uppercase rounded-xl shadow-xl hover:bg-yellow-500 transition-all">
                            <Zap class="w-4 h-4 mr-2" /> Apertura Rápida
                        </Link>

                    </div>
                </div>

                <!-- FILTROS -->
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 mb-8 flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[250px]">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Asignación Directa de Docentes</label>
                        <div class="flex gap-3">
                            <select v-model="filterForm.academic_period_id" class="flex-1 border-gray-200 rounded-xl text-xs font-bold focus:ring-indigo-500">
                                <option value="">Seleccionar Periodo...</option>
                                <option v-for="p in academicPeriods" :key="p.id" :value="p.id">{{ p.name }}</option>
                            </select>
                            <select v-model="filterForm.study_plan_id" class="flex-1 border-gray-200 rounded-xl text-xs font-bold focus:ring-indigo-500">
                                <option value="">Seleccionar Plan...</option>
                                <option v-for="plan in studyPlans" :key="plan.id" :value="plan.id">
                                    {{ plan.study_program.name }} ({{ plan.name }})
                                </option>
                            </select>
                        </div>
                    </div>
                    <button @click="goToAssignment" :disabled="!filterForm.academic_period_id || !filterForm.study_plan_id"
                            class="px-8 py-3 bg-indigo-50 text-indigo-600 rounded-xl font-black text-[10px] uppercase hover:bg-indigo-600 hover:text-white disabled:opacity-30 transition-all">
                        Abrir Sábana de Carga Académica
                    </button>
                </div>

                <!-- RENDERIZADO POR GRUPOS -->
                <div v-for="(programs, periodName) in groupedData" :key="periodName" class="mb-12">
                    <div class="flex items-center justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4 flex-1">
                            <h2 class="text-3xl font-black text-gray-800 tracking-tighter">{{ periodName }}</h2>
                            <div class="h-[2px] flex-1 bg-gray-200"></div>
                        </div>
                        <div class="flex gap-2">
                            <button @click="toggleAll(true)" class="text-[9px] font-black uppercase text-gray-400 hover:text-indigo-600 transition-colors">Expandir todo</button>
                            <span class="text-gray-200">|</span>
                            <button @click="toggleAll(false)" class="text-[9px] font-black uppercase text-gray-400 hover:text-indigo-600 transition-colors">Contraer todo</button>
                        </div>
                    </div>

                    <div v-for="(cycles, programName) in programs" :key="programName" class="mb-4">
                        <button
                            @click="toggleProgram(programName)"
                            class="w-full flex items-center justify-between p-6 bg-white rounded-[2rem] border-2 border-transparent hover:border-indigo-100 transition-all shadow-sm hover:shadow-md group"
                            :class="{'border-indigo-200 bg-indigo-50/20': expandedPrograms[programName]}"
                        >
                            <div class="flex items-center gap-4">
                                <div class="bg-indigo-600 text-white p-3 rounded-2xl group-hover:rotate-6 transition-transform">
                                    <BookOpen class="w-5 h-5" />
                                </div>
                                <div class="text-left">
                                    <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight">{{ programName }}</h3>
                                    <div class="flex items-center gap-4 mt-1">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ Object.keys(cycles).length }} Ciclos</p>
                                        <span class="text-gray-200">•</span>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ getProgramSummary(cycles).totalSections }} Secciones</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div v-if="!expandedPrograms[programName]" class="hidden md:flex flex-col items-end gap-1">
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[9px] font-black uppercase">
                                        {{ getProgramSummary(cycles).assignedTeachers }}/{{ getProgramSummary(cycles).totalCourses }} Docentes
                                    </span>
                                </div>
                                <ChevronRight class="w-6 h-6 text-gray-300 transition-transform" :class="{'rotate-90 text-indigo-600': expandedPrograms[programName]}" />
                            </div>
                        </button>

                        <transition
                            enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="transform opacity-0 -translate-y-4"
                            enter-to-class="transform opacity-100 translate-y-0"
                        >
                            <div v-if="expandedPrograms[programName]" class="mt-6 ml-4 md:ml-12 space-y-8 pb-8">
                                <div v-for="(sections, cycleNum) in cycles" :key="cycleNum">
                                    <div class="flex items-center gap-3 mb-6">
                                        <span class="bg-gray-900 text-white px-4 py-1 rounded-full text-[10px] font-black italic uppercase">Ciclo {{ toRoman(cycleNum) }}</span>
                                        <div class="h-[1px] flex-1 bg-gray-100"></div>
                                    </div>

                                    <!-- GRID DE TARJETAS DE SECCIÓN (2 COLUMNAS PARA QUE SEAN ANCHAS) -->
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                        <div v-for="(courses, sectionLabel) in sections" :key="sectionLabel"
                                             class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl p-6 hover:shadow-2xl transition-all relative overflow-hidden group">

                                            <div class="flex justify-between items-center mb-6 bg-indigo-50/30 p-5 -mx-6 -mt-6 border-b border-indigo-100/50">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative">
                                                        <div class="bg-indigo-600 text-white w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg">
                                                            <!-- Mostramos solo la letra (ej: 'A') -->
                                                            <h5 class="text-3xl font-black">{{ courses[0].name }}</h5>
                                                        </div>
                                                        <!-- ICONO DE TURNO FLOTANTE -->
                                                        <div class="absolute -top-2 -right-2 w-7 h-7 rounded-full flex items-center justify-center shadow-md border-2 border-white"
                                                            :class="courses[0].shift_id === 1 ? 'bg-yellow-400' : 'bg-indigo-900'">
                                                            <Sun v-if="courses[0].shift_id === 1" class="w-3.5 h-3.5 text-white" />
                                                            <Moon v-else class="w-3.5 h-3.5 text-white" />
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest block mb-1">
                                                            Turno {{ courses[0].shift_id === 1 ? 'Mañana' : 'Tarde' }}
                                                        </span>
                                                        <span class="text-[9px] font-bold text-gray-400 uppercase">{{ courses.length }} Cursos</span>
                                                    </div>
                                                </div>

                                                <!-- LADO DERECHO: Botones de Acción -->
                                                <div class="flex items-center gap-3">
                                                    <!-- Botón para Nómina (Verde) -->
                                                    <button @click="openNominaModal(courses[0]?.id)"
                                                            class="flex flex-col items-center gap-1 group/btn_nom">
                                                        <div class="bg-white text-emerald-600 p-3.5 rounded-2xl shadow-sm border border-emerald-100 group-hover/btn_nom:bg-emerald-600 group-hover/btn_nom:text-white transition-all transform group-hover/btn_nom:scale-110">
                                                            <ClipboardList class="w-6 h-6" />
                                                        </div>
                                                        <span class="text-[8px] font-black text-emerald-400 uppercase tracking-widest">Nómina</span>
                                                    </button>

                                                    <!-- Botón para Horario (Azul) -->
                                                    <Link :href="route('admin.course_sections.schedule.edit', courses[0]?.id)"
                                                        class="flex flex-col items-center gap-1 group/btn">
                                                        <div class="bg-white text-indigo-600 p-3.5 rounded-2xl shadow-sm border border-indigo-100 group-hover/btn:bg-indigo-600 group-hover/btn:text-white transition-all transform group-hover/btn:scale-110">
                                                            <Calendar class="w-6 h-6" />
                                                        </div>
                                                        <span class="text-[8px] font-black text-indigo-400 uppercase tracking-widest">Horario</span>
                                                    </Link>
                                                </div>
                                            </div>

                                            <div class="space-y-4 mb-6">
                                                <div v-for="c in courses" :key="c.id" class="flex items-start justify-between group/item border-b border-gray-50 pb-3 last:border-0">
                                                    <div class="flex-1 pr-4">
                                                        <p class="text-[11px] font-black text-gray-800 uppercase leading-tight break-words">{{ c.course.name }}</p>
                                                        <p class="text-[10px] font-bold mt-1" :class="c.teacher ? 'text-emerald-600' : 'text-rose-500'">
                                                            {{ c.teacher?.full_name || 'Falta Docente' }}
                                                        </p>
                                                    </div>
                                                    <div class="flex gap-1 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                                        <Link :href="route('admin.course_sections.edit', c.id)" class="p-1 text-gray-400 hover:text-indigo-600"><Edit class="w-4 h-4"/></Link>
                                                        <button @click="deleteCourseSection(c.id, sectionLabel, c.course.name)" class="p-1 text-gray-400 hover:text-red-600"><Trash2 class="w-4 h-4"/></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                                <span class="text-[9px] font-black text-gray-400 uppercase">{{ courses.length }} Cursos totales</span>
                                                <div class="flex -space-x-2">
                                                    <div v-for="i in 3" :key="i" class="w-6 h-6 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center">
                                                        <Users class="w-3 h-3 text-gray-400" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- /grid -->
                                </div> <!-- /cycleNum loop -->
                            </div> <!-- /v-if expanded -->
                        </transition>
                    </div> <!-- /programName loop -->
                </div> <!-- /periodName loop -->

                <!-- ESTADO VACÍO -->
                <div v-if="!courseSections.data || courseSections.data.length === 0" class="bg-white rounded-[3rem] p-20 text-center border-2 border-dashed border-gray-200">
                    <Layers class="w-16 h-16 text-gray-200 mx-auto mb-4" />
                    <h3 class="text-xl font-bold text-gray-400 uppercase tracking-tighter">No hay secciones configuradas</h3>
                    <p class="text-gray-400 text-xs mt-2">Utiliza "Apertura Rápida" para generar las secciones de este ciclo.</p>
                </div>

            </div>
            <!-- MODAL DE DATOS PARA NÓMINA -->
            <div v-if="isNominaModalOpen" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
                <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in-95">
                    <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
                        <h2 class="font-black text-gray-800 uppercase tracking-tighter italic">Datos de la Nómina</h2>
                        <button @click="isNominaModalOpen = false" class="text-gray-400 hover:text-red-500 text-2xl font-black">×</button>
                    </div>

                    <div class="p-8 space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Resolución de Autorización</label>
                            <input v-model="nominaForm.rdr" type="text" class="w-full border-gray-200 rounded-xl font-bold text-sm focus:ring-emerald-500" placeholder="Ej: RDR N° 3068-2025- DREC">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">R.D. Encargatura</label>
                            <input v-model="nominaForm.rdr_encargatura" type="text" class="w-full border-gray-200 rounded-xl font-bold text-sm focus:ring-emerald-500" placeholder="Ej: RDR N° 3260-2024">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Fecha de Cierre</label>
                            <input v-model="nominaForm.fecha_cierre" type="date" class="w-full border-gray-200 rounded-xl font-bold text-sm focus:ring-emerald-500">
                        </div>

                        <button @click="downloadNomina" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-black text-xs uppercase shadow-xl shadow-emerald-100 hover:bg-emerald-700 transition transform active:scale-95 flex items-center justify-center gap-2">
                            <ClipboardList class="w-4 h-4" />
                            Generar Nómina Oficial
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

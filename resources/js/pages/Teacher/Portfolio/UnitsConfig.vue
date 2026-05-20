<script setup lang="ts">
import { ref } from 'vue'; // Agrupamos los de Vue
import { useForm, Head, Link, router } from '@inertiajs/vue3';
import {
    Layout, Plus, ArrowLeft, Layers,
    CheckCircle2, BookOpen, FileText, MessagesSquare,
    Edit, Trash2, AlertCircle, Sun, Moon // Añadí AlertCircle por si lo usas en las alertas
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3'; // <--- AGREGA ESTA

const props = defineProps<{
    section: any,
    units: any[]
}>();

const page = usePage<any>();

// --- LÓGICA DE CREACIÓN MASIVA ---
const form = useForm({
    number_of_units: 3
});

const submitBatch = (count: number) => {
    form.number_of_units = count;
    if (confirm(`¿Confirmas que deseas organizar este curso en ${count} unidades didácticas?`)) {
        form.post(route('teacher.units.store-batch', props.section.id));
    }
};

// --- LÓGICA DE AGREGAR UNA UNIDAD ---
const addOneUnit = () => {
    router.post(route('teacher.units.add-one', props.section.id), {}, {
        preserveScroll: true
    });
};

// --- LÓGICA DE EDICIÓN DE NOMBRE ---
const editingUnit = ref<any>(null);
const editForm = useForm({ name: '' });

const openEdit = (unit: any) => {
    editingUnit.value = unit;
    editForm.name = unit.name;
};

const updateUnit = () => {
    editForm.put(route('teacher.units.update', editingUnit.value.id), {
        onSuccess: () => editingUnit.value = null,
        preserveScroll: true
    });
};

// --- LÓGICA DE ELIMINACIÓN PRO (MODAL) ---
const unitToDelete = ref<any>(null);

const confirmDelete = (unit: any) => {
    unitToDelete.value = unit;
};

const executeDelete = () => {
    router.delete(route('teacher.units.destroy', unitToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => unitToDelete.value = null
    });
};

// Helper Romano
const toRoman = (num: any) => {
    const map: any = { 1:'I', 2:'II', 3:'III', 4:'IV', 5:'V', 6:'VI', 7:'VII', 8:'VIII', 9:'IX', 10:'X' };
    return map[num] || num;
};
</script>

<template>
    <Head title="Configurar Unidades" />
    <AppLayout>
        <div class="p-8 max-w-5xl mx-auto bg-gray-50 min-h-screen">

            <!-- ENCABEZADO -->
            <div class="mb-10">
                <Link :href="route('teacher.sections.index')" class="text-[10px] font-black text-gray-400 uppercase flex items-center mb-2 hover:text-emerald-600 transition-all">
                    <ArrowLeft class="w-3 h-3 mr-1" /> Volver a mis cursos
                </Link>
                <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter italic">Estructura Académica</h1>
                <p class="text-emerald-600 font-bold text-xs uppercase tracking-widest mt-1">
                    {{ section.course.name }} | SECCIÓN {{ section.name }}
                </p>
            </div>

            <!-- ALERTA DE ERROR -->
            <!-- ALERTA DE ERROR -->
            <div v-if="page.props.flash?.error"
                class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-2xl flex items-center gap-3 shadow-sm animate-in slide-in-from-top-2">
                <AlertCircle class="w-5 h-5 shrink-0" />
                <span class="text-xs font-bold uppercase tracking-tight">
                    {{ page.props.flash.error }}
                </span>
            </div>

            <!-- ESTADO A: EL CURSO NO TIENE UNIDADES TODAVÍA -->
            <div v-if="units.length === 0" class="space-y-8 animate-in fade-in zoom-in-95 duration-500">
                <div class="bg-white p-10 rounded-[3rem] shadow-xl border border-gray-100 text-center">
                    <div class="bg-emerald-50 w-20 h-20 rounded-3xl flex items-center justify-center mx-auto mb-6 text-emerald-600">
                        <Layers class="w-10 h-10" />
                    </div>
                    <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight mb-2">Configura tu Semestre</h2>
                    <p class="text-gray-500 text-sm max-w-md mx-auto mb-10">
                        Para empezar a subir materiales y tareas, primero debes definir en cuántas unidades se divide este curso según tu sílabo.
                    </p>

                    <!-- Reemplaza los 3 botones por este selector dinámico -->
                    <div class="flex items-center justify-center gap-4 bg-gray-50 p-8 rounded-[2rem]">
                        <span class="text-xs font-black text-gray-400 uppercase">¿Cuántas unidades trabajarás?</span>
                        <select v-model="form.number_of_units" class="border-2 border-emerald-100 rounded-xl font-black text-emerald-600 focus:ring-emerald-500">
                            <option v-for="n in 6" :key="n" :value="n">{{ n }} Unidades</option>
                        </select>
                        <button @click="submitBatch(form.number_of_units)" class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-black text-xs uppercase shadow-lg hover:bg-emerald-700 transition-all">
                            Configurar Ahora
                        </button>
                    </div>

                </div>
            </div>

            <!-- ESTADO B: EL CURSO YA TIENE SUS UNIDADES -->
            <div v-else class="grid grid-cols-1 gap-6 animate-in slide-in-from-bottom-4 duration-700">
                <div v-for="unit in units" :key="unit.id"
                     class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 flex flex-col md:flex-row items-center justify-between hover:shadow-md transition-all group">

                    <div class="flex items-center gap-6">
                        <div class="bg-gray-900 text-white w-16 h-16 rounded-3xl flex items-center justify-center font-black text-xl shadow-lg">
                            {{ toRoman(unit.order) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">{{ unit.name }}</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Contenedor de aprendizaje activo</p>
                        </div>
                    </div>

                    <!-- Botones de Gestión Interna (Próximos Bloques) -->
                    <div class="flex gap-3 mt-6 md:mt-0">
                        <!-- Busca el botón de Materiales y cámbialo por este Link -->
                        <Link :href="route('teacher.resources.index', unit.id)"
                            class="flex flex-col items-center gap-1 group/btn">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover/btn:bg-blue-600 group-hover/btn:text-white transition-all">
                                <FileText class="w-5 h-5" />
                            </div>
                            <span class="text-[8px] font-black text-gray-400 uppercase">Materiales</span>
                        </Link>

                        <!-- Busca el botón de Tareas y cámbialo por este -->
                        <Link :href="route('teacher.tasks.index', unit.id)"
                            class="flex flex-col items-center gap-1 group/btn">
                            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl group-hover/btn:bg-indigo-600 group-hover/btn:text-white transition-all">
                                <Plus class="w-5 h-5" />
                            </div>
                            <span class="text-[8px] font-black text-gray-400 uppercase">Tareas</span>
                        </Link>

                        <Link :href="route('teacher.forums.index', unit.id)"
                            class="flex flex-col items-center gap-1 group/btn">
                            <div class="p-3 bg-purple-50 text-purple-600 rounded-2xl group-hover/btn:bg-purple-600 group-hover/btn:text-white transition-all">
                                <MessagesSquare class="w-5 h-5" />
                            </div>
                            <span class="text-[8px] font-black text-gray-400 uppercase">Foros</span>
                        </Link>
                    </div>

                    <div class="flex gap-2 ml-4 pl-4 border-l border-gray-100">
                        <!-- Botón Editar Nombre -->
                        <button @click="openEdit(unit)" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                            <Edit class="w-4 h-4" />
                        </button>
                        <!-- Botón Eliminar Unidad -->
                        <button @click="confirmDelete(unit)" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                            <Trash2 class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Pon esto después del v-for de las unidades -->
                <button @click="addOneUnit"
                        class="w-full p-6 border-2 border-dashed border-gray-200 rounded-[2.5rem] flex items-center justify-center gap-2 text-gray-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all group">
                    <Plus class="w-5 h-5 group-hover:scale-110 transition-transform" />
                    <span class="text-xs font-black uppercase tracking-widest">Añadir otra Unidad Didáctica</span>
                </button>

                <!-- Botón de Reset (Solo Admin o con precaución) -->
                <p class="text-center text-[10px] text-gray-400 uppercase font-bold mt-10">
                    Estructura oficial para el periodo académico actual.
                </p>

                <!-- MODAL DE EDICIÓN DE NOMBRE DE UNIDAD -->
                <div v-if="editingUnit" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
                    <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden">
                        <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
                            <h2 class="font-black text-gray-800 uppercase text-sm tracking-tighter italic">Renombrar Unidad</h2>
                            <button @click="editingUnit = null" class="text-gray-400 hover:text-red-500 text-2xl font-black">×</button>
                        </div>
                        <form @submit.prevent="updateUnit" class="p-8 space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Nuevo nombre de la Unidad</label>
                                <input v-model="editForm.name" type="text" class="w-full border-gray-200 rounded-xl text-sm font-bold focus:ring-blue-500" required />
                            </div>

                            <button :disabled="editForm.processing" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase shadow-xl hover:bg-blue-700 transition-all">
                                {{ editForm.processing ? 'Guardando...' : 'Actualizar Nombre' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN -->
            <div v-if="unitToDelete" class="fixed inset-0 bg-rose-900/40 backdrop-blur-sm z-[70] flex items-center justify-center p-4">
                <div class="bg-white rounded-[2.5rem] w-full max-w-sm shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
                    <div class="p-8 text-center">
                        <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <Trash2 class="w-10 h-10" />
                        </div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter mb-2">¿Eliminar Unidad?</h3>
                        <p class="text-sm text-gray-500 leading-tight">
                            Estás a punto de borrar <strong>{{ unitToDelete.name }}</strong>.
                            Esta acción solo se completará si la unidad está totalmente vacía.
                        </p>
                    </div>
                    <div class="p-6 bg-gray-50 flex gap-3">
                        <button @click="unitToDelete = null" class="flex-1 py-4 text-xs font-black uppercase text-gray-400 hover:text-gray-600 transition-colors">
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

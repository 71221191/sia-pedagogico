<script setup>
import { ref } from 'vue';
import { useForm, router, Head } from '@inertiajs/vue3';
import { FileSearch, CheckCircle, AlertCircle, XCircle, Eye } from 'lucide-vue-next';

const props = defineProps({
    files: Object,
    currentStatus: String
});

const isModalOpen = ref(false);
const selectedFile = ref(null);
const showFeedbackField = ref(false);

const form = useForm({
    status: '',
    feedback: ''
});

const openModal = (file) => {
    selectedFile.value = file;
    form.feedback = file.feedback || '';
    showFeedbackField.value = false;
    isModalOpen.value = true;
};

const processFile = (status) => {
    if (status === 'observed' && !showFeedbackField.value) {
        showFeedbackField.value = true;
        return;
    }
    form.status = status;
    form.patch(route('head_of_area.portfolio.update', selectedFile.value.id), {
        onSuccess: () => isModalOpen.value = false
    });
};
// Agrega o reemplaza esta función en tu script
const getStatusClasses = (status) => {
    if (status === 'approved') return 'bg-emerald-50 text-emerald-700 border-emerald-100';
    if (status === 'observed') return 'bg-rose-50 text-rose-700 border-rose-100';
    return 'bg-amber-50 text-amber-700 border-amber-100';
};

const changeTab = (status) => {
    router.get(route('head_of_area.portfolio.index'), { status });
};
</script>

<template>
    <Head title="Validación de Portafolio" />
    <div class="p-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-black text-gray-900 mb-8 uppercase tracking-tight">Revisión de Portafolio Docente</h1>

            <!-- TABS -->
            <div class="flex space-x-2 mb-6">
                <button v-for="s in ['pending', 'approved', 'observed']" :key="s"
                    @click="changeTab(s)"
                    :class="currentStatus === s ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white text-gray-500 border'"
                    class="px-6 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                    {{ s === 'pending' ? 'Pendientes' : (s === 'approved' ? 'Aprobados' : 'Observados') }}
                </button>
            </div>

            <!-- TABLA -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-900 text-white text-[10px] uppercase font-black tracking-widest">
                            <th class="p-4">Docente / Curso</th>
                            <th class="p-4">Tipo de Doc.</th>
                            <th class="p-4">Fecha Subida</th>
                            <th class="p-4 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="file in files.data" :key="file.id" class="hover:bg-indigo-50/30 transition">
                            <td class="p-5">
                                <!-- Usamos encadenamiento opcional ?. para que no explote si falta el dato -->
                                <div class="font-black text-gray-900 uppercase text-[11px] leading-tight">
                                    {{ file.section?.teacher?.full_name || 'Docente no asignado' }}
                                </div>
                                <div class="text-[9px] text-indigo-600 font-bold uppercase mt-1 tracking-wider">
                                    {{ file.section?.course?.name }} — SECC {{ file.section?.name }}
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border" :class="getStatusClasses(file.status)">
                                    {{ file.type }}
                                </span>
                            </td>
                            <td class="p-5 text-[10px] text-gray-400 font-bold uppercase">
                                {{ new Date(file.created_at).toLocaleDateString() }}
                            </td>
                            <td class="p-5 text-center">
                                <button @click="openModal(file)"
                                        class="bg-gray-900 text-white px-5 py-2.5 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg shadow-gray-200">
                                    AUDITAR DOCUMENTO
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL DE VISUALIZACIÓN -->
        <div v-if="isModalOpen" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-[2.5rem] w-full max-w-5xl max-h-[95vh] overflow-hidden flex flex-col shadow-2xl">
                <div class="p-6 border-b flex justify-between items-center bg-gray-50">
                    <div>
                        <h2 class="font-black text-gray-900 uppercase text-lg">{{ selectedFile.name }}</h2>
                        <p class="text-xs text-indigo-500 font-bold uppercase">{{ selectedFile.section.course.name }}</p>
                    </div>
                    <button @click="isModalOpen = false" class="text-gray-400 hover:text-red-500 text-2xl font-black">×</button>
                </div>

                <div class="flex-1 bg-slate-100 p-4 sm:p-8 overflow-y-auto">
                    <!-- Contenedor con aspecto de papel -->
                    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-sm overflow-hidden" style="min-height: 800px;">
                        <iframe
                            :src="'/storage/' + selectedFile.file_path"
                            class="w-full h-full border-none"
                            style="min-height: 800px;"
                        ></iframe>
                    </div>
                </div>

                <div class="p-8 bg-gray-50 border-t flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <button @click="processFile('observed')"
                                class="px-8 py-3 bg-white border-2 border-rose-200 text-rose-600 rounded-2xl font-black text-xs uppercase hover:bg-rose-50 transition-all">
                            {{ showFeedbackField ? 'CONFIRMAR Y NOTIFICAR' : 'OBSERVAR / RECHAZAR' }}
                        </button>
                        <button v-if="showFeedbackField" @click="showFeedbackField = false" class="text-gray-400 font-bold text-xs uppercase tracking-widest">Cancelar</button>
                    </div>

                    <button @click="processFile('approved')"
                            class="px-12 py-4 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase shadow-xl shadow-emerald-100 hover:bg-emerald-700 transition-all transform hover:scale-105">
                        APROBAR PARA REGISTRO DE NOTAS
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

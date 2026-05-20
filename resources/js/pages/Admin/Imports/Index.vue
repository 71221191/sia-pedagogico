<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';

import { ref } from 'vue';
import {
    Upload, FileSpreadsheet, Users, BookOpen,
    GraduationCap, DollarSign, AlertCircle,
    CheckCircle2, UserRoundCheck, History
} from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

// Opciones de importación
const importTypes = [
    { value: 'students', label: 'Alumnos', icon: Users, description: 'Importar datos personales de estudiantes' },
    { value: 'teachers', label: 'Docentes', icon: UserRoundCheck, description: 'Importar datos y cuentas de docentes' }, // <--- Nueva opción
    { value: 'courses', label: 'Cursos', icon: BookOpen, description: 'Importar catálogo de cursos académicos' },
    { value: 'grades', label: 'Notas', icon: GraduationCap, description: 'Importar calificaciones de estudiantes' },
    { value: 'payments', label: 'Pagos', icon: DollarSign, description: 'Importar pagos históricos' },

];

const selectedType = ref(null);
const dragOver = ref(false);

const form = useForm({
    file: null,
    import_type: null,
});

function selectType(type) {
    selectedType.value = type;
    form.import_type = type;
}

function onFileChange(event) {
    const file = event.target.files[0];
    if (file) {
        form.file = file;
    }
}

function onDrop(event) {
    dragOver.value = false;
    const file = event.dataTransfer.files[0];
    if (file) {
        form.file = file;
    }
}

function submit() {
    form.post(route('admin.imports.process'), {
        onSuccess: () => {
            form.reset();
            selectedType.value = null;
        },
    });
}
</script>

<template>
    <Head title="Importación Masiva" />
    <AppLayout>
        <div class="p-8 max-w-5xl mx-auto bg-gray-50 min-h-screen">
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-8 flex items-center">
                <Upload class="mr-3 w-8 h-8 text-indigo-600" />
                Centro de Importación Masiva
            </h1>

            <div class="mb-8">
                <Link :href="route('admin.imports.history')"
                    class="inline-flex items-center px-4 py-2 bg-white border-2 border-gray-100 text-gray-600 rounded-xl font-black text-[10px] uppercase hover:border-indigo-200 hover:text-indigo-600 transition-all shadow-sm">
                    <History class="w-4 h-4 mr-2" />
                    Ver Historial de Cargas y Errores
                </Link>
            </div>

            <!-- Mensajes de éxito / error -->
            <div v-if="$page.props.flash?.success" class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl flex items-center">
                <CheckCircle2 class="w-5 h-5 mr-2" />
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.detalles_errores?.length" class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded-xl">
                <div class="flex items-center mb-2">
                    <AlertCircle class="w-5 h-5 mr-2" />
                    <span class="font-bold">Errores encontrados:</span>
                </div>
                <ul class="list-disc list-inside text-sm">
                    <li v-for="(error, idx) in $page.props.flash.detalles_errores" :key="idx">{{ error }}</li>
                </ul>
            </div>

            <!-- Selector visual de tipo de importación -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <button
                    v-for="type in importTypes"
                    :key="type.value"
                    @click="selectType(type.value)"
                    :class="[
                        'p-6 rounded-2xl border-2 transition-all duration-200 text-left',
                        selectedType === type.value
                            ? 'border-indigo-500 bg-indigo-50 shadow-md'
                            : 'border-gray-200 bg-white hover:border-indigo-300 hover:shadow-sm'
                    ]"
                >
                    <component :is="type.icon" class="w-8 h-8 mb-3 text-indigo-600" />
                    <h3 class="font-bold text-gray-900">{{ type.label }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ type.description }}</p>
                </button>
            </div>

            <!-- Zona de Drag & Drop -->
            <div
                @dragover.prevent="dragOver = true"
                @dragleave.prevent="dragOver = false"
                @drop.prevent="onDrop"
                :class="[
                    'border-2 border-dashed rounded-3xl p-10 text-center transition-all',
                    dragOver ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300 bg-white'
                ]"
            >
                <FileSpreadsheet class="w-12 h-12 mx-auto mb-4 text-gray-400" />
                <p class="text-gray-600 font-medium mb-2">
                    Arrastra tu archivo Excel aquí o haz clic para seleccionarlo
                </p>
                <p class="text-xs text-gray-400 mb-4">Formatos aceptados: .xlsx, .xls, .csv</p>
                <input
                    type="file"
                    @input="onFileChange"
                    accept=".xlsx,.xls,.csv"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-600 file:text-white file:font-bold cursor-pointer"
                />
                <p v-if="form.file" class="mt-2 text-sm text-indigo-600 font-medium">
                    Archivo seleccionado: {{ form.file.name }}
                </p>
            </div>

            <!-- Botón de envío -->
            <div class="mt-8 text-center">
                <button
                    @click="submit"
                    :disabled="!form.file || !selectedType || form.processing"
                    class="px-10 py-4 bg-indigo-600 text-white font-bold text-lg rounded-2xl shadow-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center mx-auto"
                >
                    <Upload v-if="!form.processing" class="w-5 h-5 mr-2" />
                    <svg v-else class="animate-spin w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    {{ form.processing ? 'Procesando...' : 'Iniciar Importación' }}
                </button>
            </div>
        </div>
    </AppLayout>
</template>

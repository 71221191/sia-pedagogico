<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import { FileText, Upload, Trash2, ArrowLeft, Eye, CheckCircle } from 'lucide-vue-next';

const props = defineProps({
    section: Object
});

const form = useForm({
    name: '',
    file: null
});

const submit = () => {
    form.post(route('teacher.syllabus.store', props.section.id), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Sílabo del Curso" />
    <div class="p-8 max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <Link :href="route('teacher.sections.index')" class="text-sm font-bold text-gray-500 uppercase flex items-center mb-2">
                <ArrowLeft class="w-4 h-4 mr-1" /> Volver a mis cursos
            </Link>
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">{{ section.course.name }}</h1>
            <p class="text-gray-500 font-mono text-sm uppercase">Gestión de Sílabo del Curso | Sección {{ section.name }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulario de Subida -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 sticky top-8">
                    <h2 class="font-black text-gray-800 mb-6 text-xs uppercase tracking-widest flex items-center">
                        <Upload class="mr-2 w-4 h-4 text-rose-600" /> Cargar Sílabo
                    </h2>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Nombre Descriptivo</label>
                            <input v-model="form.name" type="text" placeholder="Ej: Sílabo Oficial 2026-I..." class="w-full border-gray-100 rounded-xl text-sm" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Archivo (PDF max 2MB)</label>
                            <input type="file" @input="form.file = $event.target.files[0]" accept=".pdf" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-rose-50 file:text-rose-700" required />
                        </div>

                        <button :disabled="form.processing" class="w-full bg-rose-600 text-white py-4 rounded-2xl font-black shadow-lg shadow-rose-100 hover:bg-rose-700 transition">
                            {{ form.processing ? 'SUBIENDO...' : 'SUBIR SÍLABO' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Visualización del Sílabo Activo -->
            <div class="lg:col-span-2 space-y-4">
                <h2 class="font-black text-gray-800 text-xs uppercase tracking-widest">Sílabo Activo de la Sección</h2>

                <!-- Si ya existe un sílabo cargado -->
                <div v-if="section.syllabus_path"
                    class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-5 flex flex-col md:flex-row items-center justify-between hover:shadow-md transition-all">

                    <div class="flex items-center space-x-4 w-full md:w-auto">
                        <div class="bg-rose-50 text-rose-600 p-4 rounded-2xl">
                            <FileText class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-black text-gray-900 text-sm uppercase leading-tight">{{ section.syllabus_name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[9px] font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full uppercase tracking-widest flex items-center gap-1">
                                    <CheckCircle class="w-3 h-3" /> Sílabo Activo
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4 md:mt-0 w-full md:w-auto justify-end">
                        <!-- Ver PDF -->
                        <a :href="'/storage/' + section.syllabus_path" target="_blank"
                        class="p-2.5 bg-gray-900 text-white rounded-xl hover:bg-rose-600 transition-all shadow-lg shadow-gray-100">
                            <Eye class="w-4 h-4" />
                        </a>
                        <!-- Eliminar PDF (Sustituye al destroy antiguo) -->
                        <button @click="$inertia.delete(route('teacher.syllabus.destroy', section.id))"
                                class="p-2.5 bg-white border-2 border-gray-100 text-gray-400 rounded-xl hover:text-red-600 hover:border-red-100 transition-all">
                            <Trash2 class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Estado Vacío (Si no hay sílabo) -->
                <div v-else class="p-10 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] text-center">
                    <FileText class="w-12 h-12 mx-auto text-gray-300 mb-2" />
                    <p class="text-xs text-gray-400 font-bold uppercase">No se ha subido ningún sílabo para esta sección.</p>
                </div>
            </div>
        </div>
    </div>
</template>

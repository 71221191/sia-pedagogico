<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Head, Link, router } from '@inertiajs/vue3';
import {
    FileText, Link as LinkIcon, Plus, Trash2,
    Eye, EyeOff, ArrowLeft, Upload, ExternalLink
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    unit: any,
    resources: any[]
}>();

const isModalOpen = ref(false);

const form = useForm({
    type: 'file',
    title: '',
    description: '',
    // Cambia la línea de abajo para que acepte archivos:
    file: null as File | null,
    url: ''
});

const submit = () => {
    form.post(route('teacher.resources.store', props.unit.id), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        }
    });
};

const deleteResource = (id: number) => {
    if (confirm('¿Eliminar este recurso de forma permanente?')) {
        router.delete(route('teacher.resources.destroy', id));
    }
};

const toggleVisibility = (id: number) => {
    router.patch(route('teacher.resources.toggle', id));
};

// Función para manejar la subida de archivos de forma segura para TypeScript
const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.file = target.files[0];
    }
};

</script>

<template>
    <Head title="Materiales de Clase" />
    <AppLayout>
        <div class="p-8 max-w-5xl mx-auto bg-gray-50 min-h-screen">

            <!-- HEADER -->
            <div class="mb-10">
                <Link :href="route('teacher.units.index', unit.course_section_id)" class="text-[10px] font-black text-gray-400 uppercase flex items-center mb-2 hover:text-blue-600 transition-all">
                    <ArrowLeft class="w-3 h-3 mr-1" /> Volver a unidades
                </Link>
                <div class="flex justify-between items-end">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter italic">{{ unit.name }}</h1>
                        <p class="text-blue-600 font-bold text-xs uppercase">{{ unit.section.course.name }}</p>
                    </div>
                    <button @click="isModalOpen = true" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase shadow-xl hover:bg-blue-700 transition transform hover:scale-105">
                        <Plus class="w-4 h-4 mr-2 inline" /> Agregar Material
                    </button>
                </div>
            </div>

            <!-- LISTA DE RECURSOS -->
            <div class="grid grid-cols-1 gap-4">
                <div v-for="res in resources" :key="res.id"
                     class="bg-white p-5 rounded-[2rem] border border-gray-100 shadow-sm flex items-center justify-between group">

                    <div class="flex items-center gap-4">
                        <div :class="res.type === 'file' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600'" class="p-4 rounded-2xl">
                            <FileText v-if="res.type === 'file'" class="w-6 h-6" />
                            <LinkIcon v-else class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-black text-gray-800 text-sm uppercase leading-tight">{{ res.title }}</h3>
                            <p class="text-[10px] text-gray-400 font-medium">{{ res.description || 'Sin descripción' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Botón Visibilidad -->
                        <button @click="toggleVisibility(res.id)"
                                :class="res.is_visible ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-400'"
                                class="p-2.5 rounded-xl transition-all" title="Cambiar visibilidad">
                            <Eye v-if="res.is_visible" class="w-4 h-4" />
                            <EyeOff v-else class="w-4 h-4" />
                        </button>

                        <!-- Botón Abrir/Descargar -->
                        <a :href="res.type === 'file' ? '/storage/' + res.file_path : res.url"
                           target="_blank"
                           class="p-2.5 bg-gray-900 text-white rounded-xl hover:bg-blue-600 transition-all">
                            <ExternalLink class="w-4 h-4" />
                        </a>

                        <!-- Botón Eliminar -->
                        <button @click="deleteResource(res.id)" class="p-2.5 text-gray-300 hover:text-red-600 transition-all">
                            <Trash2 class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <div v-if="resources.length === 0" class="p-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-200">
                    <FileText class="w-12 h-12 text-gray-200 mx-auto mb-2" />
                    <p class="text-gray-400 font-bold uppercase text-[10px]">No hay materiales en esta unidad</p>
                </div>
            </div>

            <!-- MODAL DE CARGA -->
            <div v-if="isModalOpen" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden">
                    <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
                        <h2 class="font-black text-gray-800 uppercase text-sm tracking-tighter">Nuevo Recurso de Aprendizaje</h2>
                        <button @click="isModalOpen = false" class="text-gray-400 hover:text-red-500 text-2xl font-black">×</button>
                    </div>
                    <form @submit.prevent="submit" class="p-8 space-y-4">
                        <div class="flex bg-gray-100 p-1 rounded-xl">
                            <button type="button" @click="form.type = 'file'" :class="form.type === 'file' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-400'" class="flex-1 py-2 rounded-lg font-black text-[10px] uppercase transition-all">Archivo</button>
                            <button type="button" @click="form.type = 'link'" :class="form.type === 'link' ? 'bg-white shadow-sm text-amber-600' : 'text-gray-400'" class="flex-1 py-2 rounded-lg font-black text-[10px] uppercase transition-all">Enlace Web</button>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Título del Material</label>
                            <input v-model="form.title" type="text" placeholder="Ej: Diapositivas de la semana 1" class="w-full border-gray-200 rounded-xl text-sm" required
                            />
                        </div>

                        <div v-if="form.type === 'file'">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Seleccionar PDF/PPT (Max 5MB)</label>
                            <input
                                type="file"
                                @input="handleFileChange"
                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700"
                                required/>
                        </div>

                        <div v-else>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Pegar URL (YouTube/Drive/Web)</label>
                            <input v-model="form.url" type="url" placeholder="https://..." class="w-full border-gray-200 rounded-xl text-sm" required />
                        </div>

                        <button :disabled="form.processing" class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black text-xs uppercase shadow-xl hover:bg-blue-600 transition-all transform active:scale-95">
                            {{ form.processing ? 'Sincronizando...' : 'Guardar en la Unidad' }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

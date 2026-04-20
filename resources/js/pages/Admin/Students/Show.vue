<script setup>

import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Camera, Upload, User, CheckCircle, XCircle } from 'lucide-vue-next';

const props = defineProps({
    student: Object
});

// 1. Variable para mostrar la previsualización
const photoPreview = ref(null);

const photoForm = useForm({
    photo: null,
});

// 2. Función para capturar el archivo y generar la vista previa
const onFileChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    photoForm.photo = file;
    // Esto crea una URL temporal en la memoria del navegador para ver la foto
    photoPreview.value = URL.createObjectURL(file);
};

const uploadPhoto = () => {
    // Usamos el nombre de la ruta que pusimos en web.php
    photoForm.post(route('admin.students.photo', props.student.id), {
        forceFormData: true,
        onSuccess: () => {
            photoPreview.value = null; // Limpiamos la previa al terminar
            alert('¡Foto oficial actualizada con éxito!');
        },
    });
};

</script>

<template>
    <Head :title="student.names" />

    <div class="p-8 bg-gray-100 min-h-screen font-sans">
        <div class="max-w-5xl mx-auto">
            <!-- CAMBIO AQUÍ: Ruta manual -->
            <Link href="/admin/estudiantes" class="text-blue-600 mb-4 inline-block font-bold">← Volver al listado</Link>

            <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Información del Alumno -->
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            {{ student.last_name_p }} {{ student.last_name_m }}, {{ student.names }}
                        </h1>
                        <p class="text-gray-500 text-lg">DNI: {{ student.dni }}</p>
                    </div>

                    <div class="max-w-sm mx-auto lg:mx-0">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-xl overflow-hidden relative">
                            <!-- Título de sección -->
                            <div class="flex items-center space-x-2 mb-6">
                                <Camera class="w-4 h-4 text-indigo-500" />
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Identidad Oficial</h3>
                            </div>

                            <div class="flex flex-col items-center">
                                <!-- CONTENEDOR DE LA FOTO -->
                                <div class="w-44 h-52 rounded-3xl border-4 border-gray-50 bg-gray-100 shadow-inner overflow-hidden mb-6 relative">
                                    <!-- Mostramos la PREVIA si el admin eligió un archivo -->
                                    <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover" />

                                    <!-- Si no hay previa, mostramos la FOTO ACTUAL de la base de datos -->
                                    <img v-else-if="student.official_photo_path"
                                        :src="'/storage/' + student.official_photo_path"
                                        class="w-full h-full object-cover" />

                                    <!-- Si el alumno no tiene ninguna foto -->
                                    <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                        <User class="w-16 h-16 opacity-20" />
                                        <span class="text-[8px] font-bold uppercase mt-2">Sin foto carnet</span>
                                    </div>
                                </div>

                                <!-- INPUT DE ARCHIVO DISFRAZADO DE BOTÓN -->
                                <div class="w-full space-y-3">
                                    <label class="cursor-pointer block text-center p-3 border-2 border-dashed border-indigo-100 rounded-2xl hover:bg-indigo-50 transition group">
                                        <span class="text-[10px] font-black text-indigo-600 uppercase group-hover:text-indigo-700">
                                            {{ photoForm.photo ? 'Cambiar selección' : 'Seleccionar Foto' }}
                                        </span>
                                        <input type="file" @change="onFileChange" accept="image/*" class="hidden" />
                                    </label>

                                    <!-- Botón de Guardar (Solo aparece si hay algo seleccionado) -->
                                    <button v-if="photoForm.photo"
                                            @click="uploadPhoto"
                                            :disabled="photoForm.processing"
                                            class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black text-[10px] uppercase shadow-lg hover:bg-indigo-600 transition flex items-center justify-center">
                                        <Upload v-if="!photoForm.processing" class="w-4 h-4 mr-2" />
                                        {{ photoForm.processing ? 'Sincronizando...' : 'FIJAR COMO FOTO OFICIAL' }}
                                    </button>
                                </div>

                                <p class="mt-4 text-[9px] text-gray-400 text-center leading-tight italic">
                                    * Use fotos con fondo blanco para fines de titulación y carnetización.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de Descarga -->
                    <div>
                        <a
                            :href="route('admin.students.certificate', student.id)"
                            target="_blank"
                            class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-md transition-all transform hover:scale-105 active:scale-95 text-sm uppercase tracking-wider"
                        >
                            <!-- Icono de PDF -->
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6M9 13h6"></path>
                            </svg>
                            Generar Certificado Oficial
                        </a>
                    </div>
                </div>
            </div>


            <h2 class="text-xl font-bold mb-4 border-b-2 border-gray-300 pb-2">HISTORIAL ACADÉMICO IMPORTADO</h2>

            <div v-if="student.enrollments.length === 0" class="bg-orange-100 p-4 text-orange-700 rounded-lg">
                No se encontraron registros de notas para este estudiante.
            </div>

            <div v-for="enrollment in student.enrollments" :key="enrollment.id" class="mb-10">
                <div class="bg-gray-800 text-white p-3 rounded-t-lg flex justify-between items-center">
                    <div>
                        <span class="font-bold">Periodo: {{ enrollment.academic_period.name }}</span>
                        <!-- AQUÍ SE MOSTRARÁ LA CARRERA -->
                        <span class="ml-4 text-blue-300">| Programa: {{ enrollment.study_plan.program.name }}</span>
                    </div>
                    <span class="bg-blue-600 px-2 py-1 rounded text-xs">Ciclo: {{ enrollment.cycle }}</span>
                </div>

                <div class="bg-white shadow rounded-b-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-100 text-xs font-bold text-gray-600 uppercase">
                            <tr>
                                <th class="p-3 text-left">Curso / Unidad Didáctica</th>
                                <th class="p-3 text-center">Nota</th>
                                <th class="p-3 text-center">Resultado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="detail in enrollment.details" :key="detail.id">
                                <td class="p-3 text-gray-700">{{ detail.course.name }}</td>
                                <td class="p-3 text-center font-bold text-lg">{{ detail.final_score_numeric }}</td>
                                <td class="p-3 text-center">
                                    <span :class="detail.status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                        class="px-3 py-1 rounded-full text-xs font-bold uppercase">
                                        {{ detail.status === 'approved' ? 'Aprobado' : 'Desaprobado' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 p-4 bg-white rounded-2xl border shadow-sm">
        <h3 class="text-sm font-bold mb-4 uppercase">Foto de Carnet Oficial</h3>
        <input type="file" @input="photoForm.photo = $event.target.files[0]" accept="image/*" />
        <button
            @click="uploadPhoto"
            :disabled="photoForm.processing"
            class="mt-2 w-full bg-gray-900 text-white py-2 rounded-xl font-bold text-xs"
        >
            {{ photoForm.processing ? 'SUBIENDO...' : 'GUARDAR FOTO OFICIAL' }}
        </button>
    </div>
</template>

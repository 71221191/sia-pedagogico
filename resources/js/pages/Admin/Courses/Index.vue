<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus, Edit, Trash2, CheckCircle2, AlertCircle, BookOpen } from 'lucide-vue-next';
import { route } from 'ziggy-js';

const props = defineProps<{
    courses: {
        data: any[];
        links: any[]; // Para paginación
        current_page: number;
        last_page: number;
        total: number;
    };
    flash: { success?: string; error?: string }; // Para mensajes flash
}>();

// Formulario para eliminar
const deleteForm = useForm({});

const deleteCourse = (courseId: number, courseName: string) => {
    if (confirm(`¿Estás seguro de que quieres ELIMINAR el curso "${courseName}"? Esta acción no se puede deshacer y puede afectar a secciones o matrículas asociadas.`)) {
        deleteForm.delete(route('admin.courses.destroy', courseId), {
            preserveScroll: true,
            onSuccess: () => {
                // Mensaje de éxito se mostrará vía flash
            },
            onError: (errors) => {
                console.error('Error al eliminar curso:', errors);
                alert(props.flash.error || 'Hubo un error al intentar eliminar el curso.');
            }
        });
    }
};
</script>

<template>
    <Head title="Cursos" />

    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8 font-sans">
        <div class="max-w-7xl mx-auto">

            <!-- ENCABEZADO -->
            <div class="flex justify-between items-center mb-10">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Gestión de Cursos</h1>
                <Link :href="route('admin.courses.create')"
                      class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <Plus class="w-5 h-5 mr-2" />
                    Nuevo Curso
                </Link>
            </div>

            <!-- Mensajes Flash -->
            <div v-if="props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 flex items-center gap-3" role="alert">
                <CheckCircle2 class="w-5 h-5" />
                <span>{{ props.flash.success }}</span>
            </div>
            <div v-if="props.flash.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 flex items-center gap-3" role="alert">
                <AlertCircle class="w-5 h-5" />
                <span>{{ props.flash.error }}</span>
            </div>


            <!-- TABLA DE CURSOS -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <!-- <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Plan de Estudio
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre del Curso
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ciclo
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Créditos
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    H. Teo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    H. Prá
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    H. Tot
                                </th>

                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo
                                </th>

                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead> -->

                        <thead>
                            <tr class="bg-gray-900 text-white text-[10px] uppercase font-black tracking-widest">
                                <th class="p-4">Plan / Especialidad</th>
                                <th class="p-4 text-center w-20">Ciclo</th>
                                <th class="p-4">Código / Curso</th>
                                <th class="p-4 text-center">Créd.</th>
                                <!-- NUEVAS COLUMNAS DE HORAS -->
                                <th class="p-4 text-center bg-gray-800">H (Total)</th>
                                <th class="p-4 text-center bg-gray-800">T</th>
                                <th class="p-4 text-center bg-gray-800">P</th>
                                <!-- COLUMNA DE COMPONENTE -->
                                <th class="p-4 text-center">Comp.</th>
                                <th class="p-4 text-center w-28">Acciones</th>
                            </tr>
                        </thead>


                        <!-- <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="course in courses.data" :key="course.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ course.study_plan.name }} ({{ course.study_plan.study_program.short_name }})
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ course.code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ course.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ course.cycle }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ course.credits }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ course.theoretical_hours }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ course.practical_hours }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ course.total_hours }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                    {{ course.type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center space-x-2">
                                        <Link :href="route('admin.courses.edit', course.id)"
                                              class="text-indigo-600 hover:text-indigo-900 transition-colors p-1 rounded-md hover:bg-indigo-50"
                                              title="Editar">
                                            <Edit class="w-5 h-5" />
                                        </Link>
                                        <button @click="deleteCourse(course.id, course.name)"
                                                :disabled="deleteForm.processing"
                                                class="text-red-600 hover:text-red-900 transition-colors p-1 rounded-md hover:bg-red-50"
                                                title="Eliminar">
                                            <Trash2 class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="courses.data.length === 0">
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No hay cursos registrados.
                                </td>
                            </tr>
                        </tbody> -->
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="course in courses.data" :key="course.id" class="hover:bg-blue-50/30 transition">
                                <!-- 1. Plan y Programa -->
                                <td class="p-4">
                                    <div class="text-[10px] font-bold text-indigo-600 uppercase">
                                        {{ course.study_plan.name }}
                                    </div>
                                    <div class="text-[9px] text-gray-400 uppercase italic">
                                        {{ course.study_plan.study_program.name }}
                                    </div>
                                </td>

                                <!-- 2. Ciclo (Estandarizado) -->
                                <td class="p-4 text-center font-black text-gray-700">
                                    {{ course.cycle }}
                                </td>

                                <!-- 3. Código y Nombre -->
                                <td class="p-4">
                                    <div class="text-[10px] font-mono text-gray-400">#{{ course.code }}</div>
                                    <div class="text-xs font-black text-gray-800 uppercase leading-tight">
                                        {{ course.name }}
                                    </div>
                                </td>

                                <!-- 4. Créditos -->
                                <td class="p-4 text-center">
                                    <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded-lg font-black text-xs">
                                        {{ course.credits }}
                                    </span>
                                </td>

                                <!-- 5. HORAS (H, T, P) -->
                                <td class="p-4 text-center font-bold text-gray-600 bg-gray-50/50">{{ course.hours_total }}h</td>
                                <td class="p-4 text-center text-gray-500 bg-gray-50/50">{{ course.hours_theory }}h</td>
                                <td class="p-4 text-center text-gray-500 bg-gray-50/50">{{ course.hours_practice }}h</td>

                                <!-- 6. COMPONENTE (Badge de color) -->
                                <td class="p-4 text-center">
                                    <span :class="{
                                        'bg-blue-50 text-blue-600 border-blue-100': course.component === 'FG',
                                        'bg-amber-50 text-amber-600 border-amber-100': course.component === 'FE',
                                        'bg-green-50 text-green-600 border-green-100': course.component === 'FPI',
                                        'bg-purple-50 text-purple-600 border-purple-100': course.component === 'ELECTIVO'
                                    }" class="px-2 py-0.5 rounded text-[9px] font-black border uppercase">
                                        {{ course.component || 'N/A' }}
                                    </span>
                                </td>

                                <!-- 7. ACCIONES -->
                                <td class="p-4 text-center">
                                    <div class="flex justify-end items-center space-x-2">
                                        <Link :href="route('admin.courses.edit', course.id)"
                                              class="text-indigo-600 hover:text-indigo-900 transition-colors p-1 rounded-md hover:bg-indigo-50"
                                              title="Editar">
                                            <Edit class="w-5 h-5" />
                                        </Link>
                                        <button @click="deleteCourse(course.id, course.name)"
                                                :disabled="deleteForm.processing"
                                                class="text-red-600 hover:text-red-900 transition-colors p-1 rounded-md hover:bg-red-50"
                                                title="Eliminar">
                                            <Trash2 class="w-5 h-5" />
                                        </button>
                                    </div>
                                </td>
                                <tr v-if="courses.data.length === 0">
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No hay cursos registrados.
                                    </td>
                                </tr>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div v-if="courses.last_page > 1" class="flex justify-between items-center px-6 py-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        Mostrando {{ courses.data.length }} de {{ courses.total }} períodos.
                    </div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, index) in courses.links" :key="index">
                            <!-- Si link.url es null, renderiza un span deshabilitado -->
                            <span v-if="link.url === null"
                                  :class="{
                                      'rounded-l-md': index === 0,
                                      'rounded-r-md': index === courses.links.length - 1,
                                  }"
                                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-gray-400 cursor-not-allowed text-sm font-medium opacity-50"
                                  v-html="link.label">
                            </span>
                            <!-- De lo contrario, renderiza el componente Link -->
                            <Link v-else
                                :href="link.url"
                                :class="{
                                    'bg-blue-600 text-white hover:bg-blue-700': link.active,
                                    'bg-white text-gray-700 hover:bg-gray-50': !link.active,
                                    'rounded-l-md': index === 0,
                                    'rounded-r-md': index === courses.links.length - 1,
                                }"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium"
                                v-html="link.label">
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

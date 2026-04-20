<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import { Save, ArrowLeft, Users, GraduationCap } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    sections: Array,
    teachers: Array,
    period: Object,
    plan: Object
});

// Preparamos el formulario con la lista de secciones
const form = useForm({
    assignments: props.sections.map(s => ({
        id: s.id,
        course_name: s.course.name,
        cycle: s.course.cycle,
        teacher_id: s.teacher_id || '',
        vacancy_limit: s.vacancy_limit || 30
    }))
});

const submit = () => {
    form.post(route('admin.course_sections.update-bulk'), {
        onSuccess: () => alert('✅ Carga académica guardada correctamente.')
    });
};
</script>

<template>
    <Head title="Asignación de Docentes" />

    <AppLayout>
        <div class="p-8 max-w-6xl mx-auto bg-gray-50 min-h-screen">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Asignación de Carga Académica</h1>
                    <p class="text-indigo-600 font-bold text-xs uppercase mt-1">
                        {{ plan.study_program.name }} | Periodo {{ period.name }}
                    </p>
                </div>
                <button @click="submit" :disabled="form.processing"
                        class="bg-green-600 text-white px-8 py-3 rounded-2xl font-black uppercase text-xs shadow-lg hover:bg-green-700 transition flex items-center">
                    <Save class="w-4 h-4 mr-2" />
                    {{ form.processing ? 'Guardando...' : 'Guardar Todo' }}
                </button>
            </div>

            <!-- Tabla de Edición Masiva -->
            <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-900 text-white text-[10px] uppercase font-black tracking-widest text-center">
                            <th class="p-4 w-16">Ciclo</th>
                            <th class="p-4 text-left">Asignatura / Curso</th>
                            <th class="p-4">Docente Responsable</th>
                            <th class="p-4 w-24">Vacantes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="(item, index) in form.assignments" :key="item.id" class="hover:bg-indigo-50/30 transition">
                            <td class="p-4 text-center">
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-lg font-black text-[10px]">
                                    {{ item.cycle }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="text-xs font-bold text-gray-800 uppercase">{{ item.course_name }}</div>
                            </td>
                            <td class="p-2">
                                <select v-model="item.teacher_id"
                                        class="w-full border-none bg-transparent focus:ring-0 text-xs font-medium text-gray-600">
                                    <option value="">-- Sin asignar --</option>
                                    <option v-for="t in teachers" :key="t.id" :value="t.id">
                                        {{ t.last_name_p }} {{ t.names }}
                                    </option>
                                </select>
                            </td>
                            <td class="p-2">
                                <input v-model="item.vacancy_limit" type="number"
                                       class="w-full border-none bg-gray-50 rounded-xl text-center font-bold text-indigo-600 focus:ring-2 focus:ring-indigo-500" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

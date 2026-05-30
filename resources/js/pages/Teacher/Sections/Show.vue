<script setup>
import { useForm, router, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    section: Object,
    students: Array,
    gradeScales: Array,
    evaluationType: String // 'numerical' o 'competency'
});

const closeActa = () => {
    if (confirm('⚠️ ¡ATENCIÓN! Una vez cerrada el acta, NO podrá modificar ninguna nota. ¿Desea continuar y oficializar las calificaciones?')) {
        router.patch(route('teacher.sections.close', props.section.id), {}, {
            preserveScroll: true,
            onSuccess: () => alert('Acta oficializada correctamente.')
        });
    }
};

// 1. Función para el color de la nota
const getGradeColorClass = (scaleId) => {
    const scale = props.gradeScales.find(s => s.id === scaleId);
    if (!scale) return 'text-gray-400';

    const val = parseFloat(scale.numeric_equivalent);

    if (val === 0) return 'bg-orange-50 text-orange-700 border-orange-200'; // NP / 00
    if (val < 3.0) return 'bg-red-50 text-red-700 border-red-200';        // Desaprobado
    return 'bg-blue-50 text-blue-700 border-blue-200';                 // Aprobado
};

// 2. Variable para saber si podemos editar (Ahora liberada sin validación de sílabo)
const canEdit = computed(() => !props.section.is_closed);

// Función para convertir el promedio (1-5) a nota vigesimal (0-20)
const getVigesimal = (avg) => {
    if (!avg || avg === 0) return '-';

    let finalAvg = avg;
    const fraction = avg - Math.floor(avg);
    if (fraction >= 0.95) finalAvg = Math.ceil(avg);

    if (finalAvg >= 1.0 && finalAvg <= 1.1) return '01';
    if (finalAvg <= 1.3) return '02';
    if (finalAvg <= 1.5) return '03';
    if (finalAvg <= 1.7) return '04';
    if (finalAvg <= 1.9) return '05';
    if (finalAvg <= 2.1) return '06';
    if (finalAvg <= 2.3) return '07';
    if (finalAvg <= 2.5) return '08';
    if (finalAvg <= 2.7) return '09';
    if (finalAvg <= 2.9) return '10';
    if (finalAvg >= 3.0 && finalAvg <= 3.2) return '11';
    if (finalAvg <= 3.5) return '12';
    if (finalAvg <= 3.7) return '13';
    if (finalAvg <= 3.9) return '14';
    if (finalAvg <= 4.1) return '15';
    if (finalAvg <= 4.3) return '16';
    if (finalAvg <= 4.5) return '17';
    if (finalAvg <= 4.7) return '18';
    if (finalAvg <= 4.9) return '19';
    if (finalAvg === 5.0) return '20';

    return '-';
};

const calculateAverage = (student) => {
    const values = Object.values(student.competencies)
        .map(c => {
            const scale = props.gradeScales.find(s => s.id === c.grade_scale_id);
            return scale ? parseFloat(scale.numeric_equivalent) : null;
        })
        .filter(v => v !== null);

    if (values.length === 0) return 0;

    const sum = values.reduce((a, b) => a + b, 0);
    return sum / values.length;
};

const form = useForm({
    students: props.students.map(student => ({
        detail_id: student.detail_id,
        person_id: student.person_id,
        student_name: student.student_name,
        final_score: student.final_score || 0,
        competencies: props.section.course.competencies.reduce((acc, comp) => {
            const existingGrade = student.grades.find(g => g.competency_id === comp.id);
            acc[comp.id] = {
                competency_id: comp.id,
                grade_scale_id: existingGrade ? existingGrade.grade_scale_id : '',
            };
            return acc;
        }, {})
    }))
});

const save = () => {
    form.post(route('teacher.grades.store', props.section.id), {
        preserveScroll: true,
        onSuccess: () => alert('Notas guardadas con éxito')
    });
};
</script>

<template>
    <div class="p-8 max-w-full mx-auto">
        <!-- Header con info del curso -->
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black text-gray-900 uppercase italic">{{ section.course.name }}</h1>
                <p class="text-gray-500 font-mono">SECCIÓN {{ section.name }} | TIPO: {{ evaluationType === 'competency' ? 'POR COMPETENCIAS (DCBN)' : 'VIGESIMAL (LEGACY)' }}</p>
            </div>

            <!-- Si el acta ya está cerrada, mostramos el badge de éxito -->
            <div v-if="section.is_closed" class="bg-gray-100 p-4 rounded-xl border-2 border-dashed border-gray-300 text-center">
                <p class="text-gray-500 font-bold uppercase text-xs">
                    ✅ Esta acta fue cerrada el {{ new Date(section.acta_close_date).toLocaleDateString() }}
                </p>
                <p class="text-[10px] text-gray-400 font-mono mt-1">Nº Registro: {{ section.acta_number }}</p>
                <a
                :href="route('teacher.sections.pdf', section.id)"
                target="_blank"
                class="bg-red-600 text-white px-8 py-3 rounded-xl font-black shadow-lg hover:bg-red-700 transition flex items-center mt-2">
                    DESCARGAR ACTA OFICIAL (PDF)
                </a>
            </div>
        </div>

        <!-- Tabla de Calificaciones (Ahora se muestra siempre de forma directa) -->
        <div>
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-900 text-white text-[10px] tracking-widest uppercase font-black">
                            <th class="p-4 border-r border-gray-800">Nº</th>
                            <th class="p-4 border-r border-gray-800">Apellidos y Nombres</th>

                            <template v-if="evaluationType === 'competency'">
                                <th v-for="comp in section.course.competencies" :key="comp.id"
                                    class="p-4 text-center border-r border-gray-800 min-w-[120px]">
                                    {{ comp.code }}
                                    <div class="lowercase font-normal text-[8px] opacity-60 leading-tight truncate w-24 mx-auto">
                                        {{ comp.description }}
                                    </div>
                                </th>
                            </template>
                            <th class="p-4 text-center bg-gray-50 border-l border-gray-200 text-blue-600">Promedio (1-5)</th>
                            <th class="p-4 text-center bg-gray-50 border-l border-gray-200 text-red-600 font-black">Nota Final (0-20)</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="(student, idx) in form.students" :key="student.detail_id" class="hover:bg-blue-50/50 transition">
                            <td class="p-4 text-gray-400 font-mono text-xs">{{ idx + 1 }}</td>
                            <td class="p-4 border-r">
                                <span class="font-bold text-gray-900 uppercase text-xs">
                                    {{ student.student_name }}
                                </span>
                            </td>

                            <template v-if="evaluationType === 'competency'">
                                <td v-for="comp in section.course.competencies" :key="comp.id" class="p-2 border-r">
                                    <select v-model="student.competencies[comp.id].grade_scale_id"
                                            :disabled="!canEdit"
                                            :class="getGradeColorClass(student.competencies[comp.id].grade_scale_id)"
                                            class="w-full text-[10px] font-black border-2 rounded-xl py-2 px-1 focus:ring-0 transition-colors cursor-pointer text-center">
                                        <option value="">-</option>
                                        <option v-for="scale in gradeScales" :key="scale.id" :value="scale.id">
                                            {{ scale.name }}
                                        </option>
                                    </select>
                                </td>

                                <td class="p-4 text-center border-l bg-gray-50/30 font-mono text-gray-500 text-xs">
                                    {{ calculateAverage(student).toFixed(2) }}
                                </td>

                                <td class="p-4 text-center border-l bg-gray-50/50">
                                    <span class="text-xl font-black"
                                        :class="getVigesimal(calculateAverage(student)) >= 11 ? 'text-green-600' : 'text-red-600'">
                                        {{ getVigesimal(calculateAverage(student)) }}
                                    </span>
                                </td>
                            </template>

                            <template v-else>
                                <td class="p-2 text-center border-r">
                                    <input type="number" v-model="student.final_score"
                                        :disabled="!canEdit"
                                        :class="student.final_score >= 11 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'"
                                        class="w-16 text-center font-black text-sm rounded-xl border-2 focus:ring-0 transition-colors"
                                        min="0" max="20" />
                                </td>
                                <td class="bg-gray-100/50 border-l"></td>
                                <td class="p-4 text-center border-l bg-gray-50/50">
                                    <span class="text-xl font-black" :class="student.final_score >= 11 ? 'text-green-600' : 'text-red-600'">
                                        {{ String(student.final_score).padStart(2, '0') }}
                                    </span>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Botones de Acción (Solo si no está cerrada) -->
            <div v-if="canEdit" class="mt-8 flex justify-end space-x-4">
                <button @click="save" :disabled="form.processing"
                        class="bg-blue-600 text-white px-8 py-3 rounded-xl font-black shadow-lg hover:bg-blue-700 transition">
                    {{ form.processing ? 'GUARDANDO...' : 'GUARDAR BORRADOR' }}
                </button>
                <button @click="closeActa"
                        class="bg-red-600 text-white px-8 py-3 rounded-xl font-black shadow-lg hover:bg-red-700 transition transform hover:scale-105">
                    🔒 CERRAR ACTA DEFINITIVA
                </button>
            </div>
        </div>
    </div>
</template>

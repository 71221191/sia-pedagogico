<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Check, ArrowLeft, Save, ShieldCheck } from 'lucide-vue-next';

const props = defineProps({
    section: Object,
    catalog: Array, // Asumimos que trae la relación .domain
    selectedIds: Array
});

// Agrupamos el catálogo por dominio para el diseño
const groupedCatalog = computed(() => {
    // props.catalog ya viene como [ {name: 'Dominio 1', competencies: [...]}, ... ]
    return props.catalog || [];
});

const form = useForm({
    competencies: [...props.selectedIds] // Copia limpia para evitar referencias
});

const toggleCompetency = (id) => {
    const index = form.competencies.indexOf(id);
    if (index > -1) {
        form.competencies.splice(index, 1);
    } else {
        form.competencies.push(id);
    }
};

const submit = () => {
    form.post(route('teacher.sections.set-competencies', props.section.id));
};
</script>

<template>
    <Head title="Configurar Evaluación" />
    <div class="p-8 max-w-4xl mx-auto">
        <Link :href="route('teacher.sections.index')" class="flex items-center text-sm text-gray-500 mb-4 hover:text-blue-600 font-bold uppercase tracking-widest">
            <ArrowLeft class="w-4 h-4 mr-1" /> Volver
        </Link>

        <h1 class="text-3xl font-black text-gray-900 mb-2 uppercase">{{ section.course.name }}</h1>
        <p class="text-gray-500 mb-8 font-medium italic">Seleccione las competencias que evaluará en este curso durante el ciclo.</p>

        <form @submit.prevent="submit" class="space-y-10 pb-20">
            <!-- RECORREMOS CADA DOMINIO -->
            <div v-for="domain in groupedCatalog" :key="domain.name" class="space-y-4">

                <!-- ENCABEZADO DEL DOMINIO -->
                <div class="flex items-center gap-4 ml-2">
                    <div class="bg-indigo-600 p-2 rounded-lg text-white">
                        <ShieldCheck class="w-4 h-4" />
                    </div>
                    <h2 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] flex-1">
                        {{ domain.name }}
                    </h2>
                    <div class="h-[1px] bg-indigo-100 flex-1"></div>
                </div>

                <!-- LISTA DE COMPETENCIAS DEL DOMINIO -->
                <div class="grid grid-cols-1 gap-3">
                    <div v-for="comp in domain.competencies" :key="comp.id"
                        @click="toggleCompetency(comp.id)"
                        :class="form.competencies.includes(comp.id)
                            ? 'border-indigo-600 bg-indigo-50/50 shadow-md ring-4 ring-indigo-50'
                            : 'border-gray-100 bg-white hover:border-indigo-200'"
                        class="group p-6 rounded-[2rem] border-2 transition-all cursor-pointer flex items-start gap-6">

                        <!-- Checkbox Custom Pro -->
                        <div class="mt-1 flex-shrink-0">
                            <div :class="form.competencies.includes(comp.id) ? 'bg-indigo-600 border-indigo-600 scale-110' : 'bg-white border-gray-300'"
                                class="w-6 h-6 border-2 rounded-xl flex items-center justify-center transition-all duration-300">
                                <Check v-if="form.competencies.includes(comp.id)" class="w-4 h-4 text-white" stroke-width="4" />
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ comp.code }}</span>
                            </div>
                            <p class="text-sm font-medium text-gray-600 leading-relaxed">{{ comp.description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTÓN DE GUARDADO FLOTANTE O AL FINAL -->
            <div class="sticky bottom-8 flex justify-center">
                <button :disabled="form.processing"
                        class="bg-gray-900 text-white px-12 py-5 rounded-[2rem] font-black shadow-2xl hover:bg-indigo-600 transition-all transform hover:scale-105 active:scale-95 flex items-center gap-3">
                    <Save class="w-6 h-6" />
                    {{ form.processing ? 'SINCRONIZANDO...' : 'CONFIRMAR MAPA CURRICULAR' }}
                </button>
            </div>
        </form>
    </div>
</template>

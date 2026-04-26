<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { 
    Upload, 
    FileSpreadsheet, 
    CreditCard, 
    Users, 
    BookOpen,
    AlertCircle,
    CheckCircle2
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

const formPayments = useForm({ file: null });
const formStudents = useForm({ file: null });
const formCourses = useForm({ file: null });

const submitLegacyPayments = () => {
    formPayments.post(route('admin.import.payments-legacy'), {
        onSuccess: () => {
            formPayments.reset();
            alert('¡Migración de pagos completada!');
        },
    });
};

// ... Aquí irían las otras funciones de envío para alumnos y cursos
</script>

<template>
    <Head title="Centro de Importación Masiva" />
    <AppLayout>
        <div class="p-8 max-w-5xl mx-auto bg-gray-50 min-h-screen">
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-8 flex items-center">
                <Upload class="mr-3 w-8 h-8 text-indigo-600" />
                Centro de Carga Masiva (Excel)
            </h1>

            <div class="grid grid-cols-1 gap-8">
                
                <!-- SECCIÓN 1: PAGOS HISTÓRICOS -->
                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-gray-100 transition hover:border-indigo-300">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-black text-gray-800 uppercase flex items-center">
                                <CreditCard class="mr-3 w-6 h-6 text-emerald-500" />
                                Migración de Pagos Históricos (2013 - 2025)
                            </h2>
                            <p class="text-sm text-gray-500 italic mt-1">Use esta opción para cargar los 4,500 registros del sistema anterior.</p>
                        </div>
                        <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">Contabilidad</span>
                    </div>

                    <form @submit.prevent="submitLegacyPayments" class="bg-gray-50 p-6 rounded-3xl border-2 border-dashed border-gray-200">
                        <div class="flex flex-col md:flex-row items-center gap-4">
                            <input type="file" @input="formPayments.file = $event.target.files[0]" accept=".xlsx,.xls" 
                                   class="flex-1 text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-600 file:text-white file:font-bold cursor-pointer" />
                            
                            <button :disabled="formPayments.processing" 
                                    class="w-full md:w-auto bg-gray-900 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase shadow-lg hover:bg-indigo-600 transition flex items-center justify-center">
                                <FileSpreadsheet class="w-4 h-4 mr-2" />
                                {{ formPayments.processing ? 'PROCESANDO FILAS...' : 'INICIAR MIGRACIÓN' }}
                            </button>
                        </div>
                        <div v-if="formPayments.errors.file" class="text-red-500 text-[10px] font-bold mt-2 uppercase">{{ formPayments.errors.file }}</div>
                    </form>
                </div>

                <!-- SECCIÓN 2: ALUMNOS Y CURSOS (Puedes añadir los otros forms aquí igual que el de arriba) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 opacity-60">
                     <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 flex items-center justify-center italic text-gray-400 text-xs">
                         Otras importaciones (Alumnos/Cursos)
                     </div>
                     <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 flex items-center justify-center italic text-gray-400 text-xs">
                         Próximamente
                     </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
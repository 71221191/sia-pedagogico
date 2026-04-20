<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    payments: Object,
    currentStatus: String
});

// Estado para el Modal de Revisión
const isModalOpen = ref(false);
const selectedPayment = ref(null);
const rejectionMode = ref(false);

const form = useForm({
    status: '',
    rejection_reason: '',
    official_receipt: null, // <--- Nueva variable para el archivo del tesorero
});

const openReviewModal = (payment) => {
    selectedPayment.value = payment;
    rejectionMode.value = false;
    form.rejection_reason = '';
    isModalOpen.value = true;
};

const processPayment = (status) => {
    // Si es rechazo y no hemos escrito el motivo, activamos el campo primero
    if (status === 'rejected' && !showFeedbackField.value) {
        showFeedbackField.value = true;
        return;
    }

    form.status = status;

    // --- LA SOLUCIÓN SENIOR ---
    // Usamos transform para meter el _method: 'PATCH' dentro de los datos
    form.transform((data) => ({
        ...data,
        _method: 'PATCH',
    })).post(route('tesoreria.payments.verify', selectedPayment.value.id), {
        forceFormData: true, // Obligatorio para enviar archivos
        onSuccess: () => {
            isModalOpen.value = false;
            selectedPayment.value = null;
            alert('¡Operación de tesorería realizada!');
        },
        onError: () => {
            alert('Hubo un error al procesar el pago. Revisa los datos.');
        }
    });
};

// Función para saber si el voucher es PDF
const isPDF = (path) => path.toLowerCase().endsWith('.pdf');

const changeTab = (status) => {
    router.get(route('tesoreria.payments.index'), { status }, { preserveState: true });
};
</script>

<template>
    <div class="p-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-black text-gray-900 mb-8 uppercase tracking-tight">Validación de Pagos - Tesorería</h1>

            <!-- Tabs de Navegación -->
            <div class="flex space-x-4 mb-6">
                <button v-for="status in ['pending', 'approved', 'rejected']" :key="status"
                    @click="changeTab(status)"
                    :class="currentStatus === status ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-100'"
                    class="px-6 py-2 rounded-xl font-bold transition-all uppercase text-xs tracking-widest border">
                    {{ status === 'pending' ? 'Pendientes' : (status === 'approved' ? 'Aprobados' : 'Rechazados') }}
                </button>
            </div>

            <!-- Tabla de Pagos -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-900 text-white uppercase text-[10px] tracking-widest font-black">
                            <th class="p-4">Alumno / DNI</th>
                            <th class="p-4">Nº Operación</th>
                            <th class="p-4">Monto</th>
                            <th class="p-4">Fecha Subida</th>
                            <th class="p-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        <tr v-for="payment in payments.data" :key="payment.id" class="hover:bg-blue-50/50 transition">
                            <td class="p-4">
                                <div class="text-gray-900 font-bold uppercase">{{ payment.person.names }} {{ payment.person.last_name_p }}</div>
                                <div class="text-gray-500 text-xs tracking-tighter">DNI: {{ payment.person.dni }}</div>
                            </td>
                            <td class="p-4 font-mono font-black text-blue-700">{{ payment.operation_number }}</td>
                            <td class="p-4 text-gray-900 font-bold">S/. {{ payment.amount }}</td>
                            <td class="p-4 text-gray-500 text-xs italic">{{ new Date(payment.created_at).toLocaleString() }}</td>
                            <td class="p-4 text-center">
                                <button @click="openReviewModal(payment)"
                                    class="bg-gray-900 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-700 transition">
                                    REVISAR VOUCHER
                                </button>
                            </td>
                        </tr>
                        <tr v-if="payments.data.length === 0">
                            <td colspan="5" class="p-10 text-center text-gray-400 italic font-medium">No hay pagos en esta categoría.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL DE REVISIÓN (THE REAL THING) -->
        <div v-if="isModalOpen" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
                <!-- Header Modal -->
                <div class="p-6 border-b flex justify-between items-center bg-gray-50">
                    <div>
                        <h2 class="font-black text-xl text-gray-900 uppercase">Revisión de Comprobante</h2>
                        <p class="text-sm text-gray-500">Operación: {{ selectedPayment.operation_number }} | Monto: S/. {{ selectedPayment.amount }}</p>
                    </div>
                    <button @click="isModalOpen = false" class="text-gray-400 hover:text-red-500 font-black text-2xl">×</button>
                </div>

                <!-- Cuerpo Modal: Visor de Documento -->
                <div class="flex-1 overflow-y-auto p-6 bg-gray-200 flex justify-center items-start">
                    <div class="w-full max-w-3xl shadow-2xl rounded-lg overflow-hidden bg-white">
                        <!-- Si es PDF -->
                        <iframe v-if="isPDF(selectedPayment.voucher_image_path)"
                            :src="'/storage/' + selectedPayment.voucher_image_path"
                            class="w-full h-150" frameborder="0"></iframe>
                        <!-- Si es Imagen -->
                        <img v-else :src="'/storage/' + selectedPayment.voucher_image_path"
                            class="w-full h-auto" />
                    </div>
                </div>

                <!-- Dentro del Modal en Treasury/Index.vue -->
                <div class="p-8 border-t bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- LADO IZQUIERDO: RECHAZO -->
                        <div class="space-y-4 border-r border-gray-100 pr-8">
                            <h4 class="text-[10px] font-black text-red-600 uppercase tracking-widest">Zona de Observaciones</h4>
                            <textarea v-model="form.rejection_reason"
                                    class="w-full border-gray-200 rounded-2xl text-sm p-4 focus:ring-red-500"
                                    placeholder="Indique el motivo si va a rechazar el pago..."></textarea>
                            <button @click="processPayment('rejected')"
                                    class="w-full bg-red-50 text-red-700 py-3 rounded-xl font-bold text-xs uppercase hover:bg-red-100 transition">
                                Rechazar Comprobante
                            </button>
                        </div>

                        <!-- LADO DERECHO: APROBACIÓN Y CANJE -->
                        <div class="space-y-4">
                            <h4 class="text-[10px] font-black text-green-600 uppercase tracking-widest">Oficialización (Canje de Boleta)</h4>

                            <div class="p-4 bg-green-50 rounded-2xl border border-green-100">
                                <label class="block text-[10px] font-bold text-green-700 uppercase mb-2">Subir Boleta Oficial del IESP (PDF/Imagen)</label>
                                <input type="file"
                                    @input="form.official_receipt = $event.target.files[0]"
                                    accept=".pdf,image/*"
                                    class="w-full text-xs text-green-800 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-green-600 file:text-white file:font-bold" />
                            </div>

                            <button @click="processPayment('approved')"
                                    :disabled="form.processing"
                                    class="w-full bg-green-600 text-white py-4 rounded-2xl font-black text-xs uppercase shadow-lg shadow-green-100 hover:bg-green-700 transition">
                                {{ form.processing ? 'Procesando...' : 'Aprobar y Registrar Boleta' }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

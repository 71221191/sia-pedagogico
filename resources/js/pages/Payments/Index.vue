<script setup>
import { useForm, Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Trash2, Eye, CheckCircle, FileText,
    AlertTriangle, Upload, CreditCard,
    Pencil, Lock, XCircle
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    payments: Array,
    concepts: Array
});

// Referencia para hacer scroll al formulario
const formRef = ref(null);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    payment_concept_id: '',
    amount: '',
    operation_number: '',
    voucher: null,
});

const onConceptChange = () => {
    const selected = props.concepts.find(c => c.id === form.payment_concept_id);
    form.amount = selected ? selected.amount : '';
};

const submit = () => {
    if (isEditing.value) {
        // Usamos transform para añadir el campo _method justo antes de enviar
        form.transform((data) => ({
            ...data,
            _method: 'PUT', // <--- Esto le dice a Laravel: "Trátame como un PUT"
        })).post(route('payments.update', editingId.value), {
            forceFormData: true, // Vital para enviar archivos
            onSuccess: () => {
                resetForm();
                alert('✅ Pago actualizado correctamente.');
            },
        });
    } else {
        // Guardado normal (POST)
        form.post(route('payments.store'), {
            forceFormData: true,
            onSuccess: () => form.reset(),
        });
    }
};

const editPayment = (payment) => {
    isEditing.value = true;
    editingId.value = payment.id;
    form.payment_concept_id = payment.payment_concept_id;
    form.operation_number = payment.operation_number;
    form.amount = payment.amount;

    // UX MÓVIL: Scroll suave hacia arriba para que el usuario vea el formulario
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const resetForm = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
};

const deletePayment = (id) => {
    if (confirm('¿Estás seguro? Esta acción quedará en los registros de auditoría.')) {
        router.delete(route('payments.destroy', id), { preserveScroll: true });
    }
};

const getStatusClass = (status) => {
    if (status === 'approved') return 'bg-green-100 text-green-800 border-green-200';
    if (status === 'rejected') return 'bg-red-100 text-red-800 border-red-200';
    return 'bg-amber-100 text-amber-800 border-amber-200';
};
</script>

<template>
    <Head title="Mis Pagos" />
    <AppLayout>
        <div class="max-w-6xl mx-auto p-4 md:p-8 bg-gray-50 min-h-screen">
            <!-- Título Principal -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter flex items-center">
                    <CreditCard class="mr-3 w-8 h-8 text-indigo-600" /> Centro de Pagos
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- FORMULARIO (Se ilumina si está editando) -->
                <div class="lg:col-span-1">
                    <div :class="isEditing ? 'border-indigo-500 ring-4 ring-indigo-50' : 'border-gray-100'"
                         class="bg-white p-6 rounded-[2rem] shadow-xl border transition-all duration-500 sticky top-8">

                        <div class="flex justify-between items-center mb-6">
                            <h2 class="font-black text-gray-700 uppercase text-xs tracking-widest flex items-center">
                                <component :is="isEditing ? Pencil : Upload" class="mr-2 w-4 h-4 text-blue-500" />
                                {{ isEditing ? 'Editando Pago' : 'Registrar Nuevo Pago' }}
                            </h2>
                            <button v-if="isEditing" @click="resetForm" class="text-gray-400 hover:text-red-500"><XCircle class="w-5 h-5"/></button>
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <!-- Campos del form (se mantienen igual que antes) -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Trámite</label>
                                <select v-model="form.payment_concept_id" @change="onConceptChange" class="w-full border-gray-100 bg-gray-50 rounded-xl text-sm font-bold">
                                    <option v-for="c in concepts" :key="c.id" :value="c.id">{{ c.name }}</option>
                                </select>
                            </div>
                            <!-- ... montos y operación ... -->
                            <input v-model="form.operation_number" type="text" placeholder="Operación" class="w-full border-gray-100 bg-gray-50 rounded-xl text-sm" />
                            <input type="file" @input="form.voucher = $event.target.files[0]" class="w-full text-[10px]" />

                            <button :disabled="form.processing"
                                    :class="isEditing ? 'bg-indigo-600' : 'bg-gray-900'"
                                    class="w-full text-white py-4 rounded-2xl font-black text-xs uppercase shadow-lg transition transform active:scale-95">
                                {{ isEditing ? 'Guardar Cambios' : 'Enviar Pago' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- LISTADO -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-xl border border-gray-100 rounded-[2.5rem] overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-900 text-white uppercase text-[10px] font-black tracking-widest">
                                    <th class="p-4">Concepto / Registro</th>
                                    <th class="p-4 text-center">Documentos</th>
                                    <th class="p-4 text-center">Estado</th>
                                    <th class="p-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="payment in payments" :key="payment.id" class="hover:bg-gray-50 transition">
                                    <td class="p-4">
                                        <div class="text-xs font-black text-gray-800 uppercase">{{ payment.payment_concept?.name }}</div>
                                        <!-- FECHA DE PAGO (Corregida) -->
                                        <div class="text-[9px] text-gray-400 font-mono mt-1">
                                            <!-- Si es importado, mostramos paid_at sin hora. Si no, created_at con hora -->
                                            <template v-if="payment.is_imported">
                                                Pagado el: {{ new Date(payment.paid_at).toLocaleDateString() }}
                                            </template>
                                            <template v-else>
                                                {{ new Date(payment.created_at).toLocaleDateString() }} - {{ new Date(payment.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                                            </template>
                                        </div>

                                    </td>
                                    <td class="p-4">
                                        <div class="flex flex-col space-y-2">
                                            <a :href="'/storage/' + payment.voucher_image_path" target="_blank" class="text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg text-center">MI VOUCHER</a>
                                            <!-- BOLETA DEL TESORERO (Aquí se verá lo que él canjea) -->
                                            <a v-if="payment.official_receipt_path" :href="'/storage/' + payment.official_receipt_path" target="_blank"
                                               class="text-[9px] font-black text-white bg-green-600 px-2 py-1 rounded-lg text-center flex items-center justify-center">
                                               <FileText class="w-3 h-3 mr-1" /> BOLETA OFICIAL
                                            </a>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span :class="getStatusClass(payment.status)" class="px-3 py-1 rounded-full text-[9px] font-black uppercase border">{{ payment.status }}</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button v-if="payment.status !== 'approved'" @click="editPayment(payment)" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-all"><Pencil class="w-4 h-4"/></button>
                                            <button v-if="payment.status === 'pending'" @click="deletePayment(payment.id)" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-all"><Trash2 class="w-4 h-4"/></button>
                                            <Lock v-if="payment.status === 'approved'" class="w-4 h-4 text-slate-200" />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

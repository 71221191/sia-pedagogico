<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Head, router } from '@inertiajs/vue3';
import { Clock, Save, AlertCircle, CheckCircle } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    timeSlots: any[],
    currentAvailability: any[],
    days: any[]
}>();

// Estado local para manejar los clics en la cuadrícula
// Guardamos las llaves como "dia-slotId" para búsqueda rápida
const unavailableSet = ref(new Set(
    props.currentAvailability.map(a => `${a.day_of_week}-${a.time_slot_id}`)
));

const toggleSlot = (dayId: number, slotId: number) => {
    const key = `${dayId}-${slotId}`;
    if (unavailableSet.value.has(key)) {
        unavailableSet.value.delete(key);
    } else {
        unavailableSet.value.add(key);
    }
};

const submit = () => {
    // Convertimos el Set a un array de objetos para mandar a Laravel
    const list = Array.from(unavailableSet.value).map(item => {
        const [day, slotId] = item.split('-');
        return { day: parseInt(day), slot_id: parseInt(slotId) };
    });

    router.post(route('teacher.availability.store'), { unavailable_slots: list }, {
        onSuccess: () => alert('✅ Disponibilidad guardada.')
    });
};
</script>

<template>
    <Head title="Mi Disponibilidad" />
    <AppLayout>
        <div class="p-8 max-w-6xl mx-auto bg-gray-50 min-h-screen">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Mi Disponibilidad Horaria</h1>
                    <p class="text-gray-500 italic">Marque en <span class="text-red-500 font-bold">ROJO</span> las horas en las que <b>NO</b> puede dictar clases.</p>
                </div>
                <button @click="submit" class="bg-gray-900 text-white px-8 py-3 rounded-2xl font-black uppercase text-xs shadow-xl hover:bg-indigo-600 transition flex items-center">
                    <Save class="w-4 h-4 mr-2" /> Guardar Mi Horario
                </button>
            </div>

            <div class="bg-white rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-900 text-white text-[10px] uppercase font-black tracking-widest">
                            <th class="p-4 border-r border-gray-800">Hora / Bloque</th>
                            <th v-for="day in days" :key="day.id" class="p-4 text-center border-r border-gray-800">{{ day.name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="slot in timeSlots" :key="slot.id" :class="slot.is_break ? 'bg-gray-100' : ''">
                            <td class="p-4 border-r text-center">
                                <div class="text-[10px] font-black text-gray-800 leading-tight">
                                    {{ slot.is_break ? 'RECREO' : 'BLOQUE ' + slot.order }}
                                </div>
                                <div class="text-[9px] text-gray-400 font-mono">{{ slot.start_time.substring(0,5) }} - {{ slot.end_time.substring(0,5) }}</div>
                            </td>

                            <template v-if="!slot.is_break">
                                <td v-for="day in days" :key="day.id"
                                    @click="toggleSlot(day.id, slot.id)"
                                    :class="unavailableSet.has(`${day.id}-${slot.id}`) ? 'bg-red-500 border-red-600' : 'bg-white hover:bg-green-50'"
                                    class="p-4 border-r border-b cursor-pointer transition-all relative group">

                                    <div class="flex items-center justify-center">
                                        <XCircle v-if="unavailableSet.has(`${day.id}-${slot.id}`)" class="text-white w-5 h-5" />
                                        <CheckCircle v-else class="text-gray-100 group-hover:text-green-200 w-5 h-5" />
                                    </div>
                                </td>
                            </template>
                            <td v-else :colspan="days.length" class="text-center text-[10px] font-black text-gray-300 tracking-[1em] uppercase border-b">
                                Descanso Institucional
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

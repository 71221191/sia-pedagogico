<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import { DoorOpen, Plus, Edit, Trash2, Users } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    classrooms: any[]
}>();

const isEditing = ref(false);
const editingId = ref<number | null>(null);

const form = useForm({
    name: '',
    type: 'aula',
    capacity: 30,
});

const submit = () => {
    if (isEditing.value && editingId.value) {
        form.put(route('admin.classrooms.update', editingId.value), {
            onSuccess: () => resetForm(),
        });
    } else {
        form.post(route('admin.classrooms.store'), {
            onSuccess: () => form.reset(),
        });
    }
};

const edit = (item: any) => {
    isEditing.value = true;
    editingId.value = item.id;
    form.name = item.name;
    form.type = item.type;
    form.capacity = item.capacity;
};

const resetForm = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
};
</script>

<template>
    <Head title="Gestión de Ambientes" />
    <AppLayout>
        <div class="p-8 max-w-6xl mx-auto bg-gray-50 min-h-screen">
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-8 flex items-center">
                <DoorOpen class="mr-3 w-8 h-8 text-indigo-600" />
                Infraestructura y Ambientes
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- FORMULARIO -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-xl border border-gray-100 sticky top-8">
                        <h2 class="font-bold text-gray-700 mb-6 uppercase text-sm tracking-widest flex items-center">
                            <Plus v-if="!isEditing" class="mr-2 w-4 h-4 text-blue-500" />
                            <Edit v-else class="mr-2 w-4 h-4 text-orange-500" />
                            {{ isEditing ? 'Editar Ambiente' : 'Nuevo Ambiente' }}
                        </h2>

                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Nombre (Nro o Sigla)</label>
                                <input v-model="form.name" type="text" placeholder="Ej: Aula 10" class="w-full border-gray-200 rounded-xl focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Tipo de Ambiente</label>
                                <select v-model="form.type" class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 text-sm font-bold uppercase">
                                    <option value="aula">AULA COMÚN</option>
                                    <option value="laboratorio">LABORATORIO</option>
                                    <option value="taller">TALLER</option>
                                    <option value="otros">OTROS</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Capacidad Máxima</label>
                                <div class="flex items-center space-x-3">
                                    <input v-model="form.capacity" type="number" class="w-24 border-gray-200 rounded-xl text-center font-bold" />
                                    <span class="text-xs text-gray-400 italic">Personas</span>
                                </div>
                            </div>

                            <button :disabled="form.processing" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-black text-[10px] uppercase shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                                {{ isEditing ? 'Guardar Cambios' : 'Registrar Ambiente' }}
                            </button>
                            <button v-if="isEditing" @click="resetForm" type="button" class="w-full text-gray-400 text-[10px] font-bold uppercase mt-2">Cancelar</button>
                        </form>
                    </div>
                </div>

                <!-- LISTADO -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-900 text-white text-[10px] uppercase font-black tracking-widest">
                                    <th class="p-4">Nombre / Identificador</th>
                                    <th class="p-4 text-center">Tipo</th>
                                    <th class="p-4 text-center">Aforo</th>
                                    <th class="p-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="room in classrooms" :key="room.id" class="hover:bg-indigo-50/30 transition group">
                                    <td class="p-4 font-bold text-gray-800 uppercase text-xs">{{ room.name }}</td>
                                    <td class="p-4 text-center">
                                        <span :class="{
                                            'bg-blue-100 text-blue-700': room.type === 'aula',
                                            'bg-purple-100 text-purple-700': room.type === 'laboratorio',
                                            'bg-amber-100 text-amber-700': room.type === 'taller',
                                            'bg-gray-100 text-gray-700': room.type === 'otros',
                                        }" class="px-2 py-0.5 rounded text-[8px] font-black uppercase border">
                                            {{ room.type }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center text-xs font-mono">
                                        <div class="flex items-center justify-center text-gray-400">
                                            <Users class="w-3 h-3 mr-1" /> {{ room.capacity }}
                                        </div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition">
                                            <button @click="edit(room)" class="text-blue-500 p-1"><Edit class="w-4 h-4" /></button>
                                            <button @click="$inertia.delete(route('admin.classrooms.destroy', room.id))" class="text-red-400 p-1"><Trash2 class="w-4 h-4" /></button>
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

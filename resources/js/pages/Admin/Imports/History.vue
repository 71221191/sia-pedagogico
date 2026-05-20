<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { History, Download, FileSpreadsheet, CheckCircle, XCircle, Info, ArrowLeft } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

defineProps<{ imports: any }>();

const getStatusColor = (type: string) => {
    const colors: any = {
        'active_students': 'bg-blue-100 text-blue-700',
        'teachers': 'bg-purple-100 text-purple-700',
        'grades': 'bg-emerald-100 text-emerald-700',
    };
    return colors[type] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <Head title="Historial de Importaciones" />
    <AppLayout>
        <div class="p-8 max-w-7xl mx-auto bg-gray-50 min-h-screen">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <Link :href="route('admin.imports.index')" class="text-xs font-black text-gray-400 uppercase flex items-center mb-2 hover:text-indigo-600">
                        <ArrowLeft class="w-4 h-4 mr-1" /> Volver a importar
                    </Link>
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter flex items-center">
                        <History class="mr-3 w-8 h-8 text-indigo-600" /> Historial de Cargas
                    </h1>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-900 text-white text-[10px] uppercase font-black tracking-widest">
                            <th class="p-6">Fecha y Archivo</th>
                            <th class="p-6">Tipo</th>
                            <th class="p-6 text-center">Resultados</th>
                            <th class="p-6 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="imp in imports.data" :key="imp.id" class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl mr-4">
                                        <FileSpreadsheet class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-800 leading-none mb-1">{{ imp.filename }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">{{ new Date(imp.created_at).toLocaleString() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <span :class="getStatusColor(imp.import_type)" class="px-3 py-1 rounded-full text-[9px] font-black uppercase">
                                    {{ imp.import_type }}
                                </span>
                            </td>
                            <td class="p-6">
                                <div class="flex justify-center gap-4">
                                    <div class="text-center">
                                        <span class="block text-[10px] font-black text-emerald-500">{{ imp.created_count }}</span>
                                        <span class="text-[8px] text-gray-400 font-bold uppercase">Nuevos</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="block text-[10px] font-black text-blue-500">{{ imp.updated_count }}</span>
                                        <span class="text-[8px] text-gray-400 font-bold uppercase">Actualiz.</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="block text-[10px] font-black text-rose-500">{{ imp.error_count }}</span>
                                        <span class="text-[8px] text-gray-400 font-bold uppercase">Errores</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6 text-right">
                                <a :href="route('admin.imports.download', imp.id)"
                                   class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-[10px] font-black uppercase rounded-xl hover:bg-indigo-600 transition-all shadow-lg">
                                    <Download class="w-3 h-3 mr-2" /> Reporte Excel
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

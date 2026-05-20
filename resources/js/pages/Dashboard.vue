<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import {
    User, Mail, Phone, MapPin, GraduationCap,
    Award, ShieldCheck, Briefcase, Users
} from 'lucide-vue-next';

const props = defineProps({
    studentProfile: Object
});
</script>

<template>
    <Head title="Mi Panel Académico" />

    <AppLayout>
        <div class="p-6 md:p-10 max-w-7xl mx-auto bg-gray-50 min-h-screen">

            <!-- 1. CABECERA DE IDENTIDAD (Digital ID) -->
            <div v-if="studentProfile" class="bg-white rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden mb-10 transition-all hover:shadow-2xl">
                <div class="bg-indigo-900 h-32 w-full relative">
                    <!-- Decoración fondo -->
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                </div>

                <div class="px-10 pb-10 -mt-16 relative z-10 flex flex-col md:flex-row items-center md:items-end gap-6">
                    <!-- Foto Oficial (Admin Only upload) -->
                    <div class="w-40 h-40 rounded-3xl border-8 border-white bg-gray-200 shadow-2xl overflow-hidden flex-shrink-0">
                        <img v-if="studentProfile.photo" :src="studentProfile.photo" class="w-full h-full object-cover" />
                        <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                            <User class="w-16 h-16" />
                        </div>
                    </div>

                    <div class="flex-1 text-center md:text-left mb-4">
                        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">{{ studentProfile.full_name }}</h1>
                        <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-2">
                            <span class="px-4 py-1 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-black uppercase tracking-widest border border-indigo-200">
                                {{ studentProfile.academic.program }} <!-- ANTES: program_name -->
                            </span>
                            <span class="px-4 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-gray-200">
                                Ciclo: {{ studentProfile.academic.cycle }} <!-- ANTES: current_cycle -->
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En Dashboard.vue, dentro del grid de columnas -->
            <div v-if="studentProfile" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- COLUMNA 1: MÉRITO Y BIOGRAFÍA -->
                <div class="space-y-8">
                    <!-- Card de Mérito -->
                    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-lg relative overflow-hidden">
                        <Award class="absolute -right-4 -bottom-4 w-32 h-32 opacity-10" />
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] mb-6 opacity-70">Rendimiento Académico</h3>
                        <div v-if="studentProfile.merit">
                            <div class="flex items-end gap-2 mb-2">
                                <span class="text-6xl font-black leading-none">{{ studentProfile.merit.position }}º</span>
                                <span class="text-sm font-bold opacity-60 mb-2">de {{ studentProfile.merit.total }}</span>
                            </div>
                            <p class="text-xs font-medium opacity-80 italic">Promedio: {{ studentProfile.merit.average }}</p>
                        </div>
                        <div v-else class="py-4 text-xs italic opacity-60 uppercase">Cálculo de puesto al cierre de ciclo</div>
                    </div>

                    <!-- Card de Datos Biográficos (LO NUEVO) -->
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center">
                            <User class="w-4 h-4 mr-2 text-indigo-500" /> Identidad Cultural
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b pb-2 border-gray-50">
                                <span class="text-[10px] text-gray-400 uppercase font-bold">Estado Civil</span>
                                <span class="text-xs font-black text-gray-700 uppercase">{{ studentProfile.bio.civil_status }}</span>
                            </div>
                            <div class="flex justify-between items-center border-b pb-2 border-gray-50">
                                <span class="text-[10px] text-gray-400 uppercase font-bold">Lengua</span>
                                <span class="text-xs font-black text-gray-700 uppercase">{{ studentProfile.bio.language }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] text-gray-400 uppercase font-bold">Etnia</span>
                                <span class="text-xs font-black text-gray-700 uppercase">{{ studentProfile.bio.ethnicity }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA 2 y 3: CONTACTO Y BIENESTAR -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-[3rem] p-10 border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-center mb-8 border-b pb-4">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Información de contacto y beneficios</h3>
                            <span :class="studentProfile.academic.has_scholarship ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                class="px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                Beca: {{ studentProfile.academic.scholarship }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- BLOQUE DE CORREOS (Izquierda) -->
                            <div class="space-y-6">
                                <!-- Correo Institucional (Viene de la tabla Users) -->
                                <div class="flex items-start">
                                    <div class="bg-blue-50 p-3 rounded-2xl text-blue-600 mr-4 shadow-sm">
                                        <ShieldCheck class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Cuenta Institucional</label>
                                        <p class="text-sm font-bold text-blue-800 break-all">
                                            {{ studentProfile.contact.institutional_email }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Correo Personal (Viene de la tabla People - Sincronizado de la Ficha) -->
                                <div class="flex items-start">
                                    <div class="bg-indigo-50 p-3 rounded-2xl text-indigo-600 mr-4 shadow-sm">
                                        <Mail class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Correo de Contacto</label>
                                        <p class="text-sm font-bold text-gray-700 break-all">
                                            {{ studentProfile.contact.personal_email }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="bg-indigo-50 p-3 rounded-2xl text-indigo-600 mr-4 shadow-sm">
                                        <Phone class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Teléfono / Celular</label>
                                        <p class="text-sm font-bold text-gray-700">{{ studentProfile.contact.phone }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="bg-indigo-50 p-3 rounded-2xl text-indigo-600 mr-4 shadow-sm">
                                        <MapPin class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Residencia Actual</label>
                                        <p class="text-sm font-bold text-gray-800 uppercase leading-tight">{{ studentProfile.contact.address }}</p>
                                        <p class="text-[10px] text-indigo-500 font-bold mt-1 uppercase">{{ studentProfile.contact.locality }}</p>
                                    </div>
                                </div>
                                <!-- Mini Widget de Situación -->
                                <div class="flex gap-4 pt-4 border-t border-gray-50">
                                    <div class="flex-1 bg-gray-50 p-3 rounded-2xl text-center">
                                        <span class="block text-[8px] font-black text-gray-400 uppercase">Hijos</span>
                                        <span class="text-xs font-black text-gray-700">{{ studentProfile.bio.has_children }}</span>
                                    </div>
                                    <div class="flex-1 bg-gray-50 p-3 rounded-2xl text-center">
                                        <span class="block text-[8px] font-black text-gray-400 uppercase">Trabaja</span>
                                        <span class="text-xs font-black text-gray-700">{{ studentProfile.bio.is_working }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ESCENARIO PARA DOCENTE -->
            <div v-else-if="$page.props.teacherProfile" class="space-y-8">

                <!-- Header Profesional -->
                <div class="bg-white rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden transition-all hover:shadow-2xl">
                    <div class="bg-emerald-900 h-24 w-full relative"></div>
                    <div class="px-10 pb-8 -mt-12 relative z-10 flex flex-col md:flex-row items-center md:items-end gap-6">
                        <div class="w-32 h-32 rounded-3xl border-8 border-white bg-gray-200 shadow-2xl overflow-hidden">
                            <img v-if="$page.props.teacherProfile.photo" :src="$page.props.teacherProfile.photo" class="w-full h-full object-cover" />
                            <User v-else class="w-full h-full p-6 text-gray-400" />
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">{{ $page.props.teacherProfile.full_name }}</h1>
                            <p class="text-emerald-600 font-bold text-xs uppercase tracking-widest">
                                {{ $page.props.teacherProfile.degree }} | {{ $page.props.teacherProfile.condition }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Widgets de Gestión -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Horas Semanales -->
                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
                        <div class="bg-blue-100 text-blue-600 p-4 rounded-2xl"><Clock /></div>
                        <div>
                            <span class="text-2xl font-black block">{{ $page.props.teacherProfile.stats.weekly_hours }}h</span>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Carga Horaria Semanal</span>
                        </div>
                    </div>

                    <!-- Alumnos Totales -->
                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
                        <div class="bg-emerald-100 text-emerald-600 p-4 rounded-2xl"><Users /></div>
                        <div>
                            <span class="text-2xl font-black block">{{ $page.props.teacherProfile.stats.total_students }}</span>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Alumnos bajo supervisión</span>
                        </div>
                    </div>

                    <!-- Estado Documentario -->
                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
                        <div :class="$page.props.teacherProfile.stats.portfolio_ok ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600'" class="p-4 rounded-2xl">
                            <ShieldCheck v-if="$page.props.teacherProfile.stats.portfolio_ok" />
                            <AlertCircle v-else />
                        </div>
                        <div>
                            <span class="text-xs font-black block uppercase">{{ $page.props.teacherProfile.stats.portfolio_ok ? 'Al día' : 'Pendientes' }}</span>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Portafolio Académico</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

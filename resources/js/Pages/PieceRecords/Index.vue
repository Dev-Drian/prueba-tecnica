<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Banner from '@/Components/Banner.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    records: {
        type: Object,
        required: true
    }
});

const paginationLinks = computed(() => {
    return props.records.links.filter(link => link.url !== null);
});
</script>

<template>
    <AppLayout title="Registros de Piezas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registros de Piezas
            </h2>
        </template>

        <Banner />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                            <h3 class="text-lg font-semibold text-gray-900">Lista de Registros</h3>
                            <Link
                                :href="route('piece-records.create')"
                                class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-150 ease-in-out transform hover:-translate-y-0.5 text-center"
                            >
                                Nuevo Registro
                            </Link>
                        </div>

                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pieza
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Peso Real
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Diferencia
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="record in records.data" :key="record.id" class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ record.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div v-if="record.piece">
                                                {{ record.piece.codigo_pieza }} - {{ record.piece.nombre }}
                                                <div class="text-xs text-gray-500">
                                                    {{ record.piece.block?.nombre }} - {{ record.piece.block?.project?.nombre }}
                                                </div>
                                            </div>
                                            <span v-else class="text-red-500">Pieza no encontrada</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ record.peso_real }} kg
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ record.diferencia }} kg
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ new Date(record.fecha_hora).toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div v-if="record.user">
                                                {{ record.user.name }}
                                            </div>
                                            <span v-else class="text-red-500">Usuario no encontrado</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-4">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-700">
                                    Mostrando {{ records.from }} a {{ records.to }} de {{ records.total }} resultados
                                </div>
                                <div class="flex space-x-2">
                                    <Link
                                        v-for="link in paginationLinks"
                                        :key="link.label"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="px-3 py-1 rounded-md text-sm"
                                        :class="{
                                            'bg-blue-600 text-white': link.active,
                                            'bg-gray-100 text-gray-700 hover:bg-gray-200': !link.active
                                        }"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 
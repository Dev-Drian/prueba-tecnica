<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Banner from '@/Components/Banner.vue';
import Modal from '@/Components/Modal.vue';
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

const showModal = ref(false);
const selectedRecord = ref(null);

const openModal = (record) => {
    selectedRecord.value = record;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedRecord.value = null;
};
</script>

<template>
    <AppLayout title="Registros de Piezas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registros de Piezas
            </h2>
        </template>

        <Banner />

        <div class="py-4 sm:py-8 lg:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 space-y-3 sm:space-y-0">
                            <h3 class="text-lg font-semibold text-gray-900">Lista de Registros</h3>
                            <Link
                                :href="route('piece-records.create')"
                                class="btn-primary w-full sm:w-auto text-center text-sm sm:text-base"
                            >
                                Nuevo Registro
                            </Link>
                        </div>

                        <!-- Vista de tabla para desktop -->
                        <div class="hidden lg:block overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="table-header">ID</th>
                                        <th class="table-header">Pieza</th>
                                        <th class="table-header">Peso Real</th>
                                        <th class="table-header">Diferencia</th>
                                        <th class="table-header">Fecha</th>
                                        <th class="table-header">Usuario</th>
                                        <th class="table-header">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="record in records.data" :key="record.id" 
                                        class="hover:bg-gray-50 transition-all duration-300 ease-in-out">
                                        <td class="table-cell">{{ record.id }}</td>
                                        <td class="table-cell">
                                            <div v-if="record.piece" class="space-y-1">
                                                <div class="font-medium">{{ record.piece.codigo_pieza }} - {{ record.piece.nombre }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ record.piece.block?.nombre }} - {{ record.piece.block?.project?.nombre }}
                                                </div>
                                            </div>
                                            <span v-else class="text-red-500">Pieza no encontrada</span>
                                        </td>
                                        <td class="table-cell">{{ record.peso_real }} kg</td>
                                        <td class="table-cell">{{ record.diferencia }} kg</td>
                                        <td class="table-cell">
                                            {{ new Date(record.fecha_hora).toLocaleString() }}
                                        </td>
                                        <td class="table-cell">
                                            <div v-if="record.user">{{ record.user.name }}</div>
                                            <span v-else class="text-red-500">Usuario no encontrado</span>
                                        </td>
                                        <td class="table-cell">
                                            <button
                                                @click="openModal(record)"
                                                class="text-indigo-600 hover:text-indigo-900 font-medium transition-colors duration-300"
                                            >
                                                Ver detalles
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Vista de cards para móvil y tablet -->
                        <div class="lg:hidden space-y-4">
                            <div v-for="record in records.data" :key="record.id" 
                                 class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                ID: {{ record.id }}
                                            </span>
                                        </div>
                                        <div v-if="record.piece" class="space-y-1">
                                            <h4 class="font-semibold text-gray-900 text-sm sm:text-base">
                                                {{ record.piece.codigo_pieza }} - {{ record.piece.nombre }}
                                            </h4>
                                            <p class="text-xs text-gray-500">
                                                {{ record.piece.block?.nombre }} - {{ record.piece.block?.project?.nombre }}
                                            </p>
                                        </div>
                                        <span v-else class="text-red-500 text-sm">Pieza no encontrada</span>
                                    </div>
                                    <button
                                        @click="openModal(record)"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium text-sm transition-colors duration-300 ml-2 flex-shrink-0"
                                    >
                                        Ver detalles
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-500 block text-xs">Peso Real:</span>
                                        <span class="font-medium">{{ record.peso_real }} kg</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block text-xs">Diferencia:</span>
                                        <span class="font-medium">{{ record.diferencia }} kg</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block text-xs">Fecha:</span>
                                        <span class="font-medium text-xs sm:text-sm">
                                            {{ new Date(record.fecha_hora).toLocaleDateString() }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block text-xs">Usuario:</span>
                                        <span v-if="record.user" class="font-medium">{{ record.user.name }}</span>
                                        <span v-else class="text-red-500">No encontrado</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Paginación responsive -->
                        <div class="mt-6">
                            <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                                <div class="text-xs sm:text-sm text-gray-700 order-2 sm:order-1">
                                    Mostrando {{ records.from }} a {{ records.to }} de {{ records.total }} resultados
                                </div>
                                <div class="flex flex-wrap justify-center sm:justify-end gap-1 sm:gap-2 order-1 sm:order-2">
                                    <Link
                                        v-for="link in paginationLinks"
                                        :key="link.label"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="px-2 sm:px-3 py-1 rounded-md text-xs sm:text-sm transition-all duration-300 min-w-[32px] text-center"
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

        <!-- Modal de detalles responsive -->
        <Modal :show="showModal" @close="closeModal">
            <div class="modal-content max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white pb-4 border-b border-gray-200 mb-4">
                    <h2 class="modal-title text-lg sm:text-xl">
                        Detalles del Registro #{{ selectedRecord?.id }}
                    </h2>
                </div>
                
                <div class="space-y-4 sm:space-y-6">
                    <div v-if="selectedRecord?.piece" class="modal-section">
                        <h3 class="modal-section-title text-base sm:text-lg">Información de la Pieza</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div class="space-y-1">
                                <p class="modal-label text-xs sm:text-sm">Código:</p>
                                <p class="modal-value text-sm sm:text-base">{{ selectedRecord.piece.codigo_pieza }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="modal-label text-xs sm:text-sm">Nombre:</p>
                                <p class="modal-value text-sm sm:text-base">{{ selectedRecord.piece.nombre }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="modal-label text-xs sm:text-sm">Bloque:</p>
                                <p class="modal-value text-sm sm:text-base">{{ selectedRecord.piece.block?.nombre }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="modal-label text-xs sm:text-sm">Proyecto:</p>
                                <p class="modal-value text-sm sm:text-base">{{ selectedRecord.piece.block?.project?.nombre }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-section">
                        <h3 class="modal-section-title text-base sm:text-lg">Información del Registro</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div class="space-y-1">
                                <p class="modal-label text-xs sm:text-sm">Peso Teórico:</p>
                                <p class="modal-value text-sm sm:text-base">{{ selectedRecord?.piece?.peso_teorico }} kg</p>
                            </div>
                            <div class="space-y-1">
                                <p class="modal-label text-xs sm:text-sm">Peso Real:</p>
                                <p class="modal-value text-sm sm:text-base">{{ selectedRecord?.peso_real }} kg</p>
                            </div>
                            <div class="space-y-1">
                                <p class="modal-label text-xs sm:text-sm">Diferencia:</p>
                                <p class="modal-value text-sm sm:text-base">{{ selectedRecord?.diferencia }} kg</p>
                            </div>
                            <div class="space-y-1">
                                <p class="modal-label text-xs sm:text-sm">Fecha y Hora:</p>
                                <p class="modal-value text-sm sm:text-base">
                                    {{ selectedRecord?.fecha_hora ? new Date(selectedRecord.fecha_hora).toLocaleString() : '' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="selectedRecord?.observaciones" class="modal-section">
                        <h3 class="modal-section-title text-base sm:text-lg">Observaciones</h3>
                        <p class="text-sm sm:text-base text-gray-900 leading-relaxed">{{ selectedRecord.observaciones }}</p>
                    </div>

                    <div class="modal-section">
                        <h3 class="modal-section-title text-base sm:text-lg">Registrado por</h3>
                        <p class="text-sm sm:text-base text-gray-900">{{ selectedRecord?.user?.name }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
                    <button
                        @click="closeModal"
                        class="btn-secondary w-full sm:w-auto text-sm sm:text-base"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
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

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="card">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                            <h3 class="text-lg font-semibold text-gray-900">Lista de Registros</h3>
                            <Link
                                :href="route('piece-records.create')"
                                class="btn-primary w-full sm:w-auto text-center"
                            >
                                Nuevo Registro
                            </Link>
                        </div>

                        <div class="overflow-x-auto rounded-lg border border-gray-200">
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
                                                <div>{{ record.piece.codigo_pieza }} - {{ record.piece.nombre }}</div>
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
                                        class="px-3 py-1 rounded-md text-sm transition-all duration-300"
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

        <!-- Modal de detalles -->
        <Modal :show="showModal" @close="closeModal">
            <div class="modal-content">
                <h2 class="modal-title">
                    Detalles del Registro #{{ selectedRecord?.id }}
                </h2>
                
                <div class="space-y-6">
                    <div v-if="selectedRecord?.piece" class="modal-section">
                        <h3 class="modal-section-title">Información de la Pieza</h3>
                        <div class="modal-grid">
                            <div>
                                <p class="modal-label">Código:</p>
                                <p class="modal-value">{{ selectedRecord.piece.codigo_pieza }}</p>
                            </div>
                            <div>
                                <p class="modal-label">Nombre:</p>
                                <p class="modal-value">{{ selectedRecord.piece.nombre }}</p>
                            </div>
                            <div>
                                <p class="modal-label">Bloque:</p>
                                <p class="modal-value">{{ selectedRecord.piece.block?.nombre }}</p>
                            </div>
                            <div>
                                <p class="modal-label">Proyecto:</p>
                                <p class="modal-value">{{ selectedRecord.piece.block?.project?.nombre }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-section">
                        <h3 class="modal-section-title">Información del Registro</h3>
                        <div class="modal-grid">
                            <div>
                                <p class="modal-label">Peso Teórico:</p>
                                <p class="modal-value">{{ selectedRecord?.piece?.peso_teorico }} kg</p>
                            </div>
                            <div>
                                <p class="modal-label">Peso Real:</p>
                                <p class="modal-value">{{ selectedRecord?.peso_real }} kg</p>
                            </div>
                            <div>
                                <p class="modal-label">Diferencia:</p>
                                <p class="modal-value">{{ selectedRecord?.diferencia }} kg</p>
                            </div>
                            <div>
                                <p class="modal-label">Fecha y Hora:</p>
                                <p class="modal-value">
                                    {{ selectedRecord?.fecha_hora ? new Date(selectedRecord.fecha_hora).toLocaleString() : '' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="selectedRecord?.observaciones" class="modal-section">
                        <h3 class="modal-section-title">Observaciones</h3>
                        <p class="text-sm text-gray-900">{{ selectedRecord.observaciones }}</p>
                    </div>

                    <div class="modal-section">
                        <h3 class="modal-section-title">Registrado por</h3>
                        <p class="text-sm text-gray-900">{{ selectedRecord?.user?.name }}</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        @click="closeModal"
                        class="btn-secondary"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template> 
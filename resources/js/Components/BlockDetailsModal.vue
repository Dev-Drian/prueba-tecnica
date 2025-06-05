<script setup>
import DialogModal from '@/Components/DialogModal.vue';

defineProps({
    show: {
        type: Boolean,
        required: true
    },
    block: {
        type: Object,
        required: true
    }
});

defineEmits(['close']);
</script>

<template>
    <DialogModal :show="show" @close="$emit('close')">
        <template #title>
            <div class="flex items-center space-x-2">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Detalles del Bloque</span>
            </div>
        </template>

        <template #content>
            <div class="space-y-6">
                <!-- Información del Bloque -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Bloque</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Código del Bloque</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ block.codigo_bloque }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Nombre del Bloque</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ block.nombre }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información del Proyecto -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Proyecto Asociado</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Código del Proyecto</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ block.project?.codigo_proyecto }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Nombre del Proyecto</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ block.project?.nombre }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información de Fechas -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Fechas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Fecha de Creación</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ block.created_at }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Última Actualización</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ block.updated_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <button
                type="button"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                @click="$emit('close')"
            >
                Cerrar
            </button>
        </template>
    </DialogModal>
</template> 
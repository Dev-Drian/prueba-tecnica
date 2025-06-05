<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import { computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    block: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['close']);

const formattedCreatedAt = computed(() => {
    if (!props.block.created_at) return '';
    return new Date(props.block.created_at).toLocaleString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
});

const formattedUpdatedAt = computed(() => {
    if (!props.block.updated_at) return '';
    return new Date(props.block.updated_at).toLocaleString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
});

const handleClose = () => {
    emit('close');
};
</script>

<template>
    <DialogModal
        :show="show"
        :max-width="'2xl'"
        :closeable="true"
        @close="handleClose"
    >
        <template #title>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    Detalles del Bloque
                </h3>
            </div>
        </template>

        <template #content>
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500">Código del Bloque</h4>
                    <p class="mt-1 text-sm text-gray-900 font-semibold">{{ block.codigo_bloque }}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500">Nombre del Bloque</h4>
                    <p class="mt-1 text-sm text-gray-900 font-semibold">{{ block.nombre }}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500">Proyecto</h4>
                    <p class="mt-1 text-sm text-gray-900 font-semibold">{{ block.project?.nombre }}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500">Fecha de Creación</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ formattedCreatedAt }}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500">Última Actualización</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ formattedUpdatedAt }}</p>
                </div>
            </div>
        </template>

        <template #footer>
            <div class="flex justify-end">
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    @click="handleClose"
                >
                    Cerrar
                </button>
            </div>
        </template>
    </DialogModal>
</template> 
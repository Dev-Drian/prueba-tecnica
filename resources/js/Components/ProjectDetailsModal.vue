<script setup>
import DialogModal from '@/Components/DialogModal.vue';

defineProps({
    show: {
        type: Boolean,
        required: true
    },
    project: {
        type: Object,
        required: true
    }
});

defineEmits(['close']);
</script>

<template>
    <DialogModal :show="show" @close="$emit('close')">
        <template #title>
            Detalles del Proyecto
        </template>

        <template #content>
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Código del Proyecto</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ project.codigo_proyecto }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Nombre del Proyecto</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ project.nombre }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Fecha de Creación</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ project.created_at }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Última Actualización</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ project.updated_at }}</p>
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
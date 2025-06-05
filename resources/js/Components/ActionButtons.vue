<script setup>
defineProps({
    onView: {
        type: Function,
        required: true
    },
    onEdit: {
        type: Function,
        required: true
    },
    onDelete: {
        type: Function,
        required: true
    }
});
</script>

<template>
    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
        <button
            @click="onView"
            class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out"
        >
            Ver
        </button>
        <button
            @click="onEdit"
            class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out"
        >
            Editar
        </button>
        <button
            @click="onDelete"
            class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out"
        >
            Eliminar
        </button>
    </div>
</template> 
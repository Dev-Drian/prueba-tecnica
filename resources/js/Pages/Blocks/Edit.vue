<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import BlockForm from '@/Components/BlockForm.vue';

const props = defineProps({
    block: {
        type: Object,
        required: true
    },
    projects: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <AppLayout title="Editar Bloque">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Bloque
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <BlockForm :block="block" :projects="projects" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 
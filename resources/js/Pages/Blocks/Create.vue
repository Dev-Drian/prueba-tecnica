<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import BlockForm from '@/Components/BlockForm.vue';

const props = defineProps({
    projects: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <AppLayout title="Crear Bloque">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Bloque
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <BlockForm :projects="projects" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 
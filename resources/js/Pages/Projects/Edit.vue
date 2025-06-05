<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ProjectForm from '@/Components/ProjectForm.vue';

defineProps({
    project: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <AppLayout title="Editar Proyecto">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Proyecto
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <ProjectForm 
                        :project="project"
                        :submit-url="route('projects.update', project.id)"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template> 
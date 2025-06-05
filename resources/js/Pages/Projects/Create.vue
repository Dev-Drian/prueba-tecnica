<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ProjectForm from '@/Components/ProjectForm.vue';
</script>

<template>
    <AppLayout title="Crear Proyecto">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Nuevo Proyecto
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <ProjectForm :submit-url="route('projects.store')" />
                </div>
            </div>
        </div>
    </AppLayout>
</template> 
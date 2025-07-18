<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { useForm, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import ProjectDetailsModal from '@/Components/ProjectDetailsModal.vue';
import ActionButtons from '@/Components/ActionButtons.vue';
import Alert from '@/Components/Alert.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    projects: {
        type: Object,
        required: true
    }
});

const flash = computed(() => usePage().props.flash);

const confirmingProjectDeletion = ref(false);
const projectToDelete = ref(null);
const showingProjectDetails = ref(false);
const selectedProject = ref(null);

const form = useForm({
    _method: 'DELETE'
});

const confirmProjectDeletion = (project) => {
    projectToDelete.value = project;
    confirmingProjectDeletion.value = true;
};

const showProjectDetails = (project) => {
    selectedProject.value = project;
    showingProjectDetails.value = true;
};

const handleEdit = (project) => {
    window.location.href = route('projects.edit', project.id);
};

const deleteProject = () => {
    try {
        form.delete(route('projects.destroy', projectToDelete.value.id), {
            onSuccess: () => {
                confirmingProjectDeletion.value = false;
                projectToDelete.value = null;
            },
            onError: (errors) => {
                console.error('Error al eliminar el proyecto:', errors);
            }
        });
    } catch (error) {
        console.error('Error al eliminar el proyecto:', error);
    }
};
</script>

<template>
    <AppLayout title="Proyectos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Proyectos
            </h2>
        </template>

        <Alert
            v-if="flash.success"
            :show="!!flash.success"
            type="success"
            :message="flash.success"
            @close="flash.success = null"
        />

        <Alert
            v-if="flash.error"
            :show="!!flash.error"
            type="error"
            :message="flash.error"
            @close="flash.error = null"
        />

        <Banner />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                            <h3 class="text-lg font-semibold text-gray-900">Lista de Proyectos</h3>
                            <Link
                                :href="route('projects.create')"
                                class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-150 ease-in-out transform hover:-translate-y-0.5 text-center"
                            >
                                Nuevo Proyecto
                            </Link>
                        </div>

                        <div class="relative">
                            <div class="overflow-x-auto">
                                <div class="inline-block min-w-full align-middle">
                                    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                        Código
                                                    </th>
                                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Nombre
                                                    </th>
                                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                        <span class="sr-only">Acciones</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                <tr v-for="project in projects.data" :key="project.id" class="hover:bg-gray-50">
                                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                        {{ project.codigo_proyecto }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ project.nombre }}
                                                    </td>
                                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <ActionButtons
                                                            :on-view="() => showProjectDetails(project)"
                                                            :on-edit="() => handleEdit(project)"
                                                            :on-delete="() => confirmProjectDeletion(project)"
                                                        />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-4">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-700">
                                    Mostrando {{ projects.from }} a {{ projects.to }} de {{ projects.total }} resultados
                                </div>
                                <div class="flex space-x-2">
                                    <Link
                                        v-for="link in projects.links"
                                        :key="link.label"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="px-3 py-1 rounded-md text-sm"
                                        :class="{
                                            'bg-blue-600 text-white': link.active,
                                            'bg-gray-100 text-gray-700 hover:bg-gray-200': !link.active,
                                            'opacity-50 cursor-not-allowed': !link.url
                                        }"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Detalles del Proyecto -->
        <ProjectDetailsModal
            v-if="selectedProject"
            :show="showingProjectDetails"
            :project="selectedProject"
            @close="showingProjectDetails = false"
        />

        <!-- Modal de Confirmación de Eliminación -->
        <ConfirmationModal :show="confirmingProjectDeletion" @close="confirmingProjectDeletion = false">
            <template #title>
                Eliminar Proyecto
            </template>

            <template #content>
                ¿Estás seguro de que deseas eliminar este proyecto? Esta acción no se puede deshacer.
            </template>

            <template #footer>
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    @click="confirmingProjectDeletion = false"
                >
                    Cancelar
                </button>

                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteProject"
                >
                    Eliminar
                </button>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template> 
<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref } from 'vue';

const props = defineProps({
    projects: {
        type: Object,
        required: true
    }
});

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

                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Código
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombre
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="project in projects.data" :key="project.id" class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ project.codigo_proyecto }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ project.nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                                                <button
                                                    @click="showProjectDetails(project)"
                                                    class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out"
                                                >
                                                    Ver
                                                </button>
                                                <Link
                                                    :href="route('projects.edit', project.id)"
                                                    class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out"
                                                >
                                                    Editar
                                                </Link>
                                                <button
                                                    @click="confirmProjectDeletion(project)"
                                                    class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            <Pagination :links="projects.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Detalles del Proyecto -->
        <DialogModal :show="showingProjectDetails" @close="showingProjectDetails = false">
            <template #title>
                Detalles del Proyecto
            </template>

            <template #content>
                <div v-if="selectedProject" class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Código del Proyecto</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ selectedProject.codigo_proyecto }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Nombre del Proyecto</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ selectedProject.nombre }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Fecha de Creación</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ selectedProject.created_at }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Última Actualización</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ selectedProject.updated_at }}</p>
                    </div>
                </div>
            </template>

            <template #footer>
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    @click="showingProjectDetails = false"
                >
                    Cerrar
                </button>
            </template>
        </DialogModal>

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
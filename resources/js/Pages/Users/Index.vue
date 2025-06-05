<template>
    <AppLayout title="Usuarios">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuarios
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

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Lista de Usuarios</h3>
                            <Link
                                :href="route('users.create')"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Nuevo Usuario
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombre
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="user in users.data" :key="user.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ user.name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ user.username }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <Link
                                                    :href="route('users.edit', user.id)"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                >
                                                    Editar
                                                </Link>
                                                <button
                                                    @click="confirmDelete(user)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación mejorada -->
                        <div class="mt-6">
                            <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                                <div class="text-xs sm:text-sm text-gray-700 order-2 sm:order-1">
                                    Mostrando {{ users.from }} a {{ users.to }} de {{ users.total }} resultados
                                </div>
                                <div class="flex flex-wrap justify-center sm:justify-end gap-1 sm:gap-2 order-1 sm:order-2">
                                    <Link
                                        v-for="link in paginationLinks"
                                        :key="link.label"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="px-2 sm:px-3 py-1 rounded-md text-xs sm:text-sm transition-all duration-300 min-w-[32px] text-center"
                                        :class="{
                                            'bg-blue-600 text-white': link.active,
                                            'bg-gray-100 text-gray-700 hover:bg-gray-200': !link.active
                                        }"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Estás seguro de que deseas eliminar este usuario?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Esta acción no se puede deshacer.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <SecondaryButton @click="closeDeleteModal">
                        Cancelar
                    </SecondaryButton>
                    <DangerButton @click="deleteUser">
                        Eliminar
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Alert from '@/Components/Alert.vue';

const props = defineProps({
    users: Object
});

const flash = computed(() => usePage().props.flash);

const paginationLinks = computed(() => {
    return props.users.links.filter(link => link.url !== null);
});

const showDeleteModal = ref(false);
const userToDelete = ref(null);

const confirmDelete = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    userToDelete.value = null;
};

const deleteUser = () => {
    if (userToDelete.value) {
        router.delete(route('users.destroy', userToDelete.value.id), {
            onSuccess: () => {
                closeDeleteModal();
            },
        });
    }
};
</script> 
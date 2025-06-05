<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { useForm, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import BlockDetailsModal from '@/Components/BlockDetailsModal.vue';
import ActionButtons from '@/Components/ActionButtons.vue';
import Alert from '@/Components/Alert.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    blocks: {
        type: Object,
        required: true
    }
});

const confirmingBlockDeletion = ref(false);
const blockToDelete = ref(null);
const showingBlockDetails = ref(false);
const selectedBlock = ref(null);

const form = useForm({
    _method: 'DELETE'
});

const flash = computed(() => usePage().props.flash);

const confirmBlockDeletion = (block) => {
    blockToDelete.value = block;
    confirmingBlockDeletion.value = true;
};

const showBlockDetails = (block) => {
    selectedBlock.value = block;
    showingBlockDetails.value = true;
};

const handleEdit = (block) => {
    window.location.href = route('blocks.edit', block.id);
};

const deleteBlock = () => {
    if (!blockToDelete.value) return;
    
    form.delete(route('blocks.destroy', blockToDelete.value.id), {
        onSuccess: () => {
            confirmingBlockDeletion.value = false;
            blockToDelete.value = null;
        },
        onError: (errors) => {
            console.error('Error al eliminar el bloque:', errors);
        }
    });
};

const closeBlockDetails = () => {
    showingBlockDetails.value = false;
    selectedBlock.value = null;
};

const paginationLinks = computed(() => {
    return props.blocks.links.filter(link => link.url !== null);
});
</script>

<template>
    <AppLayout title="Bloques">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bloques
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
                            <h3 class="text-lg font-semibold text-gray-900">Lista de Bloques</h3>
                            <Link
                                :href="route('blocks.create')"
                                class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-150 ease-in-out transform hover:-translate-y-0.5 text-center"
                            >
                                Nuevo Bloque
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
                                            Proyecto
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="block in blocks.data" :key="block.id" class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ block.codigo_bloque }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ block.nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ block.project?.nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <ActionButtons
                                                :on-view="() => showBlockDetails(block)"
                                                :on-edit="() => handleEdit(block)"
                                                :on-delete="() => confirmBlockDeletion(block)"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-4">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-700">
                                    Mostrando {{ blocks.from }} a {{ blocks.to }} de {{ blocks.total }} resultados
                                </div>
                                <div class="flex space-x-2">
                                    <Link
                                        v-for="link in paginationLinks"
                                        :key="link.label"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="px-3 py-1 rounded-md text-sm"
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

        <!-- Modal de Detalles del Bloque -->
        <BlockDetailsModal
            v-if="selectedBlock"
            :show="showingBlockDetails"
            :block="selectedBlock"
            @close="closeBlockDetails"
        />

        <!-- Modal de Confirmación de Eliminación -->
        <ConfirmationModal :show="confirmingBlockDeletion" @close="confirmingBlockDeletion = false">
            <template #title>
                Eliminar Bloque
            </template>

            <template #content>
                ¿Estás seguro de que deseas eliminar este bloque? Esta acción no se puede deshacer.
            </template>

            <template #footer>
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    @click="confirmingBlockDeletion = false"
                >
                    Cancelar
                </button>

                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteBlock"
                >
                    Eliminar
                </button>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template> 
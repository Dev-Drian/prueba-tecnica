<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PieceForm from '@/Components/PieceForm.vue';

const props = defineProps({
    blocks: {
        type: Array,
        required: true
    },
    old: {
        type: Object,
        default: () => ({})
    }
});

const form = useForm({
    codigo_pieza: '',
    nombre: '',
    block_id: '',
    peso_teorico: '',
    estado: 'Pendiente'
});

const submit = (form) => {
    form.post(route('pieces.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<template>
    <AppLayout title="Crear Pieza">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Pieza
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <PieceForm
                            :blocks="blocks"
                            :old="old"
                            :submit-button-text="'Crear Pieza'"
                            @submit="submit"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 
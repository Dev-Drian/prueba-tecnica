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
    },
    errors: {
        type: Object,
        default: () => ({})
    }
});

const form = useForm({
    codigo_pieza: props.old.codigo_pieza || '',
    nombre: props.old.nombre || '',
    block_id: props.old.block_id || '',
    peso_teorico: props.old.peso_teorico || '',
    estado: props.old.estado || 'Pendiente'
});

const submit = (formData) => {
    formData.post(route('pieces.store'), {
        preserveScroll: true,
        onSuccess: () => {
            formData.reset();
        },
        onError: (errors) => {
            console.error('Errores de validación:', errors);
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
                            :errors="errors"
                            :submit-button-text="'Crear Pieza'"
                            @submit="submit"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 
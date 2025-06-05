<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PieceForm from '@/Components/PieceForm.vue';

const props = defineProps({
    piece: {
        type: Object,
        required: true
    },
    blocks: {
        type: Array,
        required: true
    }
});

const form = useForm({
    codigo_pieza: props.piece.codigo_pieza,
    nombre: props.piece.nombre,
    block_id: props.piece.block_id
});

const submit = (form) => {
    form.put(route('pieces.update', props.piece.id), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<template>
    <AppLayout title="Editar Pieza">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Pieza
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <PieceForm
                            :piece="piece"
                            :blocks="blocks"
                            :submit-button-text="'Actualizar Pieza'"
                            @submit="submit"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 
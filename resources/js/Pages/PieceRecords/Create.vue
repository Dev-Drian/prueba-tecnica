<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PieceRecordForm from '@/Components/PieceRecordForm.vue';

const props = defineProps({
    projects: {
        type: Array,
        required: true
    },
    blocks: {
        type: Array,
        required: true
    },
    pieces: {
        type: Array,
        required: true
    }
});

const form = useForm({
    project_id: '',
    block_id: '',
    pieza_id: '',
    peso_real: '',
    observaciones: ''
});

const submit = (form) => {
    form.post(route('piece-records.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<template>
    <AppLayout title="Registrar Pieza">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registrar Pieza
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <PieceRecordForm
                            :projects="projects"
                            :blocks="blocks"
                            :pieces="pieces"
                            @submit="submit"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 
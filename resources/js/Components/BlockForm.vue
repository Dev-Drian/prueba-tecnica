<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    block: {
        type: Object,
        default: () => ({})
    },
    projects: {
        type: Array,
        required: true
    }
});

const form = useForm({
    codigo_bloque: props.block?.codigo_bloque || '',
    nombre: props.block?.nombre || '',
    project_id: props.block?.project_id || ''
});

const submit = () => {
    if (props.block?.id) {
        form.put(route('blocks.update', props.block.id));
    } else {
        form.post(route('blocks.store'));
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <InputLabel for="codigo_bloque" value="Código del Bloque" />
            <TextInput
                id="codigo_bloque"
                type="text"
                class="mt-1 block w-full"
                v-model="form.codigo_bloque"
                required
                autofocus
            />
            <InputError :message="form.errors.codigo_bloque" class="mt-2" />
        </div>

        <div>
            <InputLabel for="nombre" value="Nombre del Bloque" />
            <TextInput
                id="nombre"
                type="text"
                class="mt-1 block w-full"
                v-model="form.nombre"
                required
            />
            <InputError :message="form.errors.nombre" class="mt-2" />
        </div>

        <div>
            <InputLabel for="project_id" value="Proyecto" />
            <select
                id="project_id"
                v-model="form.project_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required
            >
                <option value="">Seleccione un proyecto</option>
                <option v-for="project in projects" :key="project.id" :value="project.id">
                    {{ project.nombre }}
                </option>
            </select>
            <InputError :message="form.errors.project_id" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ props.block?.id ? 'Actualizar' : 'Crear' }} Bloque
            </PrimaryButton>
        </div>
    </form>
</template> 
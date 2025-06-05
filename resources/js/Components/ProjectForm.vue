<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    project: {
        type: Object,
        default: () => ({})
    },
    submitUrl: {
        type: String,
        required: true
    }
});

const form = useForm({
    codigo_proyecto: props.project?.codigo_proyecto || '',
    nombre: props.project?.nombre || '',
    _method: props.project?.id ? 'PUT' : 'POST'
});

const submit = () => {
    try {
        if (props.project?.id) {
            form.put(props.submitUrl, {
                onSuccess: () => {
                    form.reset();
                },
                onError: (errors) => {
                    console.error('Errores de validación:', errors);
                }
            });
        } else {
            form.post(props.submitUrl, {
                onSuccess: () => {
                    form.reset();
                },
                onError: (errors) => {
                    console.error('Errores de validación:', errors);
                }
            });
        }
    } catch (error) {
        console.error('Error al enviar el formulario:', error);
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <InputLabel for="codigo_proyecto" value="Código del Proyecto" />
            <TextInput
                id="codigo_proyecto"
                v-model="form.codigo_proyecto"
                type="text"
                class="mt-1 block w-full"
                required
                autofocus
            />
            <InputError :message="form.errors.codigo_proyecto" class="mt-2" />
        </div>

        <div>
            <InputLabel for="nombre" value="Nombre del Proyecto" />
            <TextInput
                id="nombre"
                v-model="form.nombre"
                type="text"
                class="mt-1 block w-full"
                required
            />
            <InputError :message="form.errors.nombre" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ project?.id ? 'Actualizar' : 'Crear' }} Proyecto
            </PrimaryButton>
        </div>
    </form>
</template> 
<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    piece: {
        type: Object,
        default: () => ({})
    },
    blocks: {
        type: Array,
        required: true
    },
    old: {
        type: Object,
        default: () => ({})
    },
    submitButtonText: {
        type: String,
        default: 'Guardar'
    }
});

const form = useForm({
    codigo_pieza: props.old.codigo_pieza || props.piece?.codigo_pieza || '',
    nombre: props.old.nombre || props.piece?.nombre || '',
    block_id: props.old.block_id || props.piece?.block_id || '',
    peso_teorico: props.old.peso_teorico || props.piece?.peso_teorico || '',
    estado: props.old.estado || props.piece?.estado || 'Pendiente',
    usuario_id: props.piece?.usuario_id || ''
});

const emit = defineEmits(['submit']);

const validateForm = () => {
    let isValid = true;
    const errors = {};

    if (!form.codigo_pieza.trim()) {
        errors.codigo_pieza = 'El código de la pieza es requerido';
        isValid = false;
    }

    if (!form.nombre.trim()) {
        errors.nombre = 'El nombre de la pieza es requerido';
        isValid = false;
    }

    if (!form.block_id) {
        errors.block_id = 'Debe seleccionar un bloque';
        isValid = false;
    }

    if (!form.peso_teorico || isNaN(form.peso_teorico) || form.peso_teorico <= 0) {
        errors.peso_teorico = 'El peso teórico debe ser un número mayor a 0';
        isValid = false;
    }

    if (!form.estado) {
        errors.estado = 'Debe seleccionar un estado';
        isValid = false;
    }

    form.errors = errors;
    return isValid;
};

const submit = () => {
    if (validateForm()) {
        console.log('Datos del formulario antes de enviar:', form.data());
        emit('submit', form);
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <InputLabel for="codigo_pieza" value="Código de la Pieza" />
            <TextInput
                id="codigo_pieza"
                type="text"
                class="mt-1 block w-full"
                v-model="form.codigo_pieza"
                required
                autofocus
            />
            <InputError :message="form.errors.codigo_pieza" class="mt-2" />
        </div>

        <div>
            <InputLabel for="nombre" value="Nombre de la Pieza" />
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
            <InputLabel for="peso_teorico" value="Peso Teórico (kg)" />
            <TextInput
                id="peso_teorico"
                type="number"
                step="0.01"
                min="0"
                class="mt-1 block w-full"
                v-model="form.peso_teorico"
                required
            />
            <InputError :message="form.errors.peso_teorico" class="mt-2" />
        </div>

        <div>
            <InputLabel for="estado" value="Estado" />
            <select
                id="estado"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.estado"
                required
            >
                <option value="Pendiente">Pendiente</option>
                <option value="Fabricada">Fabricada</option>
                <option value="En_Proceso">En Proceso</option>
            </select>
            <InputError :message="form.errors.estado" class="mt-2" />
        </div>

        <div>
            <InputLabel for="block_id" value="Bloque" />
            <select
                id="block_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.block_id"
                required
            >
                <option value="">Seleccione un bloque</option>
                <option
                    v-for="block in blocks"
                    :key="block.id"
                    :value="block.id"
                >
                    {{ block.nombre }} ({{ block.project.nombre }})
                </option>
            </select>
            <InputError :message="form.errors.block_id" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
            <PrimaryButton
                class="ml-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ submitButtonText }}
            </PrimaryButton>
        </div>
    </form>
</template> 
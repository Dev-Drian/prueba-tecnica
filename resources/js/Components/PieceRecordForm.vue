<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, computed, watch } from 'vue';

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

const selectedProject = ref(null);
const selectedBlock = ref(null);
const selectedPiece = ref(null);
const pesoTeorico = ref(null);
const diferencia = ref(null);

// Filtrar bloques por proyecto seleccionado
const filteredBlocks = computed(() => {
    if (!selectedProject.value) return [];
    console.log('Filtrando bloques para proyecto:', selectedProject.value);
    const blocks = props.blocks.filter(block => block.project_id === parseInt(selectedProject.value));
    console.log('Bloques filtrados:', blocks);
    return blocks;
});

// Filtrar piezas por bloque seleccionado
const filteredPieces = computed(() => {
    if (!selectedBlock.value) return [];
    console.log('Filtrando piezas para bloque:', selectedBlock.value);
    const pieces = props.pieces.filter(piece => piece.block_id === parseInt(selectedBlock.value));
    console.log('Piezas filtradas:', pieces);
    return pieces;
});

// Observar cambios en el proyecto seleccionado
watch(selectedProject, (newValue) => {
    console.log('Proyecto seleccionado:', newValue);
    form.project_id = newValue;
    form.block_id = '';
    form.pieza_id = '';
    selectedBlock.value = null;
    selectedPiece.value = null;
    pesoTeorico.value = null;
    diferencia.value = null;
});

// Observar cambios en el bloque seleccionado
watch(selectedBlock, (newValue) => {
    console.log('Bloque seleccionado:', newValue);
    form.block_id = newValue;
    form.pieza_id = '';
    selectedPiece.value = null;
    pesoTeorico.value = null;
    diferencia.value = null;
});

// Observar cambios en la pieza seleccionada
watch(selectedPiece, (newValue) => {
    console.log('Pieza seleccionada:', newValue);
    if (newValue) {
        form.pieza_id = newValue.id;
        pesoTeorico.value = newValue.peso_teorico;
        calcularDiferencia();
    } else {
        form.pieza_id = '';
        pesoTeorico.value = null;
        diferencia.value = null;
    }
});

// Observar cambios en el peso real
watch(() => form.peso_real, () => {
    calcularDiferencia();
});

const calcularDiferencia = () => {
    if (pesoTeorico.value && form.peso_real) {
        diferencia.value = (parseFloat(pesoTeorico.value) - parseFloat(form.peso_real)).toFixed(2);
    } else {
        diferencia.value = null;
    }
};

const validateForm = () => {
    let isValid = true;
    const errors = {};

    if (!form.project_id) {
        errors.project_id = 'Debe seleccionar un proyecto';
        isValid = false;
    }

    if (!form.block_id) {
        errors.block_id = 'Debe seleccionar un bloque';
        isValid = false;
    }

    if (!form.pieza_id) {
        errors.pieza_id = 'Debe seleccionar una pieza';
        isValid = false;
    }

    if (!form.peso_real || isNaN(form.peso_real) || form.peso_real <= 0) {
        errors.peso_real = 'El peso real debe ser un número mayor a 0';
        isValid = false;
    }

    form.errors = errors;
    return isValid;
};

const emit = defineEmits(['submit']);

const submit = () => {
    if (validateForm()) {
        emit('submit', form);
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <InputLabel for="project_id" value="Proyecto" />
            <select
                id="project_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="selectedProject"
                required
            >
                <option value="">Seleccione un proyecto</option>
                <option
                    v-for="project in projects"
                    :key="project.id"
                    :value="project.id"
                >
                    {{ project.nombre }}
                </option>
            </select>
            <InputError :message="form.errors.project_id" class="mt-2" />
        </div>

        <div>
            <InputLabel for="block_id" value="Bloque" />
            <select
                id="block_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="selectedBlock"
                required
                :disabled="!selectedProject"
            >
                <option value="">Seleccione un bloque</option>
                <option
                    v-for="block in filteredBlocks"
                    :key="block.id"
                    :value="block.id"
                >
                    {{ block.nombre }}
                </option>
            </select>
            <InputError :message="form.errors.block_id" class="mt-2" />
        </div>

        <div>
            <InputLabel for="pieza_id" value="Pieza" />
            <select
                id="pieza_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="selectedPiece"
                required
                :disabled="!selectedBlock"
            >
                <option value="">Seleccione una pieza</option>
                <option
                    v-for="piece in filteredPieces"
                    :key="piece.id"
                    :value="piece"
                >
                    {{ piece.codigo_pieza }} - {{ piece.nombre }}
                </option>
            </select>
            <InputError :message="form.errors.pieza_id" class="mt-2" />
        </div>

        <div>
            <InputLabel for="peso_teorico" value="Peso Teórico (kg)" />
            <TextInput
                id="peso_teorico"
                type="number"
                step="0.01"
                class="mt-1 block w-full bg-gray-100"
                :value="pesoTeorico"
                disabled
            />
        </div>

        <div>
            <InputLabel for="peso_real" value="Peso Real (kg)" />
            <TextInput
                id="peso_real"
                type="number"
                step="0.01"
                min="0"
                class="mt-1 block w-full"
                v-model="form.peso_real"
                required
            />
            <InputError :message="form.errors.peso_real" class="mt-2" />
        </div>

        <div>
            <InputLabel for="diferencia" value="Diferencia (kg)" />
            <TextInput
                id="diferencia"
                type="number"
                step="0.01"
                class="mt-1 block w-full bg-gray-100"
                :value="diferencia"
                disabled
            />
        </div>

        <div>
            <InputLabel for="observaciones" value="Observaciones" />
            <textarea
                id="observaciones"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.observaciones"
                rows="3"
            ></textarea>
        </div>

        <div class="flex items-center justify-end">
            <PrimaryButton
                class="ml-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Registrar
            </PrimaryButton>
        </div>
    </form>
</template> 
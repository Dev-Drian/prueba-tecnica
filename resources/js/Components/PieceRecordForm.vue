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
    },
    oldData: {
        type: Object,
        default: () => ({})
    }
});

const form = useForm({
    project_id: props.oldData.project_id || '',
    block_id: props.oldData.block_id || '',
    pieza_id: props.oldData.pieza_id || '',
    peso_real: props.oldData.peso_real || '',
    observaciones: props.oldData.observaciones || ''
});

const selectedProject = ref(props.oldData.project_id || null);
const selectedBlock = ref(props.oldData.block_id || null);
const selectedPiece = ref(props.oldData.pieza_id ? props.pieces.find(p => p.id === props.oldData.pieza_id) : null);
const pesoTeorico = ref(selectedPiece.value?.peso_teorico || null);
const diferencia = ref(null);

// Filtrar bloques por proyecto seleccionado
const filteredBlocks = computed(() => {
    if (!selectedProject.value) return [];
    return props.blocks.filter(block => block.project_id === parseInt(selectedProject.value));
});

// Filtrar piezas por bloque seleccionado
const filteredPieces = computed(() => {
    if (!selectedBlock.value) return [];
    return props.pieces.filter(piece => piece.block_id === parseInt(selectedBlock.value));
});

// Observar cambios en el proyecto seleccionado
watch(selectedProject, (newValue) => {
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
    form.block_id = newValue;
    form.pieza_id = '';
    selectedPiece.value = null;
    pesoTeorico.value = null;
    diferencia.value = null;
});

// Observar cambios en la pieza seleccionada
watch(selectedPiece, (newValue) => {
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
            <InputLabel for="project_id" value="Proyecto" class="form-label" />
            <select
                id="project_id"
                class="form-input"
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
            <InputError :message="form.errors.project_id" class="form-error" />
        </div>

        <div>
            <InputLabel for="block_id" value="Bloque" class="form-label" />
            <select
                id="block_id"
                class="form-input"
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
            <InputError :message="form.errors.block_id" class="form-error" />
        </div>

        <div>
            <InputLabel for="pieza_id" value="Pieza" class="form-label" />
            <select
                id="pieza_id"
                class="form-input"
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
            <InputError :message="form.errors.pieza_id" class="form-error" />
        </div>

        <div>
            <InputLabel for="peso_teorico" value="Peso Teórico (kg)" class="form-label" />
            <TextInput
                id="peso_teorico"
                type="number"
                step="0.01"
                class="form-input bg-gray-100"
                :value="pesoTeorico"
                disabled
            />
        </div>

        <div>
            <InputLabel for="peso_real" value="Peso Real (kg)" class="form-label" />
            <TextInput
                id="peso_real"
                type="number"
                step="0.01"
                min="0"
                class="form-input"
                v-model="form.peso_real"
                required
            />
            <InputError :message="form.errors.peso_real" class="form-error" />
        </div>

        <div>
            <InputLabel for="diferencia" value="Diferencia (kg)" class="form-label" />
            <TextInput
                id="diferencia"
                type="number"
                step="0.01"
                class="form-input bg-gray-100"
                :value="diferencia"
                disabled
            />
        </div>

        <div>
            <InputLabel for="observaciones" value="Observaciones" class="form-label" />
            <textarea
                id="observaciones"
                class="form-input"
                v-model="form.observaciones"
                rows="3"
            ></textarea>
            <InputError :message="form.errors.observaciones" class="form-error" />
        </div>

        <div class="flex items-center justify-end">
            <PrimaryButton
                class="btn-primary ml-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Registrar
            </PrimaryButton>
        </div>
    </form>
</template> 
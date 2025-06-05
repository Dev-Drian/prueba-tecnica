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
    <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">
        <!-- Grid responsive para los selects principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Proyecto -->
            <div>
                <InputLabel for="project_id" value="Proyecto" class="form-label text-sm sm:text-base" />
                <select
                    id="project_id"
                    class="form-input w-full text-sm sm:text-base"
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
                <InputError :message="form.errors.project_id" class="form-error mt-1 text-xs sm:text-sm" />
            </div>

            <!-- Bloque -->
            <div>
                <InputLabel for="block_id" value="Bloque" class="form-label text-sm sm:text-base" />
                <select
                    id="block_id"
                    class="form-input w-full text-sm sm:text-base"
                    v-model="selectedBlock"
                    required
                    :disabled="!selectedProject"
                    :class="{ 'bg-gray-100 cursor-not-allowed': !selectedProject }"
                >
                    <option value="">{{ selectedProject ? 'Seleccione un bloque' : 'Primero seleccione un proyecto' }}</option>
                    <option
                        v-for="block in filteredBlocks"
                        :key="block.id"
                        :value="block.id"
                    >
                        {{ block.nombre }}
                    </option>
                </select>
                <InputError :message="form.errors.block_id" class="form-error mt-1 text-xs sm:text-sm" />
            </div>

            <!-- Pieza -->
            <div class="md:col-span-2 lg:col-span-1">
                <InputLabel for="pieza_id" value="Pieza" class="form-label text-sm sm:text-base" />
                <select
                    id="pieza_id"
                    class="form-input w-full text-sm sm:text-base"
                    v-model="selectedPiece"
                    required
                    :disabled="!selectedBlock"
                    :class="{ 'bg-gray-100 cursor-not-allowed': !selectedBlock }"
                >
                    <option value="">{{ selectedBlock ? 'Seleccione una pieza' : 'Primero seleccione un bloque' }}</option>
                    <option
                        v-for="piece in filteredPieces"
                        :key="piece.id"
                        :value="piece"
                    >
                        {{ piece.codigo_pieza }} - {{ piece.nombre }}
                    </option>
                </select>
                <InputError :message="form.errors.pieza_id" class="form-error mt-1 text-xs sm:text-sm" />
            </div>
        </div>

        <!-- Sección de pesos - Grid responsive -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg border border-gray-200">
            <h3 class="text-sm sm:text-base font-medium text-gray-900 mb-3 sm:mb-4">Información de Peso</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <!-- Peso Teórico -->
                <div>
                    <InputLabel for="peso_teorico" value="Peso Teórico (kg)" class="form-label text-sm" />
                    <TextInput
                        id="peso_teorico"
                        type="number"
                        step="0.01"
                        class="form-input bg-gray-100 w-full text-sm sm:text-base"
                        :value="pesoTeorico"
                        disabled
                        placeholder="Automático"
                    />
                    <p class="text-xs text-gray-500 mt-1">Se completa automáticamente</p>
                </div>

                <!-- Peso Real -->
                <div>
                    <InputLabel for="peso_real" value="Peso Real (kg)" class="form-label text-sm" />
                    <TextInput
                        id="peso_real"
                        type="number"
                        step="0.01"
                        min="0"
                        class="form-input w-full text-sm sm:text-base"
                        v-model="form.peso_real"
                        required
                        placeholder="Ingrese el peso real"
                    />
                    <InputError :message="form.errors.peso_real" class="form-error mt-1 text-xs sm:text-sm" />
                </div>

                <!-- Diferencia -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <InputLabel for="diferencia" value="Diferencia (kg)" class="form-label text-sm" />
                    <TextInput
                        id="diferencia"
                        type="number"
                        step="0.01"
                        class="form-input bg-gray-100 w-full text-sm sm:text-base"
                        :value="diferencia"
                        disabled
                        placeholder="Se calcula automáticamente"
                        :class="{
                            'text-red-600': diferencia && parseFloat(diferencia) > 0,
                            'text-green-600': diferencia && parseFloat(diferencia) < 0,
                            'text-gray-600': diferencia && parseFloat(diferencia) === 0
                        }"
                    />
                    <p class="text-xs text-gray-500 mt-1">
                        {{ diferencia > 0 ? 'Peso teórico mayor' : diferencia < 0 ? 'Peso teórico menor' : 'Pesos iguales' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div>
            <InputLabel for="observaciones" value="Observaciones" class="form-label text-sm sm:text-base" />
            <textarea
                id="observaciones"
                class="form-input w-full text-sm sm:text-base resize-none"
                v-model="form.observaciones"
                rows="3"
                placeholder="Ingrese observaciones adicionales (opcional)"
            ></textarea>
            <InputError :message="form.errors.observaciones" class="form-error mt-1 text-xs sm:text-sm" />
        </div>

        <!-- Botones de acción -->
        <div class="flex flex-col sm:flex-row items-center justify-between pt-4 sm:pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
            <!-- Resumen del registro -->
            <div v-if="selectedPiece && form.peso_real" class="text-xs sm:text-sm text-gray-600 bg-blue-50 p-2 sm:p-3 rounded-lg w-full sm:w-auto">
                <p class="font-medium">Resumen del registro:</p>
                <p>{{ selectedPiece.codigo_pieza }} - {{ selectedPiece.nombre }}</p>
                <p>Peso: {{ form.peso_real }}kg ({{ diferencia > 0 ? '+' : '' }}{{ diferencia }}kg)</p>
            </div>

            <!-- Botón de envío -->
            <div class="flex space-x-3 w-full sm:w-auto">
                <button
                    type="button"
                    class="flex-1 sm:flex-none px-4 py-2 text-sm sm:text-base text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200"
                    @click="$emit('cancel')"
                >
                    Cancelar
                </button>
                <PrimaryButton
                    class="flex-1 sm:flex-none btn-primary px-6 py-2 text-sm sm:text-base"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing || !selectedPiece || !form.peso_real"
                >
                    <span v-if="form.processing">Registrando...</span>
                    <span v-else>Registrar Pieza</span>
                </PrimaryButton>
            </div>
        </div>
    </form>
</template>
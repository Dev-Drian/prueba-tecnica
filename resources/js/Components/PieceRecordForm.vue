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
    <form @submit.prevent="submit" class="space-y-8">
        <!-- Header del formulario -->
        <div class="text-center pb-6 border-b border-slate-200">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2m-2 0v2a2 2 0 002 2h2a2 2 0 002-2v-2m0 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v2z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">Información de la Pieza</h3>
        </div>

        <!-- Grid de selecciones principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Proyecto -->
            <div class="group">
                <InputLabel for="project_id" value="Proyecto" class="text-sm font-semibold text-slate-700 mb-2 block" />
                <div class="relative">
                    <select
                        id="project_id"
                        class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-700 text-sm
                               focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none
                               transition-all duration-200 hover:border-slate-400 hover:shadow-sm
                               appearance-none cursor-pointer"
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
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <InputError :message="form.errors.project_id" class="mt-2 text-sm" />
            </div>

            <!-- Bloque -->
            <div class="group">
                <InputLabel for="block_id" value="Bloque" class="text-sm font-semibold text-slate-700 mb-2 block" />
                <div class="relative">
                    <select
                        id="block_id"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-700 text-sm
                               focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none
                               transition-all duration-200 hover:border-slate-400 hover:shadow-sm
                               appearance-none cursor-pointer"
                        :class="{
                            'bg-white': selectedProject,
                            'bg-slate-50 cursor-not-allowed text-slate-400': !selectedProject
                        }"
                        v-model="selectedBlock"
                        required
                        :disabled="!selectedProject"
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
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <InputError :message="form.errors.block_id" class="mt-2 text-sm" />
            </div>

            <!-- Pieza -->
            <div class="group md:col-span-2 lg:col-span-1">
                <InputLabel for="pieza_id" value="Pieza" class="text-sm font-semibold text-slate-700 mb-2 block" />
                <div class="relative">
                    <select
                        id="pieza_id"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl text-slate-700 text-sm
                               focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none
                               transition-all duration-200 hover:border-slate-400 hover:shadow-sm
                               appearance-none cursor-pointer"
                        :class="{
                            'bg-white': selectedBlock,
                            'bg-slate-50 cursor-not-allowed text-slate-400': !selectedBlock
                        }"
                        v-model="selectedPiece"
                        required
                        :disabled="!selectedBlock"
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
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <InputError :message="form.errors.pieza_id" class="mt-2 text-sm" />
            </div>
        </div>

        <!-- Sección de pesos con diseño moderno -->
        <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 p-6 rounded-2xl border border-slate-200/50 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Información de Peso</h3>
                    <p class="text-slate-500 text-sm">Datos técnicos y mediciones</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Peso Teórico -->
                <div class="group">
                    <InputLabel for="peso_teorico" value="Peso Teórico (kg)" class="text-sm font-semibold text-slate-700 mb-2 block" />
                    <div class="relative">
                        <TextInput
                            id="peso_teorico"
                            type="number"
                            step="0.01"
                            class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-600 text-sm
                                   cursor-not-allowed transition-all duration-200"
                            :value="pesoTeorico"
                            disabled
                            placeholder="Se completa automáticamente"
                        />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Se completa automáticamente
                    </p>
                </div>

                <!-- Peso Real -->
                <div class="group">
                    <InputLabel for="peso_real" value="Peso Real (kg)" class="text-sm font-semibold text-slate-700 mb-2 block" />
                    <div class="relative">
                        <TextInput
                            id="peso_real"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-700 text-sm
                                   focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none
                                   transition-all duration-200 hover:border-slate-400 hover:shadow-sm"
                            v-model="form.peso_real"
                            required
                            placeholder="Ingrese el peso real"
                        />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-slate-400 text-sm">kg</span>
                        </div>
                    </div>
                    <InputError :message="form.errors.peso_real" class="mt-2 text-sm" />
                </div>

                <!-- Diferencia -->
                <div class="group sm:col-span-2 lg:col-span-1">
                    <InputLabel for="diferencia" value="Diferencia (kg)" class="text-sm font-semibold text-slate-700 mb-2 block" />
                    <div class="relative">
                        <TextInput
                            id="diferencia"
                            type="number"
                            step="0.01"
                            class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm
                                   cursor-not-allowed transition-all duration-200 font-medium"
                            :class="{
                                'text-red-600 bg-red-50 border-red-200': diferencia && parseFloat(diferencia) > 0,
                                'text-green-600 bg-green-50 border-green-200': diferencia && parseFloat(diferencia) < 0,
                                'text-slate-600 bg-slate-100 border-slate-200': diferencia && parseFloat(diferencia) === 0 || !diferencia
                            }"
                            :value="diferencia"
                            disabled
                            placeholder="Se calcula automáticamente"
                        />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-sm" :class="{
                                'text-red-500': diferencia && parseFloat(diferencia) > 0,
                                'text-green-500': diferencia && parseFloat(diferencia) < 0,
                                'text-slate-400': diferencia && parseFloat(diferencia) === 0 || !diferencia
                            }">kg</span>
                        </div>
                    </div>
                    <p class="text-xs mt-2 flex items-center gap-1" :class="{
                        'text-red-600': diferencia && parseFloat(diferencia) > 0,
                        'text-green-600': diferencia && parseFloat(diferencia) < 0,
                        'text-slate-500': diferencia && parseFloat(diferencia) === 0 || !diferencia
                    }">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" v-if="diferencia">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  :d="diferencia > 0 ? 'M7 11l5-5m0 0l5 5m-5-5v12' : diferencia < 0 ? 'M17 13l-5 5m0 0l-5-5m5 5V6' : 'M5 13l4 4L19 7'"></path>
                        </svg>
                        {{ diferencia > 0 ? 'Peso teórico mayor' : diferencia < 0 ? 'Peso teórico menor' : diferencia === 0 ? 'Pesos iguales' : 'Ingrese peso real' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div class="group">
            <InputLabel for="observaciones" value="Observaciones" class="text-sm font-semibold text-slate-700 mb-2 block" />
            <div class="relative">
                <textarea
                    id="observaciones"
                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-700 text-sm
                           focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none
                           transition-all duration-200 hover:border-slate-400 hover:shadow-sm resize-none"
                    v-model="form.observaciones"
                    rows="4"
                    placeholder="Ingrese observaciones adicionales (opcional)"
                ></textarea>
                <div class="absolute bottom-3 right-3">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>
            <InputError :message="form.errors.observaciones" class="mt-2 text-sm" />
        </div>

        <!-- Resumen y botones de acción -->
        <div class="border-t border-slate-200 pt-8">
            <!-- Resumen del registro -->
            <div v-if="selectedPiece && form.peso_real" 
                 class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-slate-800 mb-2">Resumen del registro</h4>
                        <div class="space-y-1 text-sm text-slate-600">
                            <p><span class="font-medium">Pieza:</span> {{ selectedPiece.codigo_pieza }} - {{ selectedPiece.nombre }}</p>
                            <p><span class="font-medium">Peso registrado:</span> {{ form.peso_real }}kg 
                               <span class="ml-2 px-2 py-1 rounded-full text-xs font-medium" :class="{
                                   'bg-red-100 text-red-700': diferencia > 0,
                                   'bg-green-100 text-green-700': diferencia < 0,
                                   'bg-slate-100 text-slate-700': diferencia === 0
                               }">
                                   {{ diferencia > 0 ? '+' : '' }}{{ diferencia }}kg
                               </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-4">
                <button
                    type="button"
                    class="w-full sm:w-auto px-6 py-3 text-sm font-medium text-slate-700 bg-white border-2 border-slate-300 
                           rounded-xl hover:bg-slate-50 hover:border-slate-400 focus:outline-none focus:ring-4 focus:ring-slate-500/10
                           transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
                    @click="$emit('cancel')"
                >
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar
                    </span>
                </button>
                
                <button
                    type="submit"
                    class="w-full sm:w-auto px-8 py-3 text-sm font-semibold text-white rounded-xl
                           transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]
                           focus:outline-none focus:ring-4 focus:ring-blue-500/20 shadow-lg hover:shadow-xl"
                    :class="{
                        'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700': !form.processing && selectedPiece && form.peso_real,
                        'bg-slate-400 cursor-not-allowed': form.processing || !selectedPiece || !form.peso_real
                    }"
                    :disabled="form.processing || !selectedPiece || !form.peso_real"
                >
                    <span class="flex items-center justify-center gap-2">
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ form.processing ? 'Registrando...' : 'Registrar Pieza' }}
                    </span>
                </button>
            </div>
        </div>
    </form>
</template>

<style scoped>
/* Animaciones personalizadas */
.group:hover .form-input {
    @apply shadow-sm;
}

/* Transiciones suaves para los estados */
.form-input {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Efectos de hover mejorados */
select:hover, input:hover, textarea:hover {
    transform: translateY(-1px);
}

/* Animaciones de los botones */
button {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

button:active {
    transform: scale(0.98);
}
</style>
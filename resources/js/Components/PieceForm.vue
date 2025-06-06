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
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 relative overflow-hidden">
        <!-- Fondo decorativo -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50/50 to-gray-100/50 pointer-events-none"></div>
        
        <!-- Contenido del formulario -->
        <div class="relative z-10">
            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-slate-700 to-slate-900 rounded-2xl shadow-lg mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Información de la Pieza</h3>
                <div class="h-1 w-16 bg-gradient-to-r from-slate-600 to-slate-800 rounded-full mx-auto"></div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Grid de campos principales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Código de Pieza -->
                    <div class="form-group">
                        <InputLabel for="codigo_pieza" value="Código de la Pieza" class="text-sm font-semibold text-gray-700 mb-3 block" />
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                            </div>
                            <TextInput
                                id="codigo_pieza"
                                type="text"
                                class="modern-input block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-300 text-gray-700 placeholder-gray-400 bg-gray-50/50"
                                v-model="form.codigo_pieza"
                                placeholder="Ingrese el código"
                                required
                                autofocus
                            />
                        </div>
                        <InputError :message="form.errors.codigo_pieza" class="mt-2 text-xs" />
                    </div>

                    <!-- Nombre de Pieza -->
                    <div class="form-group">
                        <InputLabel for="nombre" value="Nombre de la Pieza" class="text-sm font-semibold text-gray-700 mb-3 block" />
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <TextInput
                                id="nombre"
                                type="text"
                                class="modern-input block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-300 text-gray-700 placeholder-gray-400 bg-gray-50/50"
                                v-model="form.nombre"
                                placeholder="Ingrese el nombre"
                                required
                            />
                        </div>
                        <InputError :message="form.errors.nombre" class="mt-2 text-xs" />
                    </div>
                </div>

                <!-- Segunda fila -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Peso Teórico -->
                    <div class="form-group">
                        <InputLabel for="peso_teorico" value="Peso Teórico (kg)" class="text-sm font-semibold text-gray-700 mb-3 block" />
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                                </svg>
                            </div>
                            <TextInput
                                id="peso_teorico"
                                type="number"
                                step="0.01"
                                min="0"
                                class="modern-input block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-300 text-gray-700 placeholder-gray-400 bg-gray-50/50"
                                v-model="form.peso_teorico"
                                placeholder="0.00"
                                required
                            />
                        </div>
                        <InputError :message="form.errors.peso_teorico" class="mt-2 text-xs" />
                    </div>

                    <!-- Estado -->
                    <div class="form-group">
                        <InputLabel for="estado" value="Estado" class="text-sm font-semibold text-gray-700 mb-3 block" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <select
                                id="estado"
                                class="modern-select block w-full pl-12 pr-10 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-300 text-gray-700 bg-gray-50/50 appearance-none"
                                v-model="form.estado"
                                required
                            >
                                <option value="Pendiente" class="bg-white">📋 Pendiente</option>
                                <option value="Fabricada" class="bg-white">✅ Fabricada</option>
                                <option value="En_Proceso" class="bg-white">⚙️ En Proceso</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        <InputError :message="form.errors.estado" class="mt-2 text-xs" />
                    </div>
                </div>

                <!-- Bloque - Campo completo -->
                <div class="form-group">
                    <InputLabel for="block_id" value="Bloque Asignado" class="text-sm font-semibold text-gray-700 mb-3 block" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <select
                            id="block_id"
                            class="modern-select block w-full pl-12 pr-10 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-300 text-gray-700 bg-gray-50/50 appearance-none"
                            v-model="form.block_id"
                            required
                        >
                            <option value="" class="bg-white">Seleccione un bloque</option>
                            <option
                                v-for="block in blocks"
                                :key="block.id"
                                :value="block.id"
                                class="bg-white"
                            >
                                🏗️ {{ block.nombre }} ({{ block.project.nombre }})
                            </option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    <InputError :message="form.errors.block_id" class="mt-2 text-xs" />
                </div>

                <!-- Botón de envío -->
                <div class="pt-6 border-t border-gray-200/50">
                    <div class="flex items-center justify-end space-x-4">
                        <button 
                            type="button" 
                            class="px-6 py-3 text-gray-600 hover:text-gray-700 font-medium transition-colors duration-200"
                        >
                            Cancelar
                        </button>
                        <PrimaryButton
                            class="modern-button bg-gradient-to-r from-slate-700 to-slate-900 hover:from-slate-800 hover:to-slate-950 text-white font-semibold py-4 px-8 rounded-2xl shadow-lg transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl focus:ring-4 focus:ring-slate-300 focus:ring-opacity-50 relative overflow-hidden"
                            :class="{ 'opacity-75 cursor-not-allowed scale-100': form.processing }"
                            :disabled="form.processing"
                        >
                            <div class="button-shine"></div>
                            <span v-if="form.processing" class="flex items-center justify-center relative z-10">
                                <div class="loading-spinner mr-3"></div>
                                Guardando...
                            </span>
                            <span v-else class="relative z-10 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ submitButtonText }}
                            </span>
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shine {
    0% {
        transform: translateX(-100%);
    }
    50%, 100% {
        transform: translateX(100%);
    }
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Clases de animación */
.form-group {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
}

.form-group:nth-child(1) {
    animation-delay: 0.1s;
}

.form-group:nth-child(2) {
    animation-delay: 0.2s;
}

.form-group:nth-child(3) {
    animation-delay: 0.3s;
}

.form-group:nth-child(4) {
    animation-delay: 0.4s;
}

/* Inputs y selects modernos */
.input-container:focus-within .absolute svg {
    color: #64748b;
}

.modern-input, .modern-select {
    backdrop-filter: blur(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-input:focus, .modern-select:focus {
    background-color: rgba(248, 250, 252, 0.8);
    box-shadow: 0 0 0 1px #64748b, 0 4px 12px rgba(100, 116, 139, 0.15);
    transform: translateY(-1px);
}

.modern-input:hover, .modern-select:hover {
    border-color: #94a3b8;
    transform: translateY(-1px);
}

/* Botón moderno */
.modern-button {
    position: relative;
    overflow: hidden;
}

.button-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    animation: shine 3s infinite;
}

.modern-button:hover .button-shine {
    animation-duration: 0.5s;
}

/* Spinner de carga */
.loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s linear infinite;
}

/* Responsive */
@media (max-width: 768px) {
    .grid-cols-1.md\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .modern-input, .modern-select {
        padding: 1rem 1rem 1rem 3rem;
    }
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
<template>
    <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="show" class="fixed top-4 left-4 z-50">
            <div class="rounded-md p-4 shadow-lg" :class="alertClasses">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <component :is="icon" class="h-5 w-5" :class="iconClasses" aria-hidden="true" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium" :class="textClasses">
                            {{ message }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button
                                type="button"
                                class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                :class="closeButtonClasses"
                                @click="$emit('close')"
                            >
                                <span class="sr-only">Cerrar</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { computed } from 'vue';
import { CheckCircleIcon, ExclamationCircleIcon, InformationCircleIcon, XCircleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'success',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    message: {
        type: String,
        required: true
    }
});

const alertClasses = computed(() => ({
    'bg-green-50': props.type === 'success',
    'bg-red-50': props.type === 'error',
    'bg-yellow-50': props.type === 'warning',
    'bg-blue-50': props.type === 'info'
}));

const iconClasses = computed(() => ({
    'text-green-400': props.type === 'success',
    'text-red-400': props.type === 'error',
    'text-yellow-400': props.type === 'warning',
    'text-blue-400': props.type === 'info'
}));

const textClasses = computed(() => ({
    'text-green-800': props.type === 'success',
    'text-red-800': props.type === 'error',
    'text-yellow-800': props.type === 'warning',
    'text-blue-800': props.type === 'info'
}));

const closeButtonClasses = computed(() => ({
    'bg-green-50 text-green-500 hover:bg-green-100 focus:ring-green-600 focus:ring-offset-green-50': props.type === 'success',
    'bg-red-50 text-red-500 hover:bg-red-100 focus:ring-red-600 focus:ring-offset-red-50': props.type === 'error',
    'bg-yellow-50 text-yellow-500 hover:bg-yellow-100 focus:ring-yellow-600 focus:ring-offset-yellow-50': props.type === 'warning',
    'bg-blue-50 text-blue-500 hover:bg-blue-100 focus:ring-blue-600 focus:ring-offset-blue-50': props.type === 'info'
}));

const icon = computed(() => {
    switch (props.type) {
        case 'success':
            return CheckCircleIcon;
        case 'error':
            return XCircleIcon;
        case 'warning':
            return ExclamationCircleIcon;
        case 'info':
            return InformationCircleIcon;
        default:
            return CheckCircleIcon;
    }
});
</script> 
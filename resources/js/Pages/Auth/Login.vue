<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
    error: String,
});

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar Sesión - Portal Empresarial" />
    
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Fondo animado -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
            </div>
        </div>

        <div class="max-w-md w-full space-y-8 relative z-10">
            <!-- Header Section -->
            <div class="text-center fade-in-up">
                <div class="mx-auto h-20 w-20 bg-gradient-to-r from-slate-700 to-slate-900 rounded-2xl flex items-center justify-center shadow-2xl hover-lift">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-800 tracking-tight">COTECMAR</h2>
                <p class="mt-2 text-sm text-gray-600 font-medium">Portal Empresarial</p>
                <div class="mt-4 h-1 w-20 bg-gradient-to-r from-slate-600 to-slate-800 rounded-full mx-auto"></div>
            </div>

            <!-- Login Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 card-hover fade-in-up" style="animation-delay: 0.2s;">
                <!-- Error Message -->
                <div v-if="error" class="mb-6 p-4 bg-red-50/80 backdrop-blur border border-red-200/50 rounded-2xl slide-in-down">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium text-red-800">{{ error }}</p>
                    </div>
                </div>

                <!-- Status Message -->
                <div v-if="status" class="mb-6 p-4 bg-emerald-50/80 backdrop-blur border border-emerald-200/50 rounded-2xl slide-in-down">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium text-emerald-800">{{ status }}</p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Username Field -->
                    <div class="form-group">
                        <InputLabel for="username" value="Usuario" class="text-sm font-semibold text-gray-700 mb-3 block" />
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <TextInput
                                id="username"
                                v-model="form.username"
                                type="text"
                                class="modern-input block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-300 text-gray-700 placeholder-gray-400 bg-gray-50/50"
                                placeholder="Ingresa tu usuario"
                                required
                                autofocus
                                autocomplete="username"
                            />
                        </div>
                        <InputError class="mt-2 text-xs" :message="form.errors.username" />
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <InputLabel for="password" value="Contraseña" class="text-sm font-semibold text-gray-700 mb-3 block" />
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="modern-input block w-full pl-12 pr-4 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-300 text-gray-700 placeholder-gray-400 bg-gray-50/50"
                                placeholder="Ingresa tu contraseña"
                                required
                                autocomplete="current-password"
                            />
                        </div>
                        <InputError class="mt-2 text-xs" :message="form.errors.password" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-2">
                        <label class="flex items-center group cursor-pointer">
                            <Checkbox 
                                v-model:checked="form.remember" 
                                name="remember" 
                                class="h-4 w-4 text-slate-600 focus:ring-slate-500 border-gray-300 rounded transition-all duration-200"
                            />
                            <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-700 transition-colors duration-200">Recordarme</span>
                        </label>

                        <Link 
                            v-if="canResetPassword" 
                            :href="route('password.request')" 
                            class="text-sm text-slate-600 hover:text-slate-700 font-medium transition-all duration-200 hover:underline decoration-2 underline-offset-2"
                        >
                            ¿Olvidaste tu contraseña?
                        </Link>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <PrimaryButton 
                            class="modern-button w-full bg-gradient-to-r from-slate-700 to-slate-900 hover:from-slate-800 hover:to-slate-950 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl focus:ring-4 focus:ring-slate-300 focus:ring-opacity-50 relative overflow-hidden" 
                            :class="{ 'opacity-75 cursor-not-allowed scale-100': form.processing }" 
                            :disabled="form.processing"
                        >
                            <div class="button-shine"></div>
                            <span v-if="form.processing" class="flex items-center justify-center relative z-10">
                                <div class="loading-spinner mr-3"></div>
                                Iniciando sesión...
                            </span>
                            <span v-else class="relative z-10">Iniciar Sesión</span>
                        </PrimaryButton>
                    </div>
                </form>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-gray-200/50 text-center">
                    <p class="text-xs text-gray-500">
                        © 2025 Portal Empresarial. Todos los derechos reservados.
                    </p>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="text-center fade-in-up" style="animation-delay: 0.4s;">
                <div class="inline-flex items-center text-sm text-gray-600 bg-white/60 backdrop-blur px-4 py-2 rounded-full border border-white/30">
                    <svg class="h-4 w-4 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    Conexión segura SSL
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Animaciones principales */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    33% {
        transform: translateY(-20px) rotate(1deg);
    }
    66% {
        transform: translateY(-10px) rotate(-1deg);
    }
}

@keyframes floatReverse {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    33% {
        transform: translateY(15px) rotate(-1deg);
    }
    66% {
        transform: translateY(8px) rotate(1deg);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 0.4;
    }
    50% {
        opacity: 0.8;
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
.fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
    opacity: 0;
}

.slide-in-down {
    animation: slideInDown 0.5s ease-out forwards;
}

.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
}

/* Formas flotantes del fondo */
.floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.shape {
    position: absolute;
    background: linear-gradient(45deg, rgba(148, 163, 184, 0.1), rgba(100, 116, 139, 0.1));
    border-radius: 50%;
}

.shape-1 {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation: float 6s ease-in-out infinite;
}

.shape-2 {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 10%;
    animation: floatReverse 8s ease-in-out infinite;
}

.shape-3 {
    width: 60px;
    height: 60px;
    top: 80%;
    left: 20%;
    animation: float 7s ease-in-out infinite;
    animation-delay: -2s;
}

.shape-4 {
    width: 40px;
    height: 40px;
    top: 30%;
    right: 30%;
    animation: pulse 4s ease-in-out infinite;
}

/* Inputs modernos */
.input-container {
    position: relative;
}

.input-container:focus-within .absolute svg {
    color: #64748b;
}

.modern-input {
    backdrop-filter: blur(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-input:focus {
    background-color: rgba(248, 250, 252, 0.8);
    box-shadow: 0 0 0 1px #64748b, 0 4px 12px rgba(100, 116, 139, 0.15);
    transform: translateY(-1px);
}

.form-group {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
}

.form-group:nth-child(1) {
    animation-delay: 0.3s;
}

.form-group:nth-child(2) {
    animation-delay: 0.4s;
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

/* Efectos responsive */
@media (max-width: 640px) {
    .shape {
        display: none;
    }
    
    .modern-input {
        padding: 1rem 1rem 1rem 3rem;
    }
    
    .modern-button {
        padding: 1rem 1.5rem;
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
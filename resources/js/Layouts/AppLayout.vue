<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <!-- Enhanced Navigation -->
            <nav class="bg-white/90 backdrop-blur-lg border-b border-blue-100 shadow-lg sticky top-0 z-50 transition-all duration-300">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Enhanced Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')" class="flex items-center space-x-3 group">
                                    <div class="h-10 w-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                        Portal Pro
                                    </span>
                                </Link>
                            </div>

                            <!-- Enhanced Navigation Links -->
                            <div class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink 
                                    :href="route('dashboard')" 
                                    :active="route().current('dashboard')"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-blue-50 hover:text-blue-700 relative overflow-hidden group"
                                    :class="route().current('dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600'"
                                >
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                    </svg>
                                    Dashboard
                                </NavLink>
                                
                                <NavLink 
                                    :href="route('projects.index')" 
                                    :active="route().current('projects.*')"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-blue-50 hover:text-blue-700 relative overflow-hidden group"
                                    :class="route().current('projects.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600'"
                                >
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Proyectos
                                </NavLink>
                                
                                <NavLink 
                                    :href="route('blocks.index')" 
                                    :active="route().current('blocks.*')"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-blue-50 hover:text-blue-700 relative overflow-hidden group"
                                    :class="route().current('blocks.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600'"
                                >
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Bloques
                                </NavLink>
                                
                                <NavLink 
                                    :href="route('pieces.index')" 
                                    :active="route().current('pieces.*')"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-blue-50 hover:text-blue-700 relative overflow-hidden group"
                                    :class="route().current('pieces.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600'"
                                >
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Piezas
                                </NavLink>
                                
                                <NavLink 
                                    :href="route('piece-records.index')" 
                                    :active="route().current('piece-records.*')"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-300 hover:bg-blue-50 hover:text-blue-700 relative overflow-hidden group"
                                    :class="route().current('piece-records.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600'"
                                >
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Registros
                                </NavLink>
                            </div>
                        </div>

                        <!-- Enhanced Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="56">
                                    <template #trigger>
                                        <button class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-300 shadow-sm hover:shadow-md">
                                            <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div class="text-left hidden lg:block">
                                                <div class="text-sm font-semibold text-gray-900">{{ $page.props.auth.user.name }}</div>
                                                <div class="text-xs text-gray-500">Administrador</div>
                                            </div>
                                            <svg class="ml-2 h-4 w-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="py-2 bg-white rounded-xl shadow-xl border border-gray-100">
                                            <div class="px-4 py-3 border-b border-gray-100">
                                                <p class="text-sm font-medium text-gray-900">{{ $page.props.auth.user.name }}</p>
                                                <p class="text-xs text-gray-500">{{ $page.props.auth.user.email }}</p>
                                            </div>
                                            
                                            <div class="py-2">
                                                <DropdownLink class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200">
                                                    <svg class="h-4 w-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    Mi Perfil
                                                </DropdownLink>
                                                
                                                <DropdownLink class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200">
                                                    <svg class="h-4 w-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    Configuración
                                                </DropdownLink>
                                            </div>
                                            
                                            <div class="border-t border-gray-100 py-2">
                                                <form @submit.prevent="logout">
                                                    <DropdownLink as="button" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                                                        <svg class="h-4 w-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                        </svg>
                                                        Cerrar Sesión
                                                    </DropdownLink>
                                                </form>
                                            </div>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Enhanced Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-blue-50 focus:outline-none focus:bg-blue-50 focus:text-blue-500 transition-all duration-300">
                                <svg class="h-6 w-6 transition-transform duration-300" :class="{'rotate-90': showingNavigationDropdown}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Responsive Navigation Menu -->
                <div 
                    :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" 
                    class="sm:hidden bg-white/95 backdrop-blur-lg border-t border-blue-100 shadow-lg transition-all duration-300"
                >
                    <div class="pt-2 pb-3 space-y-1 px-4">
                        <ResponsiveNavLink 
                            :href="route('dashboard')" 
                            :active="route().current('dashboard')"
                            class="flex items-center px-4 py-3 rounded-lg transition-all duration-300"
                            :class="route().current('dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700'"
                        >
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            Dashboard
                        </ResponsiveNavLink>
                        
                        <ResponsiveNavLink 
                            :href="route('projects.index')" 
                            :active="route().current('projects.*')"
                            class="flex items-center px-4 py-3 rounded-lg transition-all duration-300"
                            :class="route().current('projects.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700'"
                        >
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Proyectos
                        </ResponsiveNavLink>
                        
                        <ResponsiveNavLink 
                            :href="route('blocks.index')" 
                            :active="route().current('blocks.*')"
                            class="flex items-center px-4 py-3 rounded-lg transition-all duration-300"
                            :class="route().current('blocks.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700'"
                        >
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Bloques
                        </ResponsiveNavLink>
                        
                        <ResponsiveNavLink 
                            :href="route('pieces.index')" 
                            :active="route().current('pieces.*')"
                            class="flex items-center px-4 py-3 rounded-lg transition-all duration-300"
                            :class="route().current('pieces.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700'"
                        >
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Piezas
                        </ResponsiveNavLink>
                        
                        <ResponsiveNavLink 
                            :href="route('piece-records.index')" 
                            :active="route().current('piece-records.*')"
                            class="flex items-center px-4 py-3 rounded-lg transition-all duration-300"
                            :class="route().current('piece-records.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700'"
                        >
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Registros
                        </ResponsiveNavLink>
                    </div>

                    <!-- Enhanced Responsive Settings Options -->
                    <div class="pt-4 pb-4 border-t border-blue-100 bg-gradient-to-r from-blue-50 to-indigo-50 mx-4 rounded-lg mb-4">
                        <div class="px-4 flex items-center space-x-3">
                            <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $page.props.auth.user.name }}</div>
                                <div class="text-sm text-gray-600">{{ $page.props.auth.user.email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1 px-4">
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button" class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-300">
                                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Cerrar Sesión
                                </ResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Enhanced Page Heading -->
            <header class="bg-white/80 backdrop-blur-lg shadow-lg border-b border-blue-100" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center space-x-4">
                        <div class="h-1 w-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"></div>
                        <slot name="header" />
                    </div>
                </div>
            </header>

            <!-- Enhanced Page Content -->
            <main class="transition-all duration-300 ease-in-out">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Animaciones suaves para las transiciones */
@keyframes slideInDown {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

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

/* Animación para el menú responsive */
.sm\\:hidden {
    animation: slideInDown 0.3s ease-out;
}

/* Efectos hover mejorados para los links */
.group:hover .group-hover\\:scale-105 {
    transform: scale(1.05);
}

.group:hover .group-hover\\:shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Transición suave para el contenido principal */
main {
    animation: fadeInUp 0.6s ease-out;
}

/* Efecto glassmorphism para la navegación */
nav {
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

/* Hover personalizado para botones de navegación */
.nav-link-hover {
    position: relative;
    overflow: hidden;
}

.nav-link-hover::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.nav-link-hover:hover::before {
    left: 100%;
}

/* Scroll suave */
html {
    scroll-behavior: smooth;
}

/* Sombra personalizada para cards */
.card-shadow {
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
}

.card-shadow:hover {
    box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
}
</style>
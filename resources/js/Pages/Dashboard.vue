<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, computed } from 'vue';
import Chart from 'chart.js/auto';
import { Bar, Pie } from 'vue-chartjs';

const props = defineProps({
    piezasPendientes: {
        type: Object,
        required: true
    },
    estadisticasProyectos: {
        type: Array,
        required: true
    }
});

// Configuración común para los gráficos
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            labels: {
                font: {
                    size: 14,
                    family: "'Inter', sans-serif"
                },
                padding: 20
            }
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            titleFont: {
                size: 14,
                family: "'Inter', sans-serif"
            },
            bodyFont: {
                size: 13,
                family: "'Inter', sans-serif"
            },
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    label += context.parsed.y || context.parsed;
                    return label;
                }
            }
        }
    }
};

// Configuración para el gráfico de barras
const barChartData = {
    labels: props.estadisticasProyectos.map(proyecto => proyecto.nombre),
    datasets: [
        {
            label: 'Piezas Pendientes',
            backgroundColor: 'rgba(248, 113, 113, 0.8)',
            borderColor: 'rgb(248, 113, 113)',
            borderWidth: 1,
            data: props.estadisticasProyectos.map(proyecto => proyecto.piezas_pendientes),
            hoverBackgroundColor: 'rgba(248, 113, 113, 1)',
            borderRadius: 6
        },
        {
            label: 'Piezas Fabricadas',
            backgroundColor: 'rgba(52, 211, 153, 0.8)',
            borderColor: 'rgb(52, 211, 153)',
            borderWidth: 1,
            data: props.estadisticasProyectos.map(proyecto => proyecto.piezas_fabricadas),
            hoverBackgroundColor: 'rgba(52, 211, 153, 1)',
            borderRadius: 6
        },
        {
            label: 'Piezas en Proceso',
            backgroundColor: 'rgba(96, 165, 250, 0.8)',
            borderColor: 'rgb(96, 165, 250)',
            borderWidth: 1,
            data: props.estadisticasProyectos.map(proyecto => proyecto.piezas_en_proceso),
            hoverBackgroundColor: 'rgba(96, 165, 250, 1)',
            borderRadius: 6
        }
    ]
};

const barChartOptions = {
    ...commonOptions,
    plugins: {
        ...commonOptions.plugins,
        title: {
            display: true,
            text: 'Estado de Piezas por Proyecto',
            font: {
                size: 18,
                family: "'Inter', sans-serif",
                weight: 'bold'
            },
            padding: 20
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: 'rgba(0, 0, 0, 0.1)'
            },
            ticks: {
                font: {
                    family: "'Inter', sans-serif"
                }
            }
        },
        x: {
            grid: {
                display: false
            },
            ticks: {
                font: {
                    family: "'Inter', sans-serif"
                }
            }
        }
    },
    animation: {
        duration: 2000,
        easing: 'easeInOutQuart'
    }
};

// Configuración para el gráfico circular
const pieChartData = {
    labels: ['Pendientes', 'Fabricadas', 'En Proceso'],
    datasets: [{
        data: [
            props.estadisticasProyectos.reduce((sum, proyecto) => sum + proyecto.piezas_pendientes, 0),
            props.estadisticasProyectos.reduce((sum, proyecto) => sum + proyecto.piezas_fabricadas, 0),
            props.estadisticasProyectos.reduce((sum, proyecto) => sum + proyecto.piezas_en_proceso, 0)
        ],
        backgroundColor: [
            'rgba(248, 113, 113, 0.8)',
            'rgba(52, 211, 153, 0.8)',
            'rgba(96, 165, 250, 0.8)'
        ],
        borderColor: [
            'rgb(248, 113, 113)',
            'rgb(52, 211, 153)',
            'rgb(96, 165, 250)'
        ],
        borderWidth: 2,
        hoverOffset: 15
    }]
};

const pieChartOptions = {
    ...commonOptions,
    plugins: {
        ...commonOptions.plugins,
        title: {
            display: true,
            text: 'Distribución Global de Piezas',
            font: {
                size: 18,
                family: "'Inter', sans-serif",
                weight: 'bold'
            },
            padding: 20
        }
    },
    animation: {
        animateRotate: true,
        animateScale: true,
        duration: 2000,
        easing: 'easeInOutQuart'
    }
};

// Estadísticas totales
const totalStats = computed(() => {
    return {
        total: props.estadisticasProyectos.reduce((sum, proyecto) => sum + proyecto.total_piezas, 0),
        pendientes: props.estadisticasProyectos.reduce((sum, proyecto) => sum + proyecto.piezas_pendientes, 0),
        fabricadas: props.estadisticasProyectos.reduce((sum, proyecto) => sum + proyecto.piezas_fabricadas, 0),
        enProceso: props.estadisticasProyectos.reduce((sum, proyecto) => sum + proyecto.piezas_en_proceso, 0)
    };
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Tarjetas de Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-red-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Piezas</p>
                                <p class="text-lg font-semibold text-gray-900">{{ totalStats.total }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Pendientes</p>
                                <p class="text-lg font-semibold text-gray-900">{{ totalStats.pendientes }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Fabricadas</p>
                                <p class="text-lg font-semibold text-gray-900">{{ totalStats.fabricadas }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">En Proceso</p>
                                <p class="text-lg font-semibold text-gray-900">{{ totalStats.enProceso }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reporte de Piezas Pendientes -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Piezas Pendientes por Proyecto
                        </h3>
                        <div class="space-y-4">
                            <div v-for="(piezas, proyecto) in piezasPendientes" :key="proyecto" 
                                 class="border rounded-lg p-4 hover:shadow-md transition-shadow duration-300">
                                <h4 class="font-medium text-gray-700 mb-2">{{ proyecto }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div v-for="pieza in piezas" :key="pieza.id" 
                                         class="bg-gray-50 p-3 rounded-lg hover:bg-gray-100 transition-colors duration-300">
                                        <p class="text-sm font-medium text-gray-900">{{ pieza.nombre }}</p>
                                        <p class="text-xs text-gray-500">{{ pieza.codigo_pieza }}</p>
                                        <p class="text-xs text-gray-500">Bloque: {{ pieza.block.nombre }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Gráfico de Barras -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <div class="h-96">
                                <Bar :data="barChartData" :options="barChartOptions" />
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico Circular -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <div class="h-96">
                                <Pie :data="pieChartData" :options="pieChartOptions" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

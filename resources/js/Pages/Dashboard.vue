<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
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

// Configuración para el gráfico de barras
const barChartData = {
    labels: props.estadisticasProyectos.map(proyecto => proyecto.nombre),
    datasets: [
        {
            label: 'Piezas Pendientes',
            backgroundColor: '#F87171',
            data: props.estadisticasProyectos.map(proyecto => proyecto.piezas_pendientes)
        },
        {
            label: 'Piezas Fabricadas',
            backgroundColor: '#34D399',
            data: props.estadisticasProyectos.map(proyecto => proyecto.piezas_fabricadas)
        },
        {
            label: 'Piezas en Proceso',
            backgroundColor: '#60A5FA',
            data: props.estadisticasProyectos.map(proyecto => proyecto.piezas_en_proceso)
        }
    ]
};

const barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Estado de Piezas por Proyecto'
        }
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
        backgroundColor: ['#F87171', '#34D399', '#60A5FA']
    }]
};

const pieChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Distribución Global de Piezas'
        }
    }
};
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
                <!-- Reporte de Piezas Pendientes -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Piezas Pendientes por Proyecto
                        </h3>
                        <div class="space-y-4">
                            <div v-for="(piezas, proyecto) in piezasPendientes" :key="proyecto" class="border rounded-lg p-4">
                                <h4 class="font-medium text-gray-700 mb-2">{{ proyecto }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div v-for="pieza in piezas" :key="pieza.id" 
                                         class="bg-gray-50 p-3 rounded-lg">
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

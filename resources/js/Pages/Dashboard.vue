<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { 
  ArrowTrendingUpIcon, 
  CubeIcon, 
  ClockIcon, 
  CheckCircleIcon, 
  UserGroupIcon,
  CalendarIcon,
  ArrowUpRightIcon,
  ArrowDownRightIcon,
  EllipsisHorizontalIcon,
  FunnelIcon,
  MagnifyingGlassIcon,
  ArrowDownTrayIcon,
  EyeIcon
} from '@heroicons/vue/24/outline';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);

// Props que vienen del controlador Laravel
const props = defineProps({
  totalProjects: Number,
  totalPieces: Number,
  pendingPieces: Array,
  completedPieces: Number,
  monthlyData: Array,
  piecesByStatus: Array,
  recentActivities: Array,
  performanceMetrics: Object
});

// Estados reactivos
const isLoading = ref(true);
const selectedTimeRange = ref('7d');
const animatedStats = ref({
  projects: 0,
  pieces: 0,
  pending: 0,
  completed: 0
});

// Computed properties
const priorityColor = computed(() => (priority) => {
  const colors = {
    'Alta': 'bg-red-100 text-red-800 border-red-200',
    'Media': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'Baja': 'bg-green-100 text-green-800 border-green-200'
  };
  return colors[priority] || 'bg-gray-100 text-gray-800';
});

const statusColor = computed(() => (status) => {
  const colors = {
    'Completado': 'bg-green-500',
    'En Proceso': 'bg-blue-500',
    'Retrasado': 'bg-red-500',
    'Pendiente': 'bg-yellow-500',
    'Revisión': 'bg-purple-500'
  };
  return colors[status] || 'bg-gray-500';
});

const progressColor = computed(() => (progress) => {
  if (progress >= 80) return 'bg-green-500';
  if (progress >= 60) return 'bg-blue-500';
  if (progress >= 40) return 'bg-yellow-500';
  return 'bg-red-500';
});

// Datos del gráfico
const chartData = computed(() => ({
  labels: props.monthlyData.map(item => {
    const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    return months[parseInt(item.month) - 1];
  }),
  datasets: [
    {
      label: 'Piezas',
      data: props.monthlyData.map(item => item.total),
      borderColor: '#3B82F6',
      backgroundColor: 'rgba(59, 130, 246, 0.1)',
      tension: 0.4,
      fill: true
    }
  ]
}));

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        display: true,
        color: 'rgba(0, 0, 0, 0.05)'
      }
    },
    x: {
      grid: {
        display: false
      }
    }
  }
};

// Funciones
const animateNumbers = () => {
  const duration = 2000;
  const steps = 60;
  const stepDuration = duration / steps;
  
  let currentStep = 0;
  const interval = setInterval(() => {
    const progress = currentStep / steps;
    const easeOut = 1 - Math.pow(1 - progress, 3);
    
    animatedStats.value = {
      projects: Math.floor(props.totalProjects * easeOut),
      pieces: Math.floor(props.totalPieces * easeOut),
      pending: Math.floor(props.pendingPieces.length * easeOut),
      completed: Math.floor(props.completedPieces * easeOut)
    };
    
    currentStep++;
    if (currentStep > steps) {
      clearInterval(interval);
      animatedStats.value = {
        projects: props.totalProjects,
        pieces: props.totalPieces,
        pending: props.pendingPieces.length,
        completed: props.completedPieces
      };
    }
  }, stepDuration);
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  });
};

const getProgressWidth = (progress) => {
  return `${Math.min(100, Math.max(0, progress))}%`;
};

// Lifecycle
onMounted(() => {
  setTimeout(() => {
    isLoading.value = false;
    animateNumbers();
  }, 300);
});
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 tracking-tight">
            Dashboard
          </h1>
          <p class="text-sm text-gray-600 mt-1">
            Resumen ejecutivo de proyectos y piezas
          </p>
        </div>
      </div>
    </template>

    <div class="space-y-6">
      <!-- Tarjetas de Estadísticas -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <!-- Total Proyectos -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-600 mb-1">Total Proyectos</p>
              <p class="text-3xl font-bold text-gray-900 mb-1">
                <span class="transition-all duration-500">{{ animatedStats.projects }}</span>
              </p>
              <div class="flex items-center text-sm">
                <ArrowUpRightIcon class="h-4 w-4 text-green-500 mr-1" />
                <span class="text-green-600 font-medium">+12%</span>
                <span class="text-gray-500 ml-1">vs mes anterior</span>
              </div>
            </div>
            <div class="h-12 w-12 bg-blue-100 rounded-xl flex items-center justify-center">
              <CubeIcon class="h-6 w-6 text-blue-600" />
            </div>
          </div>
        </div>

        <!-- Total Piezas -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-600 mb-1">Total Piezas</p>
              <p class="text-3xl font-bold text-gray-900 mb-1">
                <span class="transition-all duration-500">{{ animatedStats.pieces }}</span>
              </p>
              <div class="flex items-center text-sm">
                <ArrowUpRightIcon class="h-4 w-4 text-green-500 mr-1" />
                <span class="text-green-600 font-medium">+8%</span>
                <span class="text-gray-500 ml-1">vs mes anterior</span>
              </div>
            </div>
            <div class="h-12 w-12 bg-green-100 rounded-xl flex items-center justify-center">
              <CubeIcon class="h-6 w-6 text-green-600" />
            </div>
          </div>
        </div>

        <!-- Piezas Pendientes -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-600 mb-1">Piezas Pendientes</p>
              <p class="text-3xl font-bold text-gray-900 mb-1">
                <span class="transition-all duration-500">{{ animatedStats.pending }}</span>
              </p>
              <div class="flex items-center text-sm">
                <ArrowDownRightIcon class="h-4 w-4 text-red-500 mr-1" />
                <span class="text-red-600 font-medium">-5%</span>
                <span class="text-gray-500 ml-1">vs mes anterior</span>
              </div>
            </div>
            <div class="h-12 w-12 bg-yellow-100 rounded-xl flex items-center justify-center">
              <ClockIcon class="h-6 w-6 text-yellow-600" />
            </div>
          </div>
        </div>

        <!-- Piezas Completadas -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-600 mb-1">Piezas Completadas</p>
              <p class="text-3xl font-bold text-gray-900 mb-1">
                <span class="transition-all duration-500">{{ animatedStats.completed }}</span>
              </p>
              <div class="flex items-center text-sm">
                <ArrowUpRightIcon class="h-4 w-4 text-green-500 mr-1" />
                <span class="text-green-600 font-medium">+15%</span>
                <span class="text-gray-500 ml-1">vs mes anterior</span>
              </div>
            </div>
            <div class="h-12 w-12 bg-emerald-100 rounded-xl flex items-center justify-center">
              <CheckCircleIcon class="h-6 w-6 text-emerald-600" />
            </div>
          </div>
        </div>
      </div>

      <!-- Gráficas y Piezas Pendientes -->
      <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Gráfica de Proyectos Mensuales -->
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Proyectos por Mes</h3>
              <p class="text-sm text-gray-600">Evolución mensual de proyectos</p>
            </div>
            <div class="flex items-center space-x-2">
              <div class="flex items-center text-sm">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Iniciados</span>
              </div>
              <div class="flex items-center text-sm">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Completados</span>
              </div>
            </div>
          </div>
          <div class="h-64">
            <Line :data="chartData" :options="chartOptions" />
          </div>
        </div>

        <!-- Distribución por Estado -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Estado de Piezas</h3>
              <p class="text-sm text-gray-600">Distribución actual</p>
            </div>
          </div>
          <div class="space-y-4">
            <div v-for="status in piecesByStatus" :key="status.name" class="flex items-center justify-between">
              <div class="flex items-center">
                <div :class="statusColor(status.name)" class="w-3 h-3 rounded-full mr-3"></div>
                <span class="text-sm font-medium text-gray-700">{{ status.name }}</span>
              </div>
              <div class="flex items-center space-x-2">
                <span class="text-sm font-semibold text-gray-900">{{ status.count }}</span>
                <span class="text-xs text-gray-500">{{ status.percentage }}%</span>
              </div>
            </div>
          </div>
          <div class="mt-6">
            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
              <div class="h-full bg-gradient-to-r from-green-500 via-blue-500 via-yellow-500 to-red-500 rounded-full transition-all duration-1000"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Piezas Pendientes Mejoradas -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Piezas Pendientes</h3>
              <p class="text-sm text-gray-600">{{ pendingPieces.length }} piezas requieren atención</p>
            </div>
          </div>
        </div>
        <div class="overflow-hidden">
          <div class="max-h-96 overflow-y-auto">
            <div class="divide-y divide-gray-100">
              <div 
                v-for="(piece, index) in pendingPieces" 
                :key="piece.id"
                class="p-4 hover:bg-gray-50 transition-colors duration-200 transform hover:scale-[1.01]"
                :style="{ animationDelay: `${index * 100}ms` }"
              >
                <div class="flex items-center justify-between">
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-3 mb-2">
                      <h4 class="text-sm font-semibold text-gray-900 truncate">
                        {{ piece.name }}
                      </h4>
                      <span 
                        :class="priorityColor(piece.priority)"
                        class="px-2 py-1 text-xs font-medium rounded-full border"
                      >
                        {{ piece.priority }}
                      </span>
                      <span 
                        :class="statusColor(piece.status)"
                        class="w-2 h-2 rounded-full"
                      ></span>
                    </div>
                    <p class="text-xs text-gray-600 mb-2">{{ piece.project }}</p>
                    <div class="flex items-center space-x-4">
                      <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                          <span class="text-xs text-gray-500">Progreso</span>
                          <span class="text-xs font-medium text-gray-700">{{ piece.progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                          <div 
                            :class="progressColor(piece.progress)"
                            class="h-1.5 rounded-full transition-all duration-500 ease-out"
                            :style="{ width: getProgressWidth(piece.progress) }"
                          ></div>
                        </div>
                      </div>
                      <div class="text-right">
                        <p v-if="piece.daysLate > 0" class="text-xs text-red-600 font-medium">
                          {{ piece.daysLate }} días de retraso
                        </p>
                        <p v-else class="text-xs text-green-600 font-medium">
                          A tiempo
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="ml-4 flex items-center space-x-2">
                    <button class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                      <EyeIcon class="h-4 w-4" />
                    </button>
                    <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                      <EllipsisHorizontalIcon class="h-4 w-4" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100">
          <a href="/pieces" class="w-full text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200 block text-center">
            Ver todas las piezas pendientes
          </a>
        </div>
      </div>

      <!-- Actividades Recientes -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">Actividad Reciente</h3>
            <p class="text-sm text-gray-600">Últimas actualizaciones del sistema</p>
          </div>
        </div>
        <div class="space-y-4">
          <div 
            v-for="(activity, index) in recentActivities" 
            :key="index"
            class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200"
          >
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                <CubeIcon class="h-4 w-4 text-blue-600" />
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-gray-900">{{ activity.description }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ formatDate(activity.created_at) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.hover\:scale-\[1\.01\]:hover {
  transform: scale(1.01);
}

/* Animaciones de entrada */
.grid > div {
  animation: slideInUp 0.6s ease-out;
}

.grid > div:nth-child(1) { animation-delay: 0.1s; }
.grid > div:nth-child(2) { animation-delay: 0.2s; }
.grid > div:nth-child(3) { animation-delay: 0.3s; }
.grid > div:nth-child(4) { animation-delay: 0.4s; }

/* Transiciones suaves para las tarjetas */
.bg-white {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Scrollbar personalizado */
.overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
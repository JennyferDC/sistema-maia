<template>
  <div class="min-h-screen bg-pink-50 p-6">

    <!-- 🟢 HEADER PRINCIPAL — Título, descripción y botón "Agregar Bebé" -->
    <header class="relative overflow-hidden rounded-3xl shadow-lg mb-10">
      <!-- 🎨 Fondo con gradiente -->
      <div class="bg-gradient-to-r from-pink-300 via-fuchsia-300 to-purple-300 pb-20 pt-10 px-6 sm:px-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

          <!-- 👶 Título con ícono decorativo -->
          <div>
            <h1 class="text-4xl sm:text-5xl font-black tracking-tight text-white flex items-center gap-3">
              <span class="inline-block p-2 bg-white/20 rounded-full backdrop-blur-sm">
                <!-- SVG Ícono decorativo -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14.25c2.485 0 4.5-2.015 4.5-4.5S14.485 5.25 12 5.25 7.5 7.265 7.5 9.75s2.015 4.5 4.5 4.5z" />
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 14.25a3.75 3.75 0 013.75 3.75v.75M8.25 14.25a3.75 3.75 0 00-3.75 3.75v.75" />
                </svg>
              </span>
              Mis Bebés
            </h1>
            <p class="mt-2 text-white/90 max-w-md">
              Gestiona la información, etapas, recomendaciones y evolución de tus pequeños.
            </p>
          </div>

          <!-- ➕ Botón "Agregar Bebé" con efecto pulse -->
          <Link
            href="/ninos/create"
            class="relative inline-flex items-center gap-2 px-6 py-3 text-lg font-semibold rounded-xl
                   bg-white text-pink-600 hover:text-white hover:bg-pink-600 transition
                   shadow-md hover:shadow-lg focus-visible:outline-none focus-visible:ring-4
                   focus-visible:ring-white/40"
          >
            <PlusOutlined />
            Agregar Bebé
            <span class="absolute inset-0 rounded-xl shadow-inner animate-pulse bg-white/5"></span>
          </Link>
        </div>
      </div>

      <!-- 🌀 Decoración de curva inferior -->
      <svg class="absolute bottom-0 left-0 w-full h-14 lg:h-20 text-white" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path
          fill="currentColor"
          d="M0,224L60,229.3C120,235,240,245,360,229.3C480,213,600,171,720,165.3C840,160,960,192,1080,192C1200,192,1320,160,1380,144L1440,128V320H1380C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320H0Z"
        />
      </svg>
    </header>

    <!-- 🟢 ACCIONES RÁPIDAS — Atajos tipo tarjetas (Resumen, Alertas, Recursos, etc.) -->
    <section class="relative -mt-8 mb-10 px-2 sm:px-0">
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 max-w-5xl mx-auto">
        <Link
          v-for="act in quickActions"
          :key="act.label"
          :href="act.route"
          class="group flex flex-col items-center justify-center gap-2 px-4 py-6
                 bg-white rounded-2xl shadow-md hover:bg-pink-100 transition"
        >
          <!-- Ícono dinámico -->
          <component :is="act.icon" class="text-3xl text-pink-600 group-hover:text-pink-800" />
          <span class="text-center text-sm font-medium text-gray-700 group-hover:text-pink-800">
            {{ act.label }}
          </span>
        </Link>
      </div>
    </section>

    <!-- 🟢 SWITCH DE VISTA — Botones para elegir entre tarjetas o tabla -->
    <div class="mb-6 flex justify-end">
      <div class="inline-flex rounded-md shadow-sm">
        <button
          @click="viewMode = 'cards'"
          :class="{'bg-pink-600 text-white': viewMode === 'cards', 'bg-white text-gray-700': viewMode !== 'cards'}"
          class="px-4 py-2 text-sm font-medium rounded-l-lg border border-gray-300"
        >
          <AppstoreOutlined class="inline mr-1" />
          Tarjetas
        </button>
        <button
          @click="viewMode = 'table'"
          :class="{'bg-pink-600 text-white': viewMode === 'table', 'bg-white text-gray-700': viewMode !== 'table'}"
          class="px-4 py-2 text-sm font-medium rounded-r-lg border border-gray-300"
        >
          <TableOutlined class="inline mr-1" />
          Tabla
        </button>
      </div>
    </div>

    <!-- 🟢 VISTA TARJETAS — Información de cada bebé -->
    <div v-if="viewMode === 'cards'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="nino in ninos.data" :key="nino.id" class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
        <Link :href="`/ninos/${nino.id}`" class="block hover:bg-pink-50 transition-colors duration-150">
          <div class="p-6">


            <!-- Encabezado de la tarjeta -->
            <div class="flex justify-between items-start mb-4">
              <h2 class="text-xl font-bold text-gray-800">{{ nino.nombre }}</h2>
              <span class="px-3 py-1 bg-pink-100 text-pink-800 rounded-full text-sm font-medium">
                {{ nino.etapa_desarrollo.nombre }}
              </span>
            </div>

            <!-- Datos del bebé -->
            <div class="space-y-3 text-gray-600">
              <div class="flex items-center">
                <ClockCircleOutlined class="h-5 w-5 mr-2 text-pink-500" />
                <span>Nació en la semana {{ nino.fecha_nacimiento }}</span>
              </div>
              <div class="flex items-center">
                <ShoppingOutlined class="h-5 w-5 mr-2 text-pink-500" />
                <span>Peso: {{ nino.peso_nacimiento }} kg</span>
              </div>
              <div class="flex items-center">
                <CalendarOutlined class="h-5 w-5 mr-2 text-pink-500" />
                <span>Talla: {{ nino.talla_nacimiento }} cm</span>
              </div>
              <div class="flex items-center">
                <UserOutlined class="h-5 w-5 mr-2 text-pink-500" />
                <span>Madre: {{ nino.madre.name }}</span>
              </div>
            </div>
          </div>

          <!-- Acciones en tarjeta -->
          <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
            <Link :href="`/ninos/${nino.id}/edit`" class="text-green-600 hover:text-green-800 text-sm font-medium">Editar</Link>
            <Link :href="route('etapas.por-nino', nino.id)" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
              Ver etapas
            </Link>
            <button @click="destroy(nino.id)" class="text-red-600 hover:text-red-800 text-sm font-medium">Eliminar</button>
          </div>
        </Link>
      </div>
    </div>

    <!-- 🟢 VISTA TABLA — Tabla con información resumida de todos los niños -->
    <div v-else class="overflow-x-auto bg-white shadow rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
            <th class="px-6 py-3">Semana Nac.</th>
            <th class="px-6 py-3">Peso (kg)</th>
            <th class="px-6 py-3">Talla (cm)</th>
            <th class="px-6 py-3">Madre</th>
            <th class="px-6 py-3">Etapa</th>
            <th class="px-6 py-3 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="nino in ninos.data" :key="nino.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-900">{{ nino.nombre }}</td>
            <td class="px-6 py-4">{{ nino.fecha_nacimiento }}</td>
            <td class="px-6 py-4">{{ nino.peso_nacimiento }}</td>
            <td class="px-6 py-4">{{ nino.talla_nacimiento }}</td>
            <td class="px-6 py-4">{{ nino.madre.name }}</td>
            <td class="px-6 py-4">
              <span class="px-2 py-1 bg-pink-100 text-pink-800 rounded-full text-xs font-medium">
                {{ nino.etapa_desarrollo.nombre }}
              </span>
            </td>
            <td class="px-6 py-4 text-center space-x-3">
              <Link :href="`/ninos/${nino.id}`" class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                <EyeOutlined class="h-5 w-5 inline" />
              </Link>
              <Link :href="`/ninos/${nino.id}/edit`" class="text-green-600 hover:text-green-900" title="Editar">
                <EditOutlined class="h-5 w-5 inline" />
              </Link>
              <Link :href="route('etapas.por-nino', nino.id)" class="text-purple-600 hover:text-purple-900" title="Ver etapas">
                <AppstoreOutlined class="h-5 w-5 inline" />
              </Link>
              <button @click="destroy(nino.id)" class="text-red-600 hover:text-red-900" title="Eliminar">
                <DeleteOutlined class="h-5 w-5 inline" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- 🟢 Paginación -->
    <div v-if="ninos.meta && ninos.meta.last_page > 1" class="mt-6 flex justify-center">
      <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
        <Link
          v-for="(link, index) in ninos.meta.links"
          :key="index"
          :href="link.url || '#'"
          :class="{
            'bg-pink-600 text-white': link.active,
            'bg-white text-gray-500 hover:bg-gray-50': !link.active,
            'rounded-l-md': index === 0,
            'rounded-r-md': index === ninos.meta.links.length - 1,
            'pointer-events-none opacity-50': !link.url
          }"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium"
          v-html="link.label"
        />
      </nav>
    </div>

  </div>
</template>

<script setup>
// 📦 Importación de librerías y componentes externos
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

// 🎨 Iconos de Ant Design utilizados en el template
import {
  PlusOutlined,            // Ícono ➕ (Agregar bebé)
  AppstoreOutlined,        // Ícono 📦 (Vista tipo tarjetas)
  TableOutlined,           // Ícono 📊 (Vista tipo tabla)
  ClockCircleOutlined,     // Ícono 🕒 (Semana nacimiento)
  ShoppingOutlined,        // Ícono 🛒 (Peso)
  CalendarOutlined,        // Ícono 📅 (Talla)
  UserOutlined,            // Ícono 👤 (Madre)
  EyeOutlined,             // Ícono 👁️ (Ver)
  EditOutlined,            // Ícono ✏️ (Editar)
  DeleteOutlined,          // Ícono 🗑️ (Eliminar)
  
  // 🧠 Iconos adicionales para Acciones Rápidas
  WarningOutlined,         // 🔔 Alertas o advertencias
  StarOutlined,            // ⭐ Resumen o logros
  BulbOutlined,            // 💡 Recomendaciones
  LaptopOutlined,          // 💻 Recursos útiles
  MedicineBoxOutlined      // 🩺 Visitas médicas o salud
} from '@ant-design/icons-vue';

// 🧩 Props recibidas desde el servidor (lista de niños)
const props = defineProps({
  ninos: Object,
});

// 🔁 Estado reactivo: controla si se muestra en modo 'cards' o 'table'
const viewMode = ref('cards');

// 🧹 Función para eliminar un niño con confirmación
function destroy(id) {
  if (confirm('¿Estás seguro de eliminar este niño?')) {
    router.delete(route('ninos.destroy', id));
  }
}

// ⚡ Lista de acciones rápidas (ejemplo: se puede ampliar según funcionalidad)
const quickActions = [
  {
    label: 'Resumen de mi hija',
    route: '/resumen',
    icon: StarOutlined
  },
  {
    label: 'Recomendaciones',
    route: '/recomendaciones',
    icon: BulbOutlined
  },
  {
    label: 'Visitas médicas',
    route: '/visitas',
    icon: MedicineBoxOutlined
  },
  {
    label: 'Recursos útiles',
    route: '/recursos',
    icon: LaptopOutlined
  }
];
</script>
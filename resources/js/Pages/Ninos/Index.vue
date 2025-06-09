<template>
  <div class="min-h-screen bg-pink-50 p-6">
    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-pink-800">Mis Bebés</h1>
      <Link
        href="/ninos/create"
        class="flex items-center gap-2 px-4 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors shadow-md"
      >
        <PlusOutlined />
        Agregar Bebé
      </Link>
    </div>

    <!-- Selector de Vista (Tarjetas/Tabla) -->
    <div class="mb-6 flex justify-end">
      <div class="inline-flex rounded-md shadow-sm">
        <button
          @click="viewMode = 'cards'"
          :class="{'bg-pink-600 text-white': viewMode === 'cards', 'bg-white text-gray-700': viewMode !== 'cards'}"
          class="px-4 py-2 text-sm font-medium rounded-l-lg border border-gray-300 focus:z-10 focus:ring-2 focus:ring-pink-500 transition-colors"
        >
          <AppstoreOutlined class="inline mr-1" />
          Tarjetas
        </button>
        <button
          @click="viewMode = 'table'"
          :class="{'bg-pink-600 text-white': viewMode === 'table', 'bg-white text-gray-700': viewMode !== 'table'}"
          class="px-4 py-2 text-sm font-medium rounded-r-lg border border-gray-300 focus:z-10 focus:ring-2 focus:ring-pink-500 transition-colors"
        >
          <TableOutlined class="inline mr-1" />
          Tabla
        </button>
      </div>
    </div>

    <!-- Vista de Tarjetas -->
    <div v-if="viewMode === 'cards'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="nino in ninos.data" :key="nino.id" class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
        <div class="p-6">
          <!-- Encabezado tarjeta -->
          <div class="flex justify-between items-start mb-4">
            <h2 class="text-xl font-bold text-gray-800">{{ nino.nombre }}</h2>
            <span class="px-3 py-1 bg-pink-100 text-pink-800 rounded-full text-sm font-medium">
              {{ nino.etapa_desarrollo.nombre }}
            </span>
          </div>
          
          <!-- Información básica -->
          <div class="space-y-3">
            <div class="flex items-center text-gray-600">
              <ClockCircleOutlined class="h-5 w-5 mr-2 text-pink-500" />
              <span>Nació en la semana {{ nino.fecha_nacimiento }}</span>
            </div>
            
            <div class="flex items-center text-gray-600">
              <ShoppingOutlined class="h-5 w-5 mr-2 text-pink-500" />
              <span>Peso: {{ nino.peso_nacimiento }} kg</span>
            </div>
            
            <div class="flex items-center text-gray-600">
              <CalendarOutlined class="h-5 w-5 mr-2 text-pink-500" />
              <span>Talla: {{ nino.talla_nacimiento }} cm</span>
            </div>
            
            <div class="flex items-center text-gray-600">
              <UserOutlined class="h-5 w-5 mr-2 text-pink-500" />
              <span>Madre: {{ nino.madre.name }}</span>
            </div>
          </div>
        </div>
        
        <!-- Acciones -->
        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
          <Link
            :href="`/ninos/${nino.id}`"
            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
          >
            Ver detalles
          </Link>
          <Link
            :href="`/ninos/${nino.id}/edit`"
            class="text-green-600 hover:text-green-800 text-sm font-medium"
          >
            Editar
          </Link>
          <button
            @click="destroy(nino.id)"
            class="text-red-600 hover:text-red-800 text-sm font-medium"
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>

    <!-- Vista de Tabla -->
    <div v-else class="overflow-x-auto bg-white shadow rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semana Nac.</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peso (kg)</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Talla (cm)</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Madre</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Etapa</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="nino in ninos.data" :key="nino.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ nino.nombre }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ nino.fecha_nacimiento }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ nino.peso_nacimiento }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ nino.talla_nacimiento }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ nino.madre.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 bg-pink-100 text-pink-800 rounded-full text-xs font-medium">
                {{ nino.etapa_desarrollo.nombre }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center space-x-3">
              <Link
                :href="`/ninos/${nino.id}`"
                class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                title="Ver detalles"
              >
                <EyeOutlined class="h-5 w-5 inline" />
              </Link>
              <Link
                :href="`/ninos/${nino.id}/edit`"
                class="text-green-600 hover:text-green-900 text-sm font-medium"
                title="Editar"
              >
                <EditOutlined class="h-5 w-5 inline" />
              </Link>
              <button
                @click="destroy(nino.id)"
                class="text-red-600 hover:text-red-900 text-sm font-medium"
                title="Eliminar"
              >
                <DeleteOutlined class="h-5 w-5 inline" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div v-if="ninos.meta && ninos.meta.last_page > 1" class="mt-6 flex justify-center">
      <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
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
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
  PlusOutlined,
  AppstoreOutlined,
  TableOutlined,
  ClockCircleOutlined,
  ShoppingOutlined,
  CalendarOutlined,
  UserOutlined,
  EyeOutlined,
  EditOutlined,
  DeleteOutlined
} from '@ant-design/icons-vue';

const props = defineProps({
  ninos: Object,
});

const viewMode = ref('cards'); // 'cards' o 'table'

function destroy(id) {
  if (confirm('¿Estás seguro de eliminar este niño?')) {
    router.delete(route('ninos.destroy', id));
  }
}
</script>
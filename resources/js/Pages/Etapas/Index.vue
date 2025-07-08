<template>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Hitos por Etapa de Desarrollo</h1>

    <div v-for="etapa in etapas" :key="etapa.id" class="mb-8">
      <div class="mb-2">
        <span class="text-lg font-semibold">{{ getNombre(etapa.nombre_etapa) }}</span>
        <span class="text-gray-500 ml-2">{{ getRango(etapa.nombre_etapa) }}</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <a-card
          v-for="hito in etapa.hitos"
          :key="hito.id"
          class="border border-gray-200 shadow-sm"
          :bodyStyle="{ padding: '8px' }"
        >
          <a-checkbox
            v-model:checked="hito.completado"
            class="custom-checkbox"
          >
            {{ hito.nombre_hito }}
          </a-checkbox>
        </a-card>

        <span v-if="!etapa.hitos.length" class="text-gray-400 col-span-full">
          Sin hitos registrados
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Card as ACard, Checkbox as ACheckbox } from 'ant-design-vue';

const props = defineProps({
  etapas: {
    type: Array,
    required: true
  }
});

// Inicializa el estado visual (opcional)
props.etapas.forEach(etapa => {
  etapa.hitos.forEach(hito => {
    if (hito.completado === undefined) hito.completado = false;
  });
});

const getNombre = (nombre_etapa) => {
  const idx = nombre_etapa.indexOf('(');
  return idx !== -1 ? nombre_etapa.slice(0, idx).trim() : nombre_etapa;
};

const getRango = (nombre_etapa) => {
  const match = nombre_etapa.match(/\(([^)]+)\)/);
  return match ? match[1] : '';
};
</script>

<style scoped>
:deep(.ant-card) {
  border-radius: 10px;
  font-size: 15px;
}

.custom-checkbox :deep(.ant-checkbox-inner) {
  width: 22px;
  height: 22px;
  border-radius: 50%;              /* 🔵 círculo */
  border: 2px solid #d1d5db;       /* gris (tailwind gray-300) */
  background-color: white;
}

.custom-checkbox :deep(.ant-checkbox-checked .ant-checkbox-inner) {
  background-color: #22c55e;       /* 🟢 verde */
  border-color: #d1d5db;           /* mantiene el borde gris */
}

.custom-checkbox :deep(.ant-checkbox) {
  transform: scale(1.3);           /* tamaño grande */
}
</style>
<template>
  <div class="p-4 space-y-8">
    <div v-for="(etapa, index) in etapasDesarrollo" :key="index" class="border rounded-xl p-4 shadow">
      <h2 class="text-lg font-semibold mb-2">{{ etapa.nombre_etapa }}</h2>

      <List :data-source="etapa.hitos" bordered>
        <template #renderItem="{ item: hito }">
          <ListItem>
            <Checkbox
              :checked="!!(seleccionados[etapa.nombre_etapa] && seleccionados[etapa.nombre_etapa][hito])"
              @change="() => toggleHito(etapa.nombre_etapa, hito)"
            >
              {{ hitosLabels[hito] }}
            </Checkbox>
          </ListItem>
        </template>
      </List>

      <!-- Mostrar hitos ya seleccionados con fecha -->
      <div v-if="seleccionados[etapa.nombre_etapa] && Object.keys(seleccionados[etapa.nombre_etapa]).length">
        <h3 class="mt-4 text-sm font-semibold">Hitos seleccionados:</h3>
        <ul class="text-sm pl-4 list-disc">
          <li
            v-for="(info, hito) in seleccionados[etapa.nombre_etapa]"
            :key="hito"
          >
            {{ hitosLabels[hito] }} — {{ info.fecha }} (original: {{ info.etapa_origen }})
          </li>
        </ul>
      </div>

      <!-- Botón para guardar -->
      <button
        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        @click="guardarHitos(etapa.nombre_etapa)"
      >
        Guardar hitos alcanzados
      </button>
    </div>
  </div>
</template>

<script setup>
import { List, ListItem, ListItemMeta, Checkbox } from 'ant-design-vue';
import 'ant-design-vue/es/list/style';
import 'ant-design-vue/es/checkbox/style';
import { ref } from 'vue';

const etapasDesarrollo = ref([
  {
    nombre_etapa: 'Recién Nacido (0-1 mes)',
    hitos: ['sostiene_cabeza', 'reflejo_succión']
  },
  {
    nombre_etapa: 'Lactante (1-12 meses)',
    hitos: ['se_sienta_sin_apoyo', 'balbucea']
  },
  {
    nombre_etapa: 'Infante (1-3 años)',
    hitos: ['camina_solo', 'dice_palabras']
  }
]);

const hitosLabels = {
  sostiene_cabeza: 'Sostiene la cabeza',
  reflejo_succión: 'Reflejo de succión',
  se_sienta_sin_apoyo: 'Se sienta sin apoyo',
  balbucea: 'Balbucea',
  camina_solo: 'Camina solo',
  dice_palabras: 'Dice palabras'
};

// Relación hito → etapa original
const hitoEtapaMap = {
  sostiene_cabeza: 'Recién Nacido (0-1 mes)',
  reflejo_succión: 'Recién Nacido (0-1 mes)',
  se_sienta_sin_apoyo: 'Lactante (1-12 meses)',
  balbucea: 'Lactante (1-12 meses)',
  camina_solo: 'Infante (1-3 años)',
  dice_palabras: 'Infante (1-3 años)'
};

// Estado: hitos seleccionados con fecha
const seleccionados = ref({});

// Marcar/desmarcar hito
const toggleHito = (etapa, hito) => {
  if (!seleccionados.value[etapa]) {
    seleccionados.value[etapa] = {};
  }

  const yaMarcado = seleccionados.value[etapa][hito];

  if (yaMarcado) {
    delete seleccionados.value[etapa][hito];
  } else {
    seleccionados.value[etapa][hito] = {
      fecha: new Date().toISOString().split('T')[0],
      etapa_origen: hitoEtapaMap[hito]
    };
  }

  console.log('Estado actual:', seleccionados.value);
};

// Guardar hitos marcados (simulación de POST al backend)
const guardarHitos = (etapaActual) => {
  const hitos = seleccionados.value[etapaActual] || {};
  const dataAGuardar = Object.entries(hitos).map(([hito, info]) => ({
    hito,
    fecha_logro: info.fecha,
    etapa_origen: info.etapa_origen,
    etapa_registro: etapaActual
  }));

  console.log('Enviando al backend:', dataAGuardar);

  // Aquí podrías enviar a tu controlador hito_logrado, por ejemplo:
  // await axios.post('/api/hito_logrado', dataAGuardar);

  // Limpiar selección (opcional)
  // delete seleccionados.value[etapaActual];
};
</script>
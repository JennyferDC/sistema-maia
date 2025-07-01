<template>
    <div class="p-4 space-y-8">
      <div
        v-for="(categoria, index) in categoriasDiagnostico"
        :key="index"
        class="border rounded-xl p-4 shadow"
      >
        <h2 class="text-lg font-semibold mb-2">{{ categoria.nombre_categoria }}</h2>
  
        <List :data-source="categoria.diagnosticos" bordered>
          <template #renderItem="{ item: diagnostico }">
            <ListItem>
              <Checkbox
                :checked="!!(seleccionados[categoria.nombre_categoria] && seleccionados[categoria.nombre_categoria][diagnostico])"
                @change="() => toggleDiagnostico(categoria.nombre_categoria, diagnostico)"
              >
                {{ diagnosticosLabels[diagnostico] }}
              </Checkbox>
            </ListItem>
          </template>
        </List>
  
        <!-- Mostrar diagnósticos seleccionados con fecha -->
        <div
          v-if="seleccionados[categoria.nombre_categoria] && Object.keys(seleccionados[categoria.nombre_categoria]).length"
        >
          <h3 class="mt-4 text-sm font-semibold">Diagnósticos seleccionados:</h3>
          <ul class="text-sm pl-4 list-disc">
            <li
              v-for="(info, diagnostico) in seleccionados[categoria.nombre_categoria]"
              :key="diagnostico"
            >
              {{ diagnosticosLabels[diagnostico] }} — {{ info.fecha }}
            </li>
          </ul>
        </div>
  
        <button
          class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
          @click="guardarDiagnosticos(categoria.nombre_categoria)"
        >
          Guardar diagnósticos seleccionados
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { List, ListItem, Checkbox } from 'ant-design-vue';
  import 'ant-design-vue/es/list/style';
  import 'ant-design-vue/es/checkbox/style';
  import { ref } from 'vue';
  import axios from 'axios';
  
  // Este ID debería ser pasado como prop si estás usando un componente por niño
  const ninoId = 1; // ⚠️ Aquí podrías usar: defineProps({ ninoId: Number })
  
  const categoriasDiagnostico = ref([
    {
      nombre_categoria: 'Trastornos del desarrollo',
      diagnosticos: ['tea', 'tdah']
    },
    {
      nombre_categoria: 'Condiciones neurológicas',
      diagnosticos: ['paralisis_cerebral', 'epilepsia']
    },
    {
      nombre_categoria: 'Otros',
      diagnosticos: ['retraso_global', 'discapacidad_intelectual']
    }
  ]);
  
  const diagnosticosLabels = {
    tea: 'Trastorno del espectro autista (TEA)',
    tdah: 'Trastorno por déficit de atención e hiperactividad (TDAH)',
    paralisis_cerebral: 'Parálisis cerebral',
    epilepsia: 'Epilepsia',
    retraso_global: 'Retraso global del desarrollo',
    discapacidad_intelectual: 'Discapacidad intelectual'
  };
  
  const seleccionados = ref({});
  
  const toggleDiagnostico = (categoria, diagnostico) => {
    if (!seleccionados.value[categoria]) {
      seleccionados.value[categoria] = {};
    }
  
    const yaMarcado = seleccionados.value[categoria][diagnostico];
  
    if (yaMarcado) {
      delete seleccionados.value[categoria][diagnostico];
    } else {
      seleccionados.value[categoria][diagnostico] = {
        fecha: new Date().toISOString().split('T')[0]
      };
    }
  };
  
  const guardarDiagnosticos = async (categoriaActual) => {
    const diagnosticos = seleccionados.value[categoriaActual] || {};
    const dataAGuardar = Object.entries(diagnosticos).map(([codigo, info]) => ({
      diagnostico: codigo,
      fecha_diagnostico: info.fecha,
      categoria: categoriaActual,
      nino_id: ninoId
    }));
  
    try {
      await axios.post('/diagnosticos/guardar-multiples', {
        diagnosticos: dataAGuardar
      });
  
      alert('Diagnósticos guardados correctamente');
      delete seleccionados.value[categoriaActual];
    } catch (error) {
      console.error(error);
      alert('Error al guardar diagnósticos');
    }
  };
  </script>
  
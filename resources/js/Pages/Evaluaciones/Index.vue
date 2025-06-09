<template>
    <div class="sm:min-w-[560px] p-6 bg-white rounded shadow">
      <h2 class="text-xl font-bold mb-4">
        Evaluación de desarrollo para: {{ props.nino.nombre }}
      </h2>
  
      <a-form layout="vertical" @submit.prevent="submitForm">
        <a-form-item label="Selecciona los hitos que tu hijo ya ha alcanzado">
          <a-checkbox-group v-model:value="form.hitos">
            <a-row>
              <a-col v-for="hito in props.hitos" :key="hito" :span="8">
                <a-checkbox :value="hito">{{ formatearHito(hito) }}</a-checkbox>
              </a-col>
            </a-row>
          </a-checkbox-group>
        </a-form-item>
  
        <a-button type="primary" html-type="submit">Guardar evaluación</a-button>
      </a-form>
    </div>
  </template>

<script setup>
import { ref } from 'vue';
import { message } from 'ant-design-vue';
import 'ant-design-vue/es/checkbox/style';
import 'ant-design-vue/es/button/style';

const props = defineProps({
  nino: Object,
  hitos: Array
});

console.log("dksok ", props.hitos);

const form = ref({
  hitos: []
});

function submitForm() {
  console.log('Hitos seleccionados:', form.value.hitos);
  message.success('Datos preparados correctamente');
}
</script>
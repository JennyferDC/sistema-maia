<template>
  <div class="sm:min-w-[560px] p-6 bg-white rounded shadow">
    <Form layout="vertical" @finish="enviarFormulario" :model="nino">
      <FormItem label="Nombre del Niño *" name="nombre">
        <Input v-model:value="nino.nombre" placeholder="Ingrese el nombre completo" />
      </FormItem>

      <FormItem label="Sexo *" name="sexo">
        <Select
          v-model:value="nino.sexo"
          :options="sexoOptions"
          placeholder="Seleccione..."
        />
      </FormItem>

      <FormItem label="Fecha de Nacimiento *" name="fecha_nacimiento">
        <DatePicker
          v-model:value="nino.fecha_nacimiento"
          format="DD/MM/YYYY"
          value-format="YYYY-MM-DD"
          style="width: 100%"
          :disabled-date="disabledDate"
          placeholder="Seleccione fecha"
        />
      </FormItem>

      <FormItem label="¿Nació prematuro? *" name="es_prematuro">
        <RadioGroup v-model:value="nino.es_prematuro" @change="handlePrematuroChange">
          <Radio :value="true">Sí</Radio>
          <Radio :value="false">No</Radio>
        </RadioGroup>
      </FormItem>

      <FormItem
        v-if="nino.es_prematuro"
        label="Semanas de gestación *"
        name="semanas_prematuro"
      >
        <Select
          v-model:value="nino.semanas_prematuro"
          :options="semanasOptions"
          placeholder="Seleccione semanas"
        />
      </FormItem>

      <FormItem label="Peso al Nacer (kg) *" name="peso_nacimiento">
        <InputNumber
          v-model:value="nino.peso_nacimiento"
          :min="0.5"
          :max="5.5"
          :step="0.001"
          placeholder="Ej. 3.250"
          class="w-full"
        />
      </FormItem>

      <FormItem label="Talla al Nacer (cm) *" name="talla_nacimiento">
        <InputNumber
          v-model:value="nino.talla_nacimiento"
          :min="30"
          :max="60"
          :step="0.1"
          placeholder="Ej. 50.5"
          class="w-full"
        />
      </FormItem>

      <!-- Etapa de desarrollo (asignado automáticamente) -->
      <FormItem label="Etapa de Desarrollo (asignado automáticamente)" name="etapa_desarrollo_id">
        <Select
          v-model:value="nino.etapa_desarrollo_id"
          :options="etapaOptions"
          disabled
        />
      </FormItem>


      <div class="mt-4 flex justify-end gap-3">
        <Button @click="resetFormulario">Cancelar</Button>
        <Button type="primary" :loading="cargando" @click="enviarFormulario">
          Guardar Niño
        </Button>
      </div>
    </Form>
  </div>
</template>

<script setup>
import {
  Form,
  FormItem,
  Input,
  Select,
  InputNumber,
  Button,
  DatePicker,
  Radio,
  RadioGroup,
  message
} from 'ant-design-vue';
import { ref, computed, watch } from 'vue';
import axios from 'axios';
import dayjs from 'dayjs';

const props = defineProps({
  etapas: Array,
  madreId: Number
});
const emit = defineEmits(['registro-exitoso']);

const cargando = ref(false);
const errores = ref({});

const nino = ref({
  nombre: '',
  sexo: '',
  fecha_nacimiento: null,
  es_prematuro: null,
  semanas_prematuro: '',
  peso_nacimiento: '',
  talla_nacimiento: '',
  etapa_desarrollo_id: '',
  madre_id: props.madreId
});

const enviarFormulario = async () => {
  cargando.value = true;
  errores.value = {};
  try {
    const response = await axios.post(route('ninos.store'), nino.value);
    message.success('Niño registrado correctamente');
    emit('registro-exitoso', response.data);
    resetFormulario();

  } catch (error) {

    message.error('Error al registrar el niño');
    if (error.response?.data?.errors) {
      errores.value = error.response.data.errors;
    }
  } finally {
    cargando.value = false;
  }
};

 const resetFormulario = () => {
  errores.value = {};
  nino.value = {
    nombre: '',
    sexo: '',
    fecha_nacimiento: null,
    es_prematuro: null,
    semanas_prematuro: '',
    peso_nacimiento: '',
    talla_nacimiento: '',
    etapa_desarrollo_id: '',
    madre_id: props.madreId
  };
};

const sexoOptions = ref([
  { value: 'masculino', label: 'Masculino' },
  { value: 'femenino', label: 'Femenino' }
]);

const semanasOptions = ref(
  Array.from({ length: 14 }, (_, i) => ({
    value: 14 + i,
    label: `${14 + i} semanas`
  }))
);

const etapasPredefinidas = ref([
  { value: 1, label: 'Recién Nacido (0-1 mes)' },
  { value: 2, label: 'Lactante (1-12 meses)' },
  { value: 3, label: 'Primera Infancia (1-2 años)' },
  { value: 4, label: 'Segunda Infancia (2-3 años)' }
]);

const etapaOptions = computed(() => {
  return props.etapas?.length
    ? props.etapas.map((e) => ({ value: e.id, label: e.nombre_etapa }))
    : etapasPredefinidas.value;
});

const handlePrematuroChange = (e) => {
  const val = e.target.value;
  nino.value.es_prematuro = val;
  if (!val) nino.value.semanas_prematuro = '';
};

const disabledDate = (current) => {
  return current && current > dayjs().endOf('day');
};

const filtrarEtapa = (input, option) => {
  return option.label.toLowerCase().includes(input.toLowerCase());
};

// Calcular la etapa de desarrollo automáticamente
watch(() => nino.value.fecha_nacimiento, (nuevaFecha) => {
  if (!nuevaFecha) return;
  const hoy = dayjs();
  const nacimiento = dayjs(nuevaFecha);
  const edadMeses = hoy.diff(nacimiento, 'month');

  if (edadMeses <= 1) nino.value.etapa_desarrollo_id = 1;
  else if (edadMeses <= 12) nino.value.etapa_desarrollo_id = 2;
  else if (edadMeses <= 24) nino.value.etapa_desarrollo_id = 3;
  else nino.value.etapa_desarrollo_id = 4;
});

</script>

<template>
  <div class="sm:min-w-[560px] p-6 bg-white rounded shadow">
    <Form layout="vertical" @finish="enviarFormulario" :model="nino">
      <FormItem
        label="Nombre del Niño *"
        name="nombre"
        :rules="[{ required: true, message: 'El nombre es obligatorio' }]"
      >
        <Input v-model:value="nino.nombre" placeholder="Ingrese el nombre completo" />
      </FormItem>

      <FormItem
        label="Sexo *"
        name="sexo"
        :rules="[{ required: true, message: 'Seleccione el sexo' }]"
      >
        <Select
          v-model:value="nino.sexo"
          :options="sexoOptions"
          placeholder="Seleccione..."
        />
      </FormItem>

      <FormItem
        label="Fecha de Nacimiento *"
        name="fecha_nacimiento"
        :rules="[{ required: true, message: 'Seleccione la fecha de nacimiento' }]"
      >
        <DatePicker
          v-model:value="nino.fecha_nacimiento"
          format="DD/MM/YYYY"
          value-format="YYYY-MM-DD"
          style="width: 100%"
          :disabled-date="disabledDate"
          placeholder="Seleccione fecha"
        />
      </FormItem>

      <FormItem
        label="¿Nació prematuro? *"
        name="es_prematuro"
        :rules="[{ required: true, message: 'Indique si fue prematuro' }]"
      >
        <RadioGroup v-model:value="nino.es_prematuro" @change="handlePrematuroChange">
          <Radio :value="true">Sí</Radio>
          <Radio :value="false">No</Radio>
        </RadioGroup>
      </FormItem>

      <FormItem
        v-if="nino.es_prematuro"
        label="Semanas de gestación *"
        name="semanas_prematuro"
        :rules="[{ required: true, message: 'Seleccione las semanas de gestación' }]"
      >
        <Select
          v-model:value="nino.semanas_prematuro"
          :options="semanasOptions"
          placeholder="Seleccione semanas"
        />
      </FormItem>

      <FormItem
        label="Peso al Nacer (kg) *"
        name="peso"
        :rules="[{ required: true, message: 'Ingrese el peso al nacer' }]"
      >
        <InputNumber
          v-model:value="nino.peso"
          :min="0.5"
          :max="5.5"
          :step="0.001"
          placeholder="Ej. 3.250"
          class="w-full"
        />
      </FormItem>

      <FormItem
        label="Talla al Nacer (cm) *"
        name="talla"
        :rules="[{ required: true, message: 'Ingrese la talla al nacer' }]"
      >
        <InputNumber
          v-model:value="nino.talla"
          :min="30"
          :max="60"
          :step="0.1"
          placeholder="Ej. 50.5"
          class="w-full"
        />
      </FormItem>

      <!-- Etapa (asignado automáticamente) -->
      <FormItem
        label="Etapa de Desarrollo (asignado automáticamente)"
        name="etapa_desarrollo_id"
      >
        <Select
          v-model:value="nino.etapa_desarrollo_id"
          :options="etapaOptions"
            disabled
        />
      </FormItem>

      <div class="mt-4 flex justify-end gap-3">
        <Button @click="cancelar">Cancelar</Button>
        <Button type="primary" :loading="cargando" html-type="submit">Guardar Niño</Button>
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
  es_prematuro: false,
  semanas_prematuro: '',
  peso: '',
  talla: '',
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
    es_prematuro: false,
    semanas_prematuro: '',
    peso: '',
    talla: '',
    etapa_desarrollo_id: '',
    madre_id: props.madreId
  };
};

const cancelar = () => {
  resetFormulario();
  window.location.href = route('ninos.index');
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

const etapaOptions = computed(() => {
  console.log('Etapas recibidas del controlador:', props.etapas);
  // El Select de Ant Design espera 'value' y 'label'
  return props.etapas?.map((e) => ({ value: e.id, label: e.label })) || [];
});

const handlePrematuroChange = (e) => {
  const val = e.target.value;
  nino.value.es_prematuro = val;
  if (!val) nino.value.semanas_prematuro = '';
};

const disabledDate = (current) => {
  const hoy = dayjs();
  const haceTresAnios = hoy.subtract(3, 'year').startOf('day');
  // Solo permite seleccionar fechas entre hace 3 años y hoy
  return (
    (current && current > hoy.endOf('day')) ||
    (current && current < haceTresAnios)
  );
};

// Calcular la etapa de desarrollo automáticamente
watch(() => nino.value.fecha_nacimiento, (nuevaFecha) => {
  if (!nuevaFecha || !props.etapas?.length) return;
  
  const hoy = dayjs();
  const nacimiento = dayjs(nuevaFecha);
  const edadMeses = hoy.diff(nacimiento, 'month');

  // Usar las etapas del controlador para determinar la etapa correcta
  // Asumiendo que las etapas están ordenadas por edad
  const etapaCorrecta = props.etapas.find((etapa, index) => {
    if (index === 0 && edadMeses <= 1) return true; // Primera etapa: 0-1 mes
    if (index === 1 && edadMeses > 1 && edadMeses <= 12) return true; // Segunda etapa: 1-12 meses
    if (index === 2 && edadMeses > 12 && edadMeses <= 24) return true; // Tercera etapa: 1-2 años
    if (index === 3 && edadMeses > 24) return true; // Cuarta etapa: 2+ años
    return false;
  });

  if (etapaCorrecta) {
    nino.value.etapa_desarrollo_id = Number(etapaCorrecta.id); // <-- Cambia esto a etapaCorrecta.id
    console.log('Etapa asignada automáticamente:', etapaCorrecta);
  }
});

</script>


<template>
    <a-modal
        :open="open"
        title="Editar información del bebé"
        @cancel="handleCancel"
        :footer="null"
        centered
    >
        <a-form layout="vertical" @submit.prevent="handleSubmit">
            <a-form-item label="Nombre" required>
                <a-input
                    v-model:value="form.nombre"
                    placeholder="Nombre del bebé"
                />
            </a-form-item>
            <a-form-item label="Fecha de nacimiento" required>
                <a-date-picker
                    v-model:value="form.fecha_nacimiento"
                    style="width: 100%"
                    format="YYYY-MM-DD"
                />
            </a-form-item>
            <a-form-item label="Sexo" required>
                <a-select v-model:value="form.sexo" style="width: 100%">
                    <ASelect.Option value="masculino">Masculino</ASelect.Option>
                    <ASelect.Option value="femenino">Femenino</ASelect.Option>
                </a-select>
            </a-form-item>
            <!-- Peso y Talla en la misma fila -->
            <div class="flex gap-4">
                <a-form-item
                    label="Peso nacimiento (kg)"
                    required
                    class="flex-1"
                >
                    <a-input-number
                        v-model:value="form.peso_nacimiento"
                        :min="0"
                        style="width: 100%"
                    />
                </a-form-item>
                <a-form-item
                    label="Talla nacimiento (cm)"
                    required
                    class="flex-1"
                >
                    <a-input-number
                        v-model:value="form.talla_nacimiento"
                        :min="0"
                        style="width: 100%"
                    />
                </a-form-item>
            </div>
            <!-- Es prematuro y semanas en la misma fila -->
            <div class="flex gap-4 items-end">
                <a-form-item label="¿Es prematuro?" required class="flex-1">
                    <a-switch v-model:checked="form.es_prematuro" />
                </a-form-item>
                <a-form-item
                    v-if="form.es_prematuro"
                    label="Semanas prematuro"
                    class="flex-1"
                >
                    <a-input-number
                        v-model:value="form.semanas_prematuro"
                        :min="0"
                        style="width: 100%"
                    />
                </a-form-item>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <a-button @click="handleCancel">Cancelar</a-button>
                <a-button type="primary" html-type="submit" :loading="loading"
                    >Guardar</a-button
                >
            </div>
        </a-form>
    </a-modal>
</template>

<script setup>
import {
    Modal as AModal,
    Form as AForm,
    FormItem as AFormItem,
    Input as AInput,
    InputNumber as AInputNumber,
    DatePicker as ADatePicker,
    Select as ASelect,
    Switch as ASwitch,
    Button as AButton,
    message as AMessage,
} from "ant-design-vue";
import { ref, watch } from "vue";
import axios from "axios";
import dayjs from "dayjs";

const props = defineProps({
    open: Boolean,
    nino: {
        type: Object,
        required: true,
    },
});
const emit = defineEmits(["cancel", "actualizado"]);

const form = ref({
    nombre: "",
    fecha_nacimiento: null,
    sexo: "masculino",
    peso_nacimiento: 0,
    talla_nacimiento: 0,
    es_prematuro: false,
    semanas_prematuro: null,
});
const loading = ref(false);
const errores = ref({});

watch(
    () => props.open,
    (nuevoValor) => {
        if (nuevoValor && props.nino) {
            form.value = {
                nombre: props.nino.nombre || "",
                fecha_nacimiento: props.nino.fecha_nacimiento
                    ? dayjs(props.nino.fecha_nacimiento)
                    : null,
                sexo: props.nino.sexo || "masculino",
                peso_nacimiento:
                    props.nino.peso_nacimiento || props.nino.peso || 0,
                talla_nacimiento:
                    props.nino.talla_nacimiento || props.nino.talla || 0,
                es_prematuro: !!props.nino.es_prematuro,
                semanas_prematuro: props.nino.semanas_prematuro || null,
            };
            errores.value = {};
        }
    },
    { immediate: true }
);

function handleCancel() {
    emit("cancel");
}

async function handleSubmit() {
    loading.value = true;
    errores.value = {};
    try {
        // Formatear fecha
        const fechaNacimiento = form.value.fecha_nacimiento
            ? form.value.fecha_nacimiento.format
                ? form.value.fecha_nacimiento.format("YYYY-MM-DD")
                : form.value.fecha_nacimiento
            : null;
        const payload = {
            nombre: form.value.nombre,
            fecha_nacimiento: fechaNacimiento,
            sexo: form.value.sexo,
            peso_nacimiento: form.value.peso_nacimiento,
            talla_nacimiento: form.value.talla_nacimiento,
            peso: form.value.peso_nacimiento, // Enviar también como peso
            talla: form.value.talla_nacimiento, // Enviar también como talla
            es_prematuro: form.value.es_prematuro,
            semanas_prematuro: form.value.es_prematuro
                ? form.value.semanas_prematuro
                : null,
        };
        console.log("Payload enviado al backend:", payload);
        await axios.post(route("ninos.update", props.nino.id), payload);
        AMessage.success("¡Datos actualizados correctamente!");
        emit("actualizado");
        handleCancel();
    } catch (error) {
        if (
            error.response &&
            error.response.data &&
            error.response.data.errors
        ) {
            errores.value = error.response.data.errors;
            // Mostrar todos los errores con AMessage
            Object.values(errores.value)
                .flat()
                .forEach((msg) => {
                    AMessage.error(msg);
                });
        } else {
            AMessage.error("Error al actualizar los datos");
        }
        console.error(error);
    } finally {
        loading.value = false;
    }
}
</script>

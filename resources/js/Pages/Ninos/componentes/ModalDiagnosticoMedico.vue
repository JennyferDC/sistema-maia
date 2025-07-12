<template>
    <a-modal
        :open="open"
        :title="'Registrar diagnóstico médico'"
        @cancel="handleCancel"
        :footer="null"
    >
        <a-form layout="vertical" @submit.prevent="handleSubmit">
            <a-form-item label="Tipo de diagnóstico" required>
                <a-input
                    v-model:value="form.tipo_diagnostico"
                    placeholder="Ej. Epilepsia, TDAH, etc."
                />
            </a-form-item>
            <a-form-item label="Descripción (opcional)">
                <a-input.TextArea
                    v-model:value="form.descripcion"
                    placeholder="Detalles, observaciones, etc."
                    :rows="2"
                />
            </a-form-item>
            <div class="flex gap-4">
                <a-form-item
                    label="¿Cuándo inició? (aprox.)"
                    required
                    class="flex-1"
                >
                    <a-date-picker
                        v-model:value="form.fecha_inicio"
                        style="width: 100%"
                        format="YYYY-MM-DD"
                    />
                </a-form-item>
                <a-form-item
                    label="¿Cuándo terminó? (opcional)"
                    class="flex-1"
                >
                    <a-date-picker
                        v-model:value="form.fecha_fin"
                        style="width: 100%"
                        format="YYYY-MM-DD"
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
    DatePicker as ADatePicker,
    Button as AButton,
    message as AMessage,
} from "ant-design-vue";
import { ref, watch } from "vue";
import axios from "axios";

const props = defineProps({
    open: Boolean,
    nino: {
        type: Object,
        required: true,
    },
});
const emit = defineEmits(["cancel"]);

const form = ref({
    tipo_diagnostico: "",
    descripcion: "",
    fecha_inicio: null,
    fecha_fin: null,
});
const loading = ref(false);

watch(
    () => props.open,
    (nuevoValor) => {
        if (nuevoValor) {
            form.value = {
                tipo_diagnostico: "",
                descripcion: "",
                fecha_inicio: null,
                fecha_fin: null,
            };
        }
    }
);

function handleCancel() {
    emit("cancel");
}

async function handleSubmit() {
    loading.value = true;
    try {
        // Formatear fechas a YYYY-MM-DD
        const fechaInicio = form.value.fecha_inicio
            ? typeof form.value.fecha_inicio === "string"
                ? form.value.fecha_inicio.substring(0, 10)
                : form.value.fecha_inicio.format
                ? form.value.fecha_inicio.format("YYYY-MM-DD")
                : null
            : null;

        const fechaFin = form.value.fecha_fin
            ? typeof form.value.fecha_fin === "string"
                ? form.value.fecha_fin.substring(0, 10)
                : form.value.fecha_fin.format
                ? form.value.fecha_fin.format("YYYY-MM-DD")
                : null
            : null;

        await axios.post(route("diagnosticos.store"), {
            tipo_diagnostico: form.value.tipo_diagnostico,
            descripcion: form.value.descripcion,
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin,
            nino_id: props.nino.id,
        });
        AMessage.success("¡Diagnóstico guardado correctamente!");
        handleCancel();
    } catch (error) {
        AMessage.error("Error al guardar el diagnóstico");
        console.error("errorr", error);
    } finally {
        loading.value = false;
    }
}
</script>

<style scoped></style>

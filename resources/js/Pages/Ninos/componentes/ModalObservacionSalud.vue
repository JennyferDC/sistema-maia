<template>
    <a-modal
        :open="open"
        :title="'Registrar entrada en el diario de salud'"
        @cancel="handleCancel"
        :footer="null"
    >
        <a-form layout="vertical" @submit.prevent="handleSubmit">
            <a-form-item label="Tipo de observación" required>
                <a-input
                    v-model:value="form.tipo_observacion"
                    placeholder="Ej. Fiebre, Tos, etc."
                />
            </a-form-item>
            <a-form-item label="Observaciones (opcional)">
                <a-input.TextArea
                    v-model:value="form.observaciones"
                    placeholder="Detalles, observaciones, hora, intensidad, etc."
                    :rows="2"
                />
            </a-form-item>
            <div class="flex justify-end gap-2 mt-6">
                <a-button @click="handleCancel">Cancelar</a-button>
                <a-button type="primary" html-type="submit" :loading="loading"
                    >Guardar entrada</a-button
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
const emit = defineEmits(["cancel", "guardado"]);

const form = ref({
    tipo_observacion: "",
    observaciones: "",
});
const loading = ref(false);

watch(
    () => props.open,
    (nuevoValor) => {
        if (nuevoValor) {
            form.value = {
                tipo_observacion: "",
                observaciones: "",
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
        await axios.post(route("observaciones.store", props.nino.id), {
            tipo_observacion: form.value.tipo_observacion,
            observaciones: form.value.observaciones,
        });
        AMessage.success("¡Entrada guardada correctamente!");
        emit("guardado");
        handleCancel();
    } catch (error) {
        AMessage.error("Error al guardar la entrada");
        console.error(error);
    } finally {
        loading.value = false;
    }
}
</script>

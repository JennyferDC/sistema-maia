<template>
    <a-modal
        :open="open"
        :title="'Confirmar logro del hito'"
        @cancel="handleCancel"
        @ok="handleConfirm"
        ok-text="Confirmar"
        cancel-text="Cancelar"
        :confirmLoading="loading"
    >
        <a-form layout="vertical" class="mt-0">
            <div class="mb-4 mt-5">
                <p>
                    ¿En qué etapa el niño/a logró
                    <b>"{{ hito?.nombre_hito }}"</b>?
                </p>
                <p class="text-gray-500 text-sm mb-2">
                    Normalmente se logra en:
                    <b>{{ getNombreEtapa(hito?.etapa_desarrollo_id) }}</b>
                </p>
            </div>
            <a-radio-group v-model:value="etapaSeleccionada">
                <div v-for="etapa in etapas" :key="etapa.id" class="mb-2">
                    <a-radio :value="etapa.id">
                        {{ etapa.nombre_etapa }}
                    </a-radio>
                </div>
            </a-radio-group>
            <a-form-item
                label="Observaciones (opcional)"
                :help="'Mientras más información nos brinde del niño, mejor podremos evaluar su desarrollo.'"
                class="mt-6"
            >
                <a-input.TextArea
                    v-model:value="observaciones"
                    placeholder="Ejm: Lo logró pero con mucha dificultad y práctica"
                />
            </a-form-item>
        </a-form>
    </a-modal>
</template>

<script setup>
import { ref, watch } from "vue";
import {
    Modal as AModal,
    Radio as ARadio,
    RadioGroup as ARadioGroup,
    Form as AForm,
    FormItem as AFormItem,
    Input as AInput,
    message as AMessage,
} from "ant-design-vue";
import axios from "axios";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    open: Boolean,
    etapas: Array,
    hito: Object,
    ninoId: {
        type: [Number, String],
        required: true,
    },
});
const emit = defineEmits(["cancel"]);

const etapaSeleccionada = ref(null);
const observaciones = ref("");
const loading = ref(false);

watch(
    () => props.hito,
    (nuevoHito) => {
        if (nuevoHito && nuevoHito.etapa_desarrollo_id) {
            etapaSeleccionada.value = nuevoHito.etapa_desarrollo_id;
        }
        observaciones.value =
            nuevoHito && nuevoHito.observaciones ? nuevoHito.observaciones : "";
    },
    { immediate: true }
);

const getNombreEtapa = (id) => {
    const etapa = props.etapas?.find((e) => e.id === id);
    return etapa ? etapa.nombre_etapa : "";
};

const handleCancel = () => {
    emit("cancel");
};
const handleConfirm = async () => {
    loading.value = true;
    try {
        await axios.post(route("hitos-logrados.store", props.ninoId), {
            hito_id: props.hito.id,
            etapa_desarrollo_id: etapaSeleccionada.value,
            observaciones: observaciones.value,
            // Puedes agregar fecha_logro si lo deseas
        });
        AMessage.success("¡Hito guardado correctamente!");
        // Recargar la ruta actual manteniendo el scroll
        router.visit(route("etapas.por-nino", props.ninoId), {
            preserveScroll: true,
        });
    } catch (error) {
        AMessage.error("Error al guardar el hito.");
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped></style>

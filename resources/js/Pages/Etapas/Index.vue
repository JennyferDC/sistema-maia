<template>
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Hitos por Etapa de Desarrollo</h1>

        <div v-for="etapa in etapas" :key="etapa.id" class="mb-8">
            <div class="mb-2 flex items-center justify-between">
                <div>
                    <span class="text-lg font-semibold">{{
                        getNombre(etapa.nombre_etapa)
                    }}</span>
                    <span class="text-gray-500 ml-2">{{
                        getRango(etapa.nombre_etapa)
                    }}</span>
                </div>
                <AButton
                    class="ml-4"
                    type="primary"
                    @click="() => abrirModalEvaluacion(etapa)"
                >
                    Evaluación
                </AButton>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <a-card
                    v-for="hito in etapa.hitos"
                    :key="hito.id"
                    class="border border-gray-200 shadow-sm cursor-pointer"
                    :bodyStyle="{ padding: '8px' }"
                    @click="() => abrirModal(etapa, hito)"
                >
                    <div
                        class="hito-checkbox-row"
                        @click.stop="() => abrirModal(etapa, hito)"
                    >
                        <span
                            class="custom-checkbox"
                            :class="{ checked: hito.completado }"
                        >
                            <svg
                                v-if="hito.completado"
                                class="check-icon"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <circle cx="10" cy="10" r="9" fill="#22c55e" />
                                <path
                                    d="M6 10.5L9 13.5L14 8.5"
                                    stroke="white"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>
                        <span class="ml-4">{{ hito.nombre_hito }}</span>
                    </div>
                </a-card>

                <span
                    v-if="!etapa.hitos.length"
                    class="text-gray-400 col-span-full"
                >
                    Sin hitos registrados
                </span>
            </div>

            <ModalHitoLogrado
                :open="mostrarModal"
                :etapas="etapas"
                :hito="hitoSeleccionado"
                :nino-id="nino.id"
                @cancel="cerrarModal"
            />
            <ModalEvaluacionEtapa
                :open="mostrarModalEvaluacion"
                :etapa="etapaSeleccionada"
                :nino="nino"
                :evaluacion="evaluacionSeleccionada"
                @cancel="cerrarModalEvaluacion"
            />
        </div>
    </div>
</template>

<script setup>
import { Card as ACard, Button as AButton } from "ant-design-vue";
import ModalHitoLogrado from "./ModalHitoLogrado.vue";
import ModalEvaluacionEtapa from "./ModalEvaluacionEtapa.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    etapas: {
        type: Array,
        required: true,
    },
    nino: {
        type: Object,
        required: true,
        default: null,
    },
});

const mostrarModal = ref(false);
const hitoSeleccionado = ref(null);

// Marcar los hitos logrados por el niño
if (props.nino && props.nino.hito_logrados) {
    const logradosIds = props.nino.hito_logrados.map((h) => h.hito_id);
    props.etapas.forEach((etapa) => {
        etapa.hitos.forEach((hito) => {
            hito.completado = logradosIds.includes(hito.id);
        });
    });
} else {
    // Inicializa el estado visual (opcional)
    props.etapas.forEach((etapa) => {
        etapa.hitos.forEach((hito) => {
            if (hito.completado === undefined) hito.completado = false;
        });
    });
}

function abrirModal(etapa, hito) {
    hitoSeleccionado.value = { ...hito };
    mostrarModal.value = true;
}
function cerrarModal() {
    mostrarModal.value = false;
    hitoSeleccionado.value = null;
}

const mostrarModalEvaluacion = ref(false);
const etapaSeleccionada = ref(null);
const evaluacionSeleccionada = ref(null);

function abrirModalEvaluacion(etapa) {
    etapaSeleccionada.value = { ...etapa };
    // Buscar la evaluación de esta etapa para el niño
    if (props.nino && Array.isArray(props.nino.evaluaciones)) {
        evaluacionSeleccionada.value =
            props.nino.evaluaciones.find(
                (ev) => ev.etapa_desarrollo_id === etapa.id
            ) || null;
    } else {
        evaluacionSeleccionada.value = null;
    }
    mostrarModalEvaluacion.value = true;
}
function cerrarModalEvaluacion() {
    mostrarModalEvaluacion.value = false;
    etapaSeleccionada.value = null;
    evaluacionSeleccionada.value = null;
}

console.log("Props recibidos en Etapas/Index.vue:", {
    etapas: props.etapas,
    nino: props.nino,
});

// ✅ Mostrar u ocultar panel flotante
const togglePanel = (hito) => {
  hito.mostrarPanel = hito.marcado;
};

// 🧠 Extraer solo nombre sin paréntesis
const getNombre = (nombre_etapa) => {
    const idx = nombre_etapa.indexOf("(");
    return idx !== -1 ? nombre_etapa.slice(0, idx).trim() : nombre_etapa;
};

// 🧠 Extraer texto dentro del paréntesis
const getRango = (nombre_etapa) => {
    const match = nombre_etapa.match(/\(([^)]+)\)/);
    return match ? match[1] : "";
};
</script>

<style scoped>
:deep(.ant-card) {
    border-radius: 16px;
    font-size: 15px;
    box-shadow: 0 2px 8px 0 rgba(34, 197, 94, 0.08),
        0 1.5px 4px 0 rgba(0, 0, 0, 0.04);
    border: 1.5px solid #e5e7eb;
    padding: 14px 18px;
    margin-bottom: 10px;
    transition: box-shadow 0.25s, border-color 0.25s;
    min-height: 64px;
    display: flex;
    align-items: center;
}
:deep(.ant-card):hover {
    box-shadow: 0 4px 16px 0 rgba(34, 197, 94, 0.18),
        0 3px 8px 0 rgba(0, 0, 0, 0.08);
    border-color: #22c55e;
}

.hito-checkbox-row {
    display: flex;
    align-items: center;
    height: 100%;
    width: 100%;
}
.custom-checkbox {
    min-width: 28px;
    min-height: 28px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2.5px solid #d1d5db;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: border-color 0.2s, background 0.2s;
    box-sizing: border-box;
    flex-shrink: 0;
}
.custom-checkbox.checked {
    border-color: #22c55e;
    background: #e6faed;
}
.check-icon {
    width: 22px;
    height: 22px;
    display: block;
}
</style>

<template>
    <AppLayout :title="`Detalle de ${nino.nombre}`">
        <template #header>
            <h1 class="text-xl font-semibold text-pink-800 mb-0">
                Detalle del Bebé
            </h1>
        </template>
        <div class="p-6 md:p-10">
            <div
                class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8"
            >
                <!-- Columna 1: Card de info básica, diagnósticos, diario de salud, evaluaciones y fotos -->
                <div>
                    <div class="bg-white rounded-xl shadow-md p-8 mb-8">
                        <!-- Información básica mejorada -->
                        <div
                            class="flex flex-col md:flex-row gap-8 items-center mb-8"
                        >
                            <!-- Foto principal eliminada -->
                            <!-- Datos principales visuales -->
                            <div
                                class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 relative"
                            >
                                <AButton
                                    type="default"
                                    shape="circle"
                                    class="absolute top-0 right-0 flex items-center justify-center !p-0 text-pink-600"
                                    @click="abrirModalEditarNino"
                                    title="Editar"
                                    :icon="h(EditOutlined)"
                                >
                                </AButton>
                                <div class="col-span-2">
                                    <span
                                        class="block font-bold text-2xl text-gray-800 mb-0 text-center"
                                        >{{ nino.nombre }}</span
                                    >
                                </div>
                                <div
                                    class="flex flex-col items-center col-span-2"
                                >
                                    <span
                                        class="px-2 py-1 bg-pink-100 text-pink-800 rounded-full text-xs font-medium text-center mb-2"
                                    >
                                        {{
                                            nino.etapa_desarrollo?.nombre ||
                                            nino.etapa_desarrollo
                                                ?.nombre_etapa ||
                                            "Sin etapa"
                                        }}
                                    </span>
                                </div>
                                <!-- Tag de sexo eliminado -->
                                <div class="flex items-center gap-3">
                                    <CalendarOutlined
                                        class="text-pink-400 text-xl"
                                    />
                                    <span class="text-gray-600"
                                        >Nacimiento:
                                        <b>{{
                                            formatearFecha(
                                                nino.fecha_nacimiento,
                                                "mediano"
                                            )
                                        }}</b></span
                                    >
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        v-if="nino.sexo === 'masculino'"
                                        class="text-blue-400 text-xl"
                                    >
                                        <ManOutlined />
                                    </span>
                                    <span v-else class="text-pink-400 text-xl">
                                        <WomanOutlined />
                                    </span>
                                    <span class="text-gray-600"
                                        >Sexo:
                                        <b class="capitalize">{{
                                            nino.sexo
                                        }}</b></span
                                    >
                                </div>
                                <div class="flex items-center gap-3">
                                    <ShoppingOutlined
                                        class="text-pink-400 text-xl"
                                    />
                                    <span class="text-gray-600"
                                        >Peso nacimiento:
                                        <b
                                            >{{
                                                nino.peso_nacimiento ||
                                                nino.peso
                                            }}
                                            kg</b
                                        ></span
                                    >
                                </div>
                                <div class="flex items-center gap-3">
                                    <AppstoreOutlined
                                        class="text-pink-400 text-xl"
                                    />
                                    <span class="text-gray-600"
                                        >Talla nacimiento:
                                        <b
                                            >{{
                                                nino.talla_nacimiento ||
                                                nino.talla
                                            }}
                                            cm</b
                                        ></span
                                    >
                                </div>
                                <div class="flex items-center gap-3">
                                    <ClockCircleOutlined
                                        class="text-pink-400 text-xl"
                                    />
                                    <span class="text-gray-600">
                                        Prematuro:
                                        <b>{{
                                            nino.es_prematuro ? "Sí" : "No"
                                        }}</b>
                                        <template v-if="nino.es_prematuro">
                                            <span
                                                class="ml-2 text-xs text-gray-500"
                                                >({{
                                                    nino.semanas_prematuro
                                                }}
                                                semanas)</span
                                            >
                                        </template>
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <UserOutlined
                                        class="text-pink-400 text-xl"
                                    />
                                    <span class="text-gray-600"
                                        >Madre:
                                        <b>{{ nino.madre?.name }}</b></span
                                    >
                                </div>
                            </div>
                        </div>
                        <hr class="my-6 border-t border-gray-200" />
                        <!-- Diagnósticos médicos -->
                        <div class="mt-8">
                            <h2 class="text-xl font-bold text-pink-700 mb-2">
                                Diagnósticos médicos
                            </h2>
                            <AButton
                                type="primary"
                                @click="abrirModalDiagnostico"
                            >
                                Agregar diagnóstico
                            </AButton>
                            <div
                                v-if="
                                    nino.diagnostico_medico &&
                                    nino.diagnostico_medico.length
                                "
                                class="mt-4"
                            >
                                <ul class="divide-y divide-gray-200">
                                    <li
                                        v-for="diag in nino.diagnostico_medico"
                                        :key="diag.id"
                                        class="py-2"
                                    >
                                        <span class="font-semibold">{{
                                            diag.tipo_diagnostico
                                        }}</span
                                        >:
                                        {{ diag.descripcion }}
                                        <span class="text-xs text-gray-500">
                                            ({{ diag.fecha_inicio }} -
                                            {{ diag.fecha_fin || "actual" }})
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div v-else class="text-gray-400 mt-4">
                                No hay diagnósticos médicos registrados.
                            </div>
                        </div>
                        <hr class="my-6 border-t border-gray-200" />
                        <!-- Diario de salud -->
                        <div class="mt-8">
                            <h2 class="text-xl font-bold text-pink-700 mb-2">
                                Diario de salud
                            </h2>
                            <AButton
                                type="primary"
                                @click="abrirModalObservacionSalud"
                            >
                                Agregar entrada
                            </AButton>
                            <div
                                v-if="
                                    nino.observaciones_salud &&
                                    nino.observaciones_salud.length
                                "
                                class="mt-4"
                            >
                                <ul class="divide-y divide-gray-200">
                                    <li
                                        v-for="obs in nino.observaciones_salud"
                                        :key="obs.id"
                                        class="py-2"
                                    >
                                        <span class="font-semibold">{{
                                            obs.tipo_observacion
                                        }}</span
                                        >: {{ obs.observaciones }}
                                        <span class="text-xs text-gray-500"
                                            >({{ obs.fecha_registro }})</span
                                        >
                                    </li>
                                </ul>
                            </div>
                            <div v-else class="text-gray-400 mt-4">
                                No hay entradas en el diario de salud.
                            </div>
                        </div>
                        <hr class="my-6 border-t border-gray-200" />
                        <!-- Evaluaciones -->
                        <div class="mt-8">
                            <h2 class="text-xl font-bold text-pink-700 mb-2">
                                Evaluaciones
                            </h2>
                            <div
                                v-if="
                                    nino.evaluaciones &&
                                    nino.evaluaciones.length
                                "
                            >
                                <ul class="divide-y divide-gray-200">
                                    <li
                                        v-for="eva in nino.evaluaciones"
                                        :key="eva.id"
                                        class="py-2"
                                    >
                                        <span class="font-semibold"
                                            >{{ eva.fecha_evaluacion }}:</span
                                        >
                                        Peso: {{ eva.peso }} kg, Talla:
                                        {{ eva.talla }} cm
                                        <span v-if="eva.comentario_madre"
                                            >- {{ eva.comentario_madre }}</span
                                        >
                                    </li>
                                </ul>
                            </div>
                            <div v-else class="text-gray-400">
                                No hay evaluaciones registradas.
                            </div>
                        </div>
                        <hr class="my-6 border-t border-gray-200" />
                        <!-- Fotos -->
                        <div class="mt-8">
                            <h2 class="text-xl font-bold text-pink-700 mb-2">
                                Fotos
                            </h2>
                            <div v-if="nino.fotos && nino.fotos.length">
                                <div class="flex flex-wrap gap-4">
                                    <div
                                        v-for="foto in nino.fotos"
                                        :key="foto.id"
                                        class="w-24 h-24"
                                    >
                                        <img
                                            :src="foto.ruta_foto"
                                            alt="Foto"
                                            class="w-full h-full object-cover rounded-lg border"
                                        />
                                        <div
                                            class="text-xs text-gray-500 truncate"
                                        >
                                            {{ foto.descripcion }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-gray-400">
                                No hay fotos registradas.
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Columna 2: Card con análisis diario con IA -->
                <div>
                    <div class="bg-white rounded-xl shadow-md p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-pink-700 mb-0">
                                Análisis diario con IA
                            </h2>
                            <AButton
                                type="primary"
                                @click="generarAnalisisIA"
                                :loading="cargandoIA"
                            >
                                Generar análisis
                            </AButton>
                        </div>
                        <!-- Recomendaciones primero -->
                        <div class="mt-0">
                            <h2 class="text-xl font-bold text-pink-700 mb-2">
                                Recomendaciones
                            </h2>
                            <div
                                v-if="
                                    nino.recomendaciones &&
                                    nino.recomendaciones.length
                                "
                            >
                                <ul class="divide-y divide-gray-200">
                                    <li
                                        v-for="rec in nino.recomendaciones"
                                        :key="
                                            rec.tipo_recomendacion +
                                            rec.descripcion
                                        "
                                        class="py-2"
                                    >
                                        <span class="font-semibold">{{
                                            rec.tipo_recomendacion
                                        }}</span
                                        >: {{ rec.descripcion }}
                                    </li>
                                </ul>
                            </div>
                            <div v-else class="text-gray-400">
                                No hay recomendaciones generadas.
                            </div>
                        </div>
                        <hr class="my-6 border-t border-gray-200" />
                        <!-- Alertas -->
                        <div class="mt-0">
                            <h2 class="text-xl font-bold text-pink-700 mb-2">
                                Alertas
                            </h2>
                            <div v-if="nino.alertas && nino.alertas.length">
                                <ul class="divide-y divide-gray-200">
                                    <li
                                        v-for="alerta in nino.alertas"
                                        :key="alerta.id || alerta.tipo_alerta"
                                        class="py-2"
                                    >
                                        <span class="font-semibold">{{
                                            alerta.tipo_alerta
                                        }}</span
                                        >: {{ alerta.descripcion }}
                                        <span
                                            v-if="alerta.fecha_generada"
                                            class="text-xs text-gray-500"
                                        >
                                            ({{ alerta.fecha_generada }})
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div v-else class="text-gray-400">
                                No hay alertas registradas.
                            </div>
                        </div>
                        <hr class="my-6 border-t border-gray-200" />
                        <!-- Predicciones -->
                        <div class="mt-8">
                            <h2 class="text-xl font-bold text-pink-700 mb-2">
                                Predicciones
                            </h2>
                            <div
                                v-if="
                                    nino.predicciones &&
                                    nino.predicciones.length
                                "
                            >
                                <ul class="divide-y divide-gray-200">
                                    <li
                                        v-for="pred in nino.predicciones"
                                        :key="pred.id"
                                        class="py-2"
                                    >
                                        <span class="font-semibold">{{
                                            pred.tipo
                                        }}</span
                                        >: {{ pred.resultado }}
                                        <span class="text-xs text-gray-500">
                                            (Prob: {{ pred.probabilidad }})
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div v-else class="text-gray-400">
                                No hay predicciones registradas.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ModalDiagnosticoMedico
                :open="mostrarModalDiagnostico"
                :nino="nino"
                @cancel="cerrarModalDiagnostico"
            />
            <ModalEditarNino
                :open="mostrarModalEditarNino"
                :nino="nino"
                @cancel="cerrarModalEditarNino"
                @actualizado="onNinoActualizado"
            />
            <ModalObservacionSalud
                :open="mostrarModalObservacionSalud"
                :nino="nino"
                @cancel="cerrarModalObservacionSalud"
                @guardado="onObservacionGuardada"
            />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineProps, ref, h } from "vue";
import {
    UserOutlined,
    CalendarOutlined,
    ShoppingOutlined,
    AppstoreOutlined,
    ClockCircleOutlined,
    EditOutlined,
    ManOutlined,
    WomanOutlined,
} from "@ant-design/icons-vue";
import { Button as AButton } from "ant-design-vue";
import ModalDiagnosticoMedico from "./componentes/ModalDiagnosticoMedico.vue";
import ModalEditarNino from "./componentes/ModalEditarNino.vue";
import ModalObservacionSalud from "./componentes/ModalObservacionSalud.vue";
import { formatearFecha } from "@/utils/date.js";
import axios from "axios";

const props = defineProps({
    nino: Object,
});

const mostrarModalDiagnostico = ref(false);
function abrirModalDiagnostico() {
    mostrarModalDiagnostico.value = true;
}
function cerrarModalDiagnostico() {
    mostrarModalDiagnostico.value = false;
}

const mostrarModalEditarNino = ref(false);
function abrirModalEditarNino() {
    mostrarModalEditarNino.value = true;
}
function cerrarModalEditarNino() {
    mostrarModalEditarNino.value = false;
}
function onNinoActualizado() {
    // Recargar la página o los datos del niño si es necesario
    window.location.reload();
}

const mostrarModalObservacionSalud = ref(false);
function abrirModalObservacionSalud() {
    mostrarModalObservacionSalud.value = true;
}
function cerrarModalObservacionSalud() {
    mostrarModalObservacionSalud.value = false;
}
function onObservacionGuardada() {
    window.location.reload();
}

const cargandoIA = ref(false);

const config = {
    responseMimeType: "application/json",
    responseSchema: {
        type: "array",
        items: {
            type: "object",
            properties: {
                tipo_alerta: { type: "string" },
                descripcion: { type: "string" },
            },
            propertyOrdering: ["tipo_alerta", "descripcion"],
        },
    },
};

async function generarAnalisisIA() {
    cargandoIA.value = true;
    try {
        // Recomendaciones
        const recPromise = axios.post("/api/gemini/recomendaciones", {
            nino_id: props.nino.id,
        });
        // Alertas
        const alertPromise = axios.post("/api/gemini/alertas", {
            nino_id: props.nino.id,
        });
        // Predicciones
        const predPromise = axios.post("/api/gemini/predicciones", {
            nino_id: props.nino.id,
        });
        const [recRes, alertRes, predRes] = await Promise.all([
            recPromise,
            alertPromise,
            predPromise,
        ]);
        // Recomendaciones
        if (
            Array.isArray(recRes.data.recomendaciones) &&
            recRes.data.recomendaciones.every(
                (r) =>
                    typeof r.tipo_recomendacion === "string" &&
                    typeof r.descripcion === "string"
            )
        ) {
            props.nino.recomendaciones = recRes.data.recomendaciones;
            console.log(
                "Recomendaciones generadas por IA:",
                recRes.data.recomendaciones
            );
        } else {
            props.nino.recomendaciones = [];
            console.error(
                "La respuesta de Gemini no cumple con el esquema esperado (recomendaciones).",
                recRes.data.recomendaciones
            );
        }
        // Alertas
        if (
            Array.isArray(alertRes.data.alertas) &&
            alertRes.data.alertas.every(
                (a) =>
                    typeof a.tipo_alerta === "string" &&
                    typeof a.descripcion === "string"
            )
        ) {
            props.nino.alertas = alertRes.data.alertas;
            console.log("Alertas generadas por IA:", alertRes.data.alertas);
        } else {
            props.nino.alertas = [];
            console.error(
                "La respuesta de Gemini no cumple con el esquema esperado (alertas).",
                alertRes.data.alertas
            );
        }
        // Predicciones
        if (
            Array.isArray(predRes.data.predicciones) &&
            predRes.data.predicciones.every(
                (p) =>
                    typeof p.tipo_prediccion === "string" &&
                    typeof p.resultado === "string" &&
                    typeof p.probabilidad === "string"
            )
        ) {
            props.nino.predicciones = predRes.data.predicciones;
            console.log(
                "Predicciones generadas por IA:",
                predRes.data.predicciones
            );
        } else {
            props.nino.predicciones = [];
            console.error(
                "La respuesta de Gemini no cumple con el esquema esperado (predicciones).",
                predRes.data.predicciones
            );
        }
    } catch (error) {
        console.error("Error generando análisis IA:", error);
    } finally {
        cargandoIA.value = false;
    }
}
</script>

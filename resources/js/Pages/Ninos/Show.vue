<template>
    <AppLayout :title="`Detalle de ${nino.nombre}`">
        <template #header>
            <h1 class="text-xl font-semibold text-pink-800 mb-0">
                Detalle del Bebé
            </h1>
        </template>
        <div class="p-6 md:p-10">
            <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md p-8">
                <!-- Información básica mejorada -->
                <div class="flex flex-col md:flex-row gap-8 items-center mb-8">
                    <!-- Foto principal -->
                    <div class="flex-shrink-0 flex flex-col items-center">
                        <img
                            v-if="nino.fotos && nino.fotos.length > 0"
                            :src="nino.fotos[0].ruta_foto"
                            alt="Foto del bebé"
                            class="w-40 h-40 object-cover rounded-full mb-2 shadow"
                        />
                        <div
                            v-else
                            class="w-40 h-40 flex items-center justify-center bg-pink-100 rounded-full text-pink-400 text-6xl mb-2 shadow"
                        >
                            <UserOutlined />
                        </div>
                        <span class="text-gray-400 text-xs"
                            >ID: {{ nino.id }}</span
                        >
                    </div>
                    <!-- Datos principales visuales -->
                    <div
                        class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 relative"
                    >
                        <AButton
                            type="default"
                            class="absolute top-0 right-0"
                            @click="abrirModalEditarNino"
                            :icon="h(EditOutlined)"
                        >
                            Editar datos
                        </AButton>
                        <div class="col-span-2">
                            <span
                                class="block font-bold text-2xl text-gray-800 mb-2"
                                >{{ nino.nombre }}</span
                            >
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="px-2 py-1 bg-pink-100 text-pink-800 rounded-full text-xs font-medium"
                            >
                                {{
                                    nino.etapa_desarrollo?.nombre ||
                                    nino.etapaDesarrollo?.nombre_etapa
                                }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <CalendarOutlined class="text-pink-400 text-xl" />
                            <span class="text-gray-600"
                                >Nacimiento:
                                <b>{{ nino.fecha_nacimiento }}</b></span
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
                                <b class="capitalize">{{ nino.sexo }}</b></span
                            >
                        </div>
                        <div class="flex items-center gap-3">
                            <ShoppingOutlined class="text-pink-400 text-xl" />
                            <span class="text-gray-600"
                                >Peso nacimiento:
                                <b
                                    >{{
                                        nino.peso_nacimiento || nino.peso
                                    }}
                                    kg</b
                                ></span
                            >
                        </div>
                        <div class="flex items-center gap-3">
                            <AppstoreOutlined class="text-pink-400 text-xl" />
                            <span class="text-gray-600"
                                >Talla nacimiento:
                                <b
                                    >{{
                                        nino.talla_nacimiento || nino.talla
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
                                <b>{{ nino.es_prematuro ? "Sí" : "No" }}</b>
                                <template v-if="nino.es_prematuro">
                                    <span class="ml-2 text-xs text-gray-500"
                                        >({{
                                            nino.semanas_prematuro
                                        }}
                                        semanas)</span
                                    >
                                </template>
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <UserOutlined class="text-pink-400 text-xl" />
                            <span class="text-gray-600"
                                >Madre: <b>{{ nino.madre?.name }}</b></span
                            >
                        </div>
                    </div>
                </div>
                <!-- Secciones adicionales -->
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-pink-700 mb-2">
                        Evaluaciones
                    </h2>
                    <div v-if="nino.evaluaciones && nino.evaluaciones.length">
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
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xl font-bold text-pink-700">
                            Diagnósticos médicos
                        </h2>
                        <AButton type="primary" @click="abrirModalDiagnostico">
                            Agregar diagnóstico
                        </AButton>
                    </div>
                    <div
                        v-if="
                            nino.diagnostico_medico &&
                            nino.diagnostico_medico.length
                        "
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
                                    {{ diag.fecha_fin || "actual" }})</span
                                >
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-gray-400">
                        No hay diagnósticos médicos registrados.
                    </div>
                </div>
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-pink-700 mb-2">Fotos</h2>
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
                                <div class="text-xs text-gray-500 truncate">
                                    {{ foto.descripcion }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-gray-400">
                        No hay fotos registradas.
                    </div>
                </div>
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-pink-700 mb-2">
                        Alertas
                    </h2>
                    <div v-if="nino.alertas && nino.alertas.length">
                        <ul class="divide-y divide-gray-200">
                            <li
                                v-for="alerta in nino.alertas"
                                :key="alerta.id"
                                class="py-2"
                            >
                                <span class="font-semibold">{{
                                    alerta.tipo_alerta
                                }}</span
                                >: {{ alerta.descripcion }}
                                <span class="text-xs text-gray-500">
                                    ({{ alerta.fecha_generada }})</span
                                >
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-gray-400">
                        No hay alertas registradas.
                    </div>
                </div>
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-pink-700 mb-2">
                        Predicciones
                    </h2>
                    <div v-if="nino.predicciones && nino.predicciones.length">
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
                                    (Prob: {{ pred.probabilidad }})</span
                                >
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-gray-400">
                        No hay predicciones registradas.
                    </div>
                </div>
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-pink-700 mb-2">
                        Recomendaciones
                    </h2>
                    <div
                        v-if="
                            nino.recomendaciones && nino.recomendaciones.length
                        "
                    >
                        <ul class="divide-y divide-gray-200">
                            <li
                                v-for="rec in nino.recomendaciones"
                                :key="rec.id"
                                class="py-2"
                            >
                                {{ rec.contenido }}
                                <span class="text-xs text-gray-500">
                                    ({{ rec.fecha_generada }})</span
                                >
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-gray-400">
                        No hay recomendaciones registradas.
                    </div>
                </div>
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-pink-700 mb-2">
                        Observaciones de salud
                    </h2>
                    <div
                        v-if="
                            nino.observaciones_salud &&
                            nino.observaciones_salud.length
                        "
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
                                <span class="text-xs text-gray-500">
                                    ({{ obs.fecha_registro }})</span
                                >
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-gray-400">
                        No hay observaciones de salud registradas.
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
</script>

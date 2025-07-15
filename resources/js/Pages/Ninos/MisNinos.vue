<template>
    <AppLayout title="Mis Bebés">
        <template #header>
            <h1 class="text-xl font-semibold text-pink-800 mb-0">Mis Bebés</h1>
        </template>
        <div class="p-6 md:p-10">
            <!-- ✅ Botones de selección de vista -->
            <div class="mb-6 flex justify-end">
                <div class="inline-flex rounded-md shadow-sm">
                    <button
                        @click="viewMode = 'cards'"
                        :class="{
                            'bg-pink-600 text-white': viewMode === 'cards',
                            'bg-white text-gray-700': viewMode !== 'cards',
                        }"
                        class="px-4 py-2 text-sm font-medium rounded-l-lg border border-gray-300 flex items-center"
                    >
                        <AppstoreOutlined class="inline mr-1 align-middle" />
                        <span class="align-middle">Tarjetas</span>
                    </button>
                    <button
                        @click="viewMode = 'table'"
                        :class="{
                            'bg-pink-600 text-white': viewMode === 'table',
                            'bg-white text-gray-700': viewMode !== 'table',
                        }"
                        class="px-4 py-2 text-sm font-medium rounded-r-lg border border-gray-300 flex items-center"
                    >
                        <TableOutlined class="inline mr-1 align-middle" />
                        <span class="align-middle">Tabla</span>
                    </button>
                </div>
            </div>

            <!-- ✅ Vista tipo Tarjetas -->
            <div
                v-if="viewMode === 'cards'"
                class="grid gap-6"
                style="
                    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
                "
            >
                <div
                    v-for="nino in ninos"
                    :key="nino.id"
                    class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer flex flex-col h-full min-w-[270px]"
                    @click="irADetalleNino(nino.id)"
                >
                    <div class="p-6 flex-1 flex flex-col">
                        <!-- ✅ Encabezado tarjeta -->
                        <div class="mb-4 flex flex-col items-center">
                            <h2
                                class="text-xl font-bold text-gray-800 text-center"
                            >
                                {{ nino.nombre }}
                            </h2>
                            <span
                                class="mt-2 px-3 py-1 bg-pink-100 text-pink-800 rounded-full text-sm font-medium text-center"
                                style="display: inline-block"
                            >
                                {{
                                    nino.etapa_desarrollo?.nombre ||
                                    nino.etapa_desarrollo?.nombre_etapa ||
                                    "Sin etapa"
                                }}
                            </span>
                        </div>

                        <!-- ✅ Datos del niño -->
                        <div class="space-y-3 text-gray-600">
                            <div class="flex items-center">
                                <ClockCircleOutlined
                                    class="h-5 w-5 mr-2 text-pink-500"
                                />
                                <span>
                                    Nació el
                                    {{
                                        formatearFecha(
                                            nino.fecha_nacimiento,
                                            "mediano"
                                        )
                                    }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <ShoppingOutlined
                                    class="h-5 w-5 mr-2 text-pink-500"
                                />
                                <span
                                    >Peso:
                                    {{ nino.peso_nacimiento || nino.peso }}
                                    kg</span
                                >
                            </div>
                            <div class="flex items-center">
                                <CalendarOutlined
                                    class="h-5 w-5 mr-2 text-pink-500"
                                />
                                <span
                                    >Talla:
                                    {{ nino.talla_nacimiento || nino.talla }}
                                    cm</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- ✅ Footer fijo abajo -->
                    <div
                        class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 mt-auto min-h-[64px] items-center"
                    >
                        <AButton
                            type="default"
                            @click.stop="
                                $inertia.visit(
                                    route('etapas.por-nino', nino.id)
                                )
                            "
                        >
                            Ver etapas
                        </AButton>
                        <AButton
                            type="primary"
                            @click.stop="irADetalleNino(nino.id)"
                        >
                            Monitorear
                        </AButton>
                    </div>
                </div>
            </div>

            <!-- ✅ Vista tipo Tabla -->
            <div v-else class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Nombre
                            </th>
                            <th class="px-6 py-3">Nacimiento</th>
                            <th class="px-6 py-3">Peso (kg)</th>
                            <th class="px-6 py-3">Talla (cm)</th>
                            <th class="px-6 py-3">Etapa</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="nino in ninos"
                            :key="nino.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ nino.nombre }}
                            </td>
                            <td class="px-6 py-4">
                                {{
                                    formatearFecha(
                                        nino.fecha_nacimiento,
                                        "mediano"
                                    )
                                }}
                            </td>
                            <td class="px-6 py-4">
                                {{ nino.peso_nacimiento || nino.peso }}
                            </td>
                            <td class="px-6 py-4">
                                {{ nino.talla_nacimiento || nino.talla }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 bg-pink-100 text-pink-800 rounded-full text-xs font-medium"
                                >
                                    {{ nino.etapa_desarrollo.nombre }}
                                </span>
                            </td>

                            <!-- ✅ Acciones en tabla (incluye Ver Etapas con ícono) -->
                            <td class="px-6 py-4 text-center space-x-3">
                                <Link
                                    :href="`/ninos/${nino.id}`"
                                    class="text-blue-600 hover:text-blue-900"
                                    title="Ver detalles"
                                >
                                    <EyeOutlined
                                        class="h-5 w-5 inline align-middle"
                                    />
                                </Link>
                                <Link
                                    :href="`/ninos/${nino.id}/edit`"
                                    class="text-green-600 hover:text-green-900"
                                    title="Editar"
                                >
                                    <EditOutlined
                                        class="h-5 w-5 inline align-middle"
                                    />
                                </Link>

                                <!-- ✅ Nuevo botón: Ver Etapas -->
                                <Link
                                    :href="route('etapas.por-nino', nino.id)"
                                    class="text-purple-600 hover:text-purple-900"
                                    title="Ver etapas"
                                >
                                    <AppstoreOutlined
                                        class="h-5 w-5 inline align-middle"
                                    />
                                </Link>

                                <button
                                    @click="destroy(nino.id)"
                                    class="text-red-600 hover:text-red-900"
                                    title="Eliminar"
                                >
                                    <DeleteOutlined
                                        class="h-5 w-5 inline align-middle"
                                    />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { formatearFecha } from "@/utils/date.js";

import {
    PlusOutlined,
    AppstoreOutlined,
    TableOutlined,
    ClockCircleOutlined,
    ShoppingOutlined,
    CalendarOutlined,
    UserOutlined,
    EyeOutlined,
    EditOutlined,
    DeleteOutlined,
} from "@ant-design/icons-vue";
import { Button as AButton } from "ant-design-vue";
import ModalDiagnosticoMedico from "./componentes/ModalDiagnosticoMedico.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    ninos: Array,
});

// Log de props recibidas
console.log("Props recibidas en MisNinos.vue:", props.ninos);

const viewMode = ref("cards");

function destroy(id) {
    if (confirm("¿Estás seguro de eliminar este niño?")) {
        router.delete(route("ninos.destroy", id));
    }
}

function irADetalleNino(id) {
    router.visit(route("ninos.show", id));
}
</script>

<style scoped>
.bg-gray-50.mt-auto {
    min-height: 64px;
    display: flex;
    align-items: center;
}
</style>

<template>
    <AppLayout title="Mis Bebés">
        <template #header>
            <h1 class="text-3xl font-bold text-pink-800 mb-0">Mis Bebés</h1>
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
                        class="px-4 py-2 text-sm font-medium rounded-l-lg border border-gray-300"
                    >
                        <AppstoreOutlined class="inline mr-1" />
                        Tarjetas
                    </button>
                    <button
                        @click="viewMode = 'table'"
                        :class="{
                            'bg-pink-600 text-white': viewMode === 'table',
                            'bg-white text-gray-700': viewMode !== 'table',
                        }"
                        class="px-4 py-2 text-sm font-medium rounded-r-lg border border-gray-300"
                    >
                        <TableOutlined class="inline mr-1" />
                        Tabla
                    </button>
                </div>
            </div>

            <!-- ✅ Vista tipo Tarjetas -->
            <div
                v-if="viewMode === 'cards'"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
            >
                <div
                    v-for="nino in ninos.data"
                    :key="nino.id"
                    class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer"
                    @click="irADetalleNino(nino.id)"
                >
                    <div class="p-6">
                        <!-- ✅ Encabezado tarjeta -->
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-xl font-bold text-gray-800">
                                {{ nino.nombre }}
                            </h2>
                            <span
                                class="px-3 py-1 bg-pink-100 text-pink-800 rounded-full text-sm font-medium"
                            >
                                {{ nino.etapa_desarrollo.nombre }}
                            </span>
                        </div>

                        <!-- ✅ Datos del niño -->
                        <div class="space-y-3 text-gray-600">
                            <div class="flex items-center">
                                <ClockCircleOutlined
                                    class="h-5 w-5 mr-2 text-pink-500"
                                />
                                <span
                                    >Nació en la semana
                                    {{ nino.fecha_nacimiento }}</span
                                >
                            </div>
                            <div class="flex items-center">
                                <ShoppingOutlined
                                    class="h-5 w-5 mr-2 text-pink-500"
                                />
                                <span>Peso: {{ nino.peso_nacimiento }} kg</span>
                            </div>
                            <div class="flex items-center">
                                <CalendarOutlined
                                    class="h-5 w-5 mr-2 text-pink-500"
                                />
                                <span
                                    >Talla: {{ nino.talla_nacimiento }} cm</span
                                >
                            </div>
                            <div class="flex items-center">
                                <UserOutlined
                                    class="h-5 w-5 mr-2 text-pink-500"
                                />
                                <span>Madre: {{ nino.madre.name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ Acciones en tarjeta: solo Monitorear y Ver etapas -->
                    <div
                        class="bg-gray-50 px-6 py-4 flex justify-end space-x-3"
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
                            <th class="px-6 py-3">Semana Nac.</th>
                            <th class="px-6 py-3">Peso (kg)</th>
                            <th class="px-6 py-3">Talla (cm)</th>
                            <th class="px-6 py-3">Madre</th>
                            <th class="px-6 py-3">Etapa</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="nino in ninos.data"
                            :key="nino.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ nino.nombre }}
                            </td>
                            <td class="px-6 py-4">
                                {{ nino.fecha_nacimiento }}
                            </td>
                            <td class="px-6 py-4">
                                {{ nino.peso_nacimiento }}
                            </td>
                            <td class="px-6 py-4">
                                {{ nino.talla_nacimiento }}
                            </td>
                            <td class="px-6 py-4">{{ nino.madre.name }}</td>
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
                                    <EyeOutlined class="h-5 w-5 inline" />
                                </Link>
                                <Link
                                    :href="`/ninos/${nino.id}/edit`"
                                    class="text-green-600 hover:text-green-900"
                                    title="Editar"
                                >
                                    <EditOutlined class="h-5 w-5 inline" />
                                </Link>

                                <!-- ✅ Nuevo botón: Ver Etapas -->
                                <Link
                                    :href="route('etapas.por-nino', nino.id)"
                                    class="text-purple-600 hover:text-purple-900"
                                    title="Ver etapas"
                                >
                                    <AppstoreOutlined class="h-5 w-5 inline" />
                                </Link>

                                <button
                                    @click="destroy(nino.id)"
                                    class="text-red-600 hover:text-red-900"
                                    title="Eliminar"
                                >
                                    <DeleteOutlined class="h-5 w-5 inline" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- ✅ Paginación -->
            <div
                v-if="ninos.meta && ninos.meta.last_page > 1"
                class="mt-6 flex justify-center"
            >
                <nav
                    class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                >
                    <Link
                        v-for="(link, index) in ninos.meta.links"
                        :key="index"
                        :href="link.url || '#'"
                        :class="{
                            'bg-pink-600 text-white': link.active,
                            'bg-white text-gray-500 hover:bg-gray-50':
                                !link.active,
                            'rounded-l-md': index === 0,
                            'rounded-r-md':
                                index === ninos.meta.links.length - 1,
                            'pointer-events-none opacity-50': !link.url,
                        }"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium"
                        v-html="link.label"
                    />
                </nav>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
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
    ninos: Object,
});

const viewMode = ref("cards"); // Estado para cambiar entre vista de tarjetas y tabla

// ✅ Función para eliminar un niño
function destroy(id) {
    if (confirm("¿Estás seguro de eliminar este niño?")) {
        router.delete(route("ninos.destroy", id));
    }
}

function irADetalleNino(id) {
    router.visit(route("ninos.show", id));
}
</script>

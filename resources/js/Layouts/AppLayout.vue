<script setup>
import { ref, nextTick, onMounted, onUnmounted } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import {
    AppstoreOutlined,
    SmileOutlined,
    MenuUnfoldOutlined,
    MenuFoldOutlined,
} from "@ant-design/icons-vue";

defineProps({
    title: {
        type: String,
        default: "",
    },
});

const page = usePage();
const user = page.props.auth.user;

const logout = () => {
    router.post(route("logout"));
};

const sidebarFolded = ref(false);
const sidebarVisibleMobile = ref(false);
const isMobile = ref(false);

function checkMobile() {
    isMobile.value = window.innerWidth < 768;
    if (isMobile.value) {
        sidebarVisibleMobile.value = false;
    }
}

function toggleSidebar() {
    if (isMobile.value) {
        sidebarVisibleMobile.value = !sidebarVisibleMobile.value;
    } else {
        sidebarFolded.value = !sidebarFolded.value;
        nextTick(() => {});
    }
}

onMounted(() => {
    checkMobile();
    window.addEventListener("resize", checkMobile);
});
onUnmounted(() => {
    window.removeEventListener("resize", checkMobile);
});
</script>

<template>
    <div>
        <Head :title="title" />
        <div class="min-h-screen bg-[#FDF2F8]">
            <!-- Overlay para mobile -->
            <div
                v-if="isMobile && sidebarVisibleMobile"
                class="fixed inset-0 bg-black bg-opacity-30 z-40"
                @click="sidebarVisibleMobile = false"
            ></div>
            <!-- Sidebar -->
            <aside
                :class="[
                    'fixed top-0 left-0 h-screen bg-white border-r border-gray-200 flex flex-col py-6 z-50 overflow-y-auto transition-all duration-300',
                    isMobile
                        ? [
                              sidebarVisibleMobile
                                  ? 'w-64 px-4 translate-x-0'
                                  : '-translate-x-full w-64 px-4',
                              'transform-gpu',
                          ]
                        : [sidebarFolded ? 'w-20 px-2' : 'w-64 px-4'],
                ]"
                style="will-change: transform"
            >
                <!-- Logo, nombre y botón de plegar/desplegar/cerrar -->
                <div
                    v-if="isMobile"
                    class="flex items-center justify-between mb-8 w-full"
                >
                    <Link
                        :href="route('dashboard')"
                        class="flex items-center gap-2"
                    >
                        <ApplicationMark class="block h-10 w-auto" />
                        <span
                            class="font-bold text-pink-700 text-lg tracking-wide"
                            >MAIA</span
                        >
                    </Link>
                    <button
                        class="flex items-center justify-center w-8 h-8 rounded hover:bg-pink-100 transition"
                        @click="sidebarVisibleMobile = false"
                        aria-label="Cerrar menú"
                    >
                        <svg
                            class="text-xl text-pink-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
                <div
                    v-else-if="!sidebarFolded"
                    class="flex items-center justify-between mb-8 w-full"
                >
                    <Link
                        :href="route('dashboard')"
                        class="flex items-center gap-2"
                    >
                        <ApplicationMark class="block h-10 w-auto" />
                        <span
                            class="font-bold text-pink-700 text-lg tracking-wide"
                            >MAIA</span
                        >
                    </Link>
                    <button
                        class="flex items-center justify-center w-8 h-8 rounded hover:bg-pink-100 transition"
                        @click="toggleSidebar"
                        aria-label="Plegar menú"
                    >
                        <MenuFoldOutlined class="text-xl text-pink-600" />
                    </button>
                </div>
                <div v-else class="flex flex-col items-center mb-8 w-full">
                    <Link
                        :href="route('dashboard')"
                        class="flex items-center justify-center"
                    >
                        <ApplicationMark class="block h-10 w-auto" />
                    </Link>
                    <button
                        class="flex items-center justify-center w-8 h-8 rounded hover:bg-pink-100 transition mt-3"
                        @click="toggleSidebar"
                        aria-label="Expandir menú"
                    >
                        <MenuUnfoldOutlined class="text-xl text-pink-600" />
                    </button>
                </div>
                <nav class="flex-1 space-y-2">
                    <Link
                        :href="route('dashboard')"
                        class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-pink-100 font-medium flex items-center gap-3"
                        :class="{
                            'bg-pink-600 text-white':
                                route().current('dashboard'),
                            'justify-center': sidebarFolded,
                        }"
                    >
                        <AppstoreOutlined class="text-xl" />
                        <span v-if="!sidebarFolded">Dashboard</span>
                    </Link>
                    <Link
                        :href="route('ninos.index')"
                        class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-pink-100 font-medium flex items-center gap-3"
                        :class="{
                            'bg-pink-600 text-white':
                                route().current('ninos.index'),
                            'justify-center': sidebarFolded,
                        }"
                    >
                        <SmileOutlined class="text-xl" />
                        <span v-if="!sidebarFolded">Mis Bebés</span>
                    </Link>
                </nav>
                <div class="mt-auto relative z-50">
                    <Dropdown
                        align="top"
                        width="48"
                        :content-classes="['py-1', 'bg-white', 'mb-2']"
                        :dropdown-style="
                            sidebarFolded
                                ? {
                                      position: 'fixed',
                                      left: '80px',
                                      bottom: '32px',
                                      zIndex: 9999,
                                      minWidth: '180px',
                                  }
                                : {}
                        "
                    >
                        <template #trigger>
                            <button
                                class="flex items-center w-full px-2 py-2 rounded-lg hover:bg-pink-50 transition"
                                :class="{ 'justify-center': sidebarFolded }"
                            >
                                <img
                                    v-if="user?.profile_photo_url"
                                    :src="user.profile_photo_url"
                                    :alt="user.name"
                                    :class="[
                                        sidebarFolded
                                            ? 'mx-auto block'
                                            : 'mr-3',
                                        'size-10 rounded-full object-cover',
                                    ]"
                                />
                                <span
                                    v-if="!sidebarFolded"
                                    class="font-medium text-gray-700"
                                    >{{ user?.name }}</span
                                >
                                <svg
                                    class="ml-auto h-4 w-4 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.show')">
                                Perfil
                            </DropdownLink>
                            <form @submit.prevent="logout">
                                <DropdownLink as="button">
                                    Cerrar sesión
                                </DropdownLink>
                            </form>
                        </template>
                    </Dropdown>
                </div>
            </aside>
            <!-- Contenido principal -->
            <div
                :class="[
                    isMobile ? 'ml-0' : sidebarFolded ? 'ml-20' : 'ml-64',
                    'flex-1 flex flex-col min-h-screen transition-all duration-300',
                ]"
            >
                <header
                    v-if="$slots.header"
                    class="bg-white shadow flex items-center justify-between"
                >
                    <div
                        class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 w-full"
                    >
                        <slot name="header" />
                    </div>
                    <!-- Botón menú mobile -->
                    <button
                        v-if="isMobile"
                        class="mr-4 flex items-center justify-center w-10 h-10 rounded hover:bg-pink-100 transition lg:hidden"
                        @click="sidebarVisibleMobile = true"
                        aria-label="Abrir menú"
                    >
                        <MenuUnfoldOutlined class="text-2xl text-pink-600" />
                    </button>
                </header>
                <main class="flex-1">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

<style scoped></style>

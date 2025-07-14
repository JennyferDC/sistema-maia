<script setup>
import { ref } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

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
</script>

<template>
    <div>
        <Head :title="title" />
        <div class="min-h-screen bg-[#FDF2F8]">
            <!-- Sidebar -->
            <aside
                class="fixed top-0 left-0 w-64 h-screen bg-white border-r border-gray-200 flex flex-col py-6 px-4 z-40 overflow-y-auto"
            >
                <div class="flex items-center mb-8">
                    <Link :href="route('dashboard')">
                        <ApplicationMark class="block h-10 w-auto" />
                    </Link>
                </div>
                <nav class="flex-1 space-y-2">
                    <Link
                        :href="route('dashboard')"
                        class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-pink-100 font-medium"
                        :class="{
                            'bg-pink-600 text-white':
                                route().current('dashboard'),
                        }"
                    >
                        Dashboard
                    </Link>
                    <Link
                        :href="route('ninos.index')"
                        class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-pink-100 font-medium"
                        :class="{
                            'bg-pink-600 text-white':
                                route().current('ninos.index'),
                        }"
                    >
                        Mis Bebés
                    </Link>
                </nav>
                <div class="mt-auto relative z-50">
                    <Dropdown
                        align="top"
                        width="48"
                        :content-classes="['py-1', 'bg-white', 'mb-2']"
                    >
                        <template #trigger>
                            <button
                                class="flex items-center w-full px-2 py-2 rounded-lg hover:bg-pink-50 transition"
                            >
                                <img
                                    v-if="user?.profile_photo_url"
                                    :src="user.profile_photo_url"
                                    :alt="user.name"
                                    class="size-10 rounded-full object-cover mr-3"
                                />
                                <span class="font-medium text-gray-700">{{
                                    user?.name
                                }}</span>
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
            <div class="ml-64 flex-1 flex flex-col min-h-screen">
                <header v-if="$slots.header" class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>
                <main class="flex-1">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

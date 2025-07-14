<script setup>
// 1. Imports - Organized by purpose
// Core imports
import { Head, Link, useForm } from "@inertiajs/vue3";

// Component imports
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

// 2. Props
defineProps({
    canResetPassword: Boolean,
    status: String,
});

// 3. Form state & helpers
const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Log in" />

    <!--Contenedor principal -->
    <div class="min-h-screen flex flex-col md:flex-row bg-white">
        <!-- Sección izquierda con imagen -->
        <div
            class="relative w-0 md:w-1/2 hidden md:flex items-center justify-center bg-orange-50"
        >
            <img
                src="/storage/Imagenes/Registro.png"
                alt="Madre sosteniendo bebé"
                class="max-w-xs md:max-w-md h-auto z-10 relative"
                loading="lazy"
            />
        </div>

        <!-- Zigzag decorativo al centro -->
        <div
            class="absolute top-0 left-1/2 transform -translate-x-1/2 h-full w-16 overflow-hidden z-20 hidden md:block"
        >
            <svg
                class="diagonal-separator"
                viewBox="0 0 100 1000"
                preserveAspectRatio="none"
            >
                <path d="M0,0 L100,0 L0,500 L100,500 Z" fill="#FCE7F3" />
                <path d="M0,500 L100,500 L0,1000 L100,1000 Z" fill="#FCE7F3" />
            </svg>
        </div>

        <!-- Sección derecha con formulario -->
        <div
            class="w-full md:w-1/2 flex items-center justify-center p-6 bg-pink-100 relative z-10"
        >
            <div
                class="relative z-10 w-full max-w-md bg-pink-200 rounded-lg p-6 shadow-lg"
            >
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <ApplicationLogo />
                </div>

                <!-- Encabezado -->
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">Login</h2>
                    <p class="mt-1 text-sm text-gray-700">
                        Sign in to your account
                    </p>
                </div>

                <!-- Estado del login -->
                <div
                    v-if="status"
                    class="mb-4 text-sm font-medium text-green-600"
                >
                    {{ status }}
                </div>

                <!-- Formulario de login -->
                <form @submit.prevent="submit">
                    <!-- Email -->
                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Contraseña -->
                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            required
                            autocomplete="current-password"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <!-- Recordarme -->
                    <div class="mt-4 flex items-center">
                        <Checkbox
                            v-model:checked="form.remember"
                            name="remember"
                        />
                        <span class="ml-2 text-sm text-gray-600"
                            >Remember me</span
                        >
                    </div>

                    <!-- Botón Login -->
                    <div class="mt-6">
                        <PrimaryButton
                            class="block w-full py-3 text-center rounded-lg font-semibold text-lg bg-black text-white hover:bg-gray-800 transition-colors duration-300 shadow-md"
                            :disabled="form.processing"
                        >
                            Login
                        </PrimaryButton>
                    </div>

                    <!-- Olvidaste tu contraseña -->
                    <div class="mt-4 text-center">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-gray-600 text-sm underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            Forgot your password?
                        </Link>
                    </div>

                    <!-- Crear nueva cuenta -->
                    <div class="mt-6">
                        <Link
                            href="/register"
                            class="block w-full py-3 text-center rounded-lg font-semibold text-lg bg-black text-white hover:bg-gray-800 transition-colors duration-300 shadow-md"
                        >
                            Create New Account
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const form = useForm({
    name: "",
    apellido: "",
    telefono: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Register" />
    <div class="min-h-screen flex bg-white">
        <!-- Sección izquierda (imagen con fondo naranja claro) -->
        <div
            class="relative w-0 md:w-1/2 hidden md:flex items-center justify-center bg-orange-50"
        >
            <img
                src="/storage/images/mama-bebe.png"
                alt="Madre sosteniendo bebé"
                class="max-w-xs md:max-w-md h-auto z-10 relative rounded-3xl shadow-lg border-4 border-pink-100"
                loading="lazy"
            />
        </div>
        <!-- Zigzag vertical en el centro: rosa arriba, rojo abajo (mismo color actual #FCE7F3) -->
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
        <!-- Sección derecha (formulario con fondo rosado claro) -->
        <div
            class="w-full md:w-1/2 flex items-center justify-center p-6 bg-pink-100 relative z-10"
        >
            <div
                class="w-full max-w-xl bg-white rounded-2xl p-10 shadow-2xl border border-pink-200"
            >
                <!-- 🖼 Logo en la parte superior -->
                <div class="flex justify-center mb-6">
                    <ApplicationLogo />
                </div>
                <!-- Título y subtítulo -->
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-pink-700">Registro</h2>
                    <p class="mt-1 text-sm text-gray-700">
                        Completa tus datos para crear tu cuenta
                    </p>
                </div>
                <!-- Formulario -->
                <form @submit.prevent="submit">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Columna izquierda -->
                        <div class="w-full md:w-1/2 space-y-4">
                            <!-- Nombre -->
                            <div>
                                <InputLabel for="name" value="Nombre" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-pink-200 focus:border-pink-400"
                                    required
                                    autofocus
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>
                            <!-- Apellido -->
                            <div>
                                <InputLabel for="apellido" value="Apellido" />
                                <TextInput
                                    id="apellido"
                                    v-model="form.apellido"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-pink-200 focus:border-pink-400"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.apellido"
                                />
                            </div>
                            <!-- Teléfono -->
                            <div>
                                <InputLabel for="telefono" value="Teléfono" />
                                <TextInput
                                    id="telefono"
                                    v-model="form.telefono"
                                    type="tel"
                                    class="mt-1 block w-full rounded-lg border-pink-200 focus:border-pink-400"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.telefono"
                                />
                            </div>
                        </div>
                        <!-- Columna derecha -->
                        <div class="w-full md:w-1/2 space-y-4">
                            <!-- Email -->
                            <div>
                                <InputLabel for="email" value="Email" />
                                <TextInput
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full rounded-lg border-pink-200 focus:border-pink-400"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.email"
                                />
                            </div>
                            <!-- Contraseña -->
                            <div>
                                <InputLabel for="password" value="Contraseña" />
                                <TextInput
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="mt-1 block w-full rounded-lg border-pink-200 focus:border-pink-400"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.password"
                                />
                            </div>
                            <!-- Confirmar Contraseña -->
                            <div>
                                <InputLabel
                                    for="password_confirmation"
                                    value="Confirmar Contraseña"
                                />
                                <TextInput
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full rounded-lg border-pink-200 focus:border-pink-400"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.password_confirmation"
                                />
                            </div>
                        </div>
                    </div>
                    <!-- Términos y condiciones -->
                    <div
                        v-if="
                            $page.props.jetstream
                                .hasTermsAndPrivacyPolicyFeature
                        "
                        class="mt-4"
                    >
                        <!-- Aquí puedes colocar un checkbox con enlace a términos -->
                    </div>
                    <!-- Botón de registro -->
                    <div class="mt-6 text-center">
                        <button
                            type="submit"
                            class="px-6 py-2 bg-pink-600 text-white rounded-lg shadow-lg transition-colors duration-300 hover:bg-pink-200 hover:text-black font-semibold text-lg"
                        >
                            Registrarme
                        </button>
                    </div>
                </form>
                <!-- Botón para ir al login -->
                <div class="mt-6 text-center">
                    <Link
                        href="/login"
                        class="block w-full py-3 text-center rounded-lg font-semibold text-lg bg-pink-100 text-pink-700 hover:bg-pink-200 transition-colors duration-300 shadow-md border border-pink-200"
                    >
                        ¿Ya tienes cuenta? Inicia sesión
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

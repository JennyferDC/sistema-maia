<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    apellido: '',
    telefono: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <!-- Contenedor principal que divide la pantalla en dos mitades -->
    <div class="min-h-screen flex bg-white">
        
        <!-- Sección izquierda (imagen con fondo naranja claro) -->
        <div class="relative w-0 md:w-1/2 hidden md:flex items-center justify-center bg-orange-50">
            <img
                src="/storage/Imagenes/Registro.png"
                alt="Madre sosteniendo bebé"
                class="max-w-xs md:max-w-md h-auto z-10 relative"
                loading="lazy"
            />
        </div>

        <!-- Zigzag vertical en el centro: rosa arriba, rojo abajo (mismo color actual #FCE7F3) -->
        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 h-full w-16 overflow-hidden z-20 hidden md:block">
            <svg class="diagonal-separator" viewBox="0 0 100 1000" preserveAspectRatio="none">
                <!-- Parte superior del zigzag (rosa claro) -->
                <path d="M0,0 L100,0 L0,500 L100,500 Z" fill="#FCE7F3" />
                <!-- Parte inferior del zigzag (también rosa claro por ahora) -->
                <path d="M0,500 L100,500 L0,1000 L100,1000 Z" fill="#FCE7F3" />
            </svg>
        </div>

        <!-- Sección derecha (formulario con fondo rosado claro) -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-6 bg-pink-100 relative z-10">
            
            <!-- Contenedor del formulario) -->
            <div class="w-full max-w-xl bg-white rounded-xl p-8 shadow-md">
                
                <!-- 🖼 Logo en la parte superior -->
                <div class="flex justify-center mb-6">
                    <img src="/storage/Imagenes/Logo1.png" alt="Logo" class="h-20 w-auto" />
                </div>

                <!-- Título y subtítulo -->
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">Registro</h2>
                    <p class="mt-1 text-sm text-gray-700">Completa tus datos para crear tu cuenta</p>
                </div>

                <!-- Aquí irá el formulario de registro -->

                 <!-- Formulario -->
                 <form @submit.prevent="submit">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Columna izquierda -->
                        <div class="w-full md:w-1/2 space-y-4">

                            <!-- Nombre -->
                            <div>
                                <InputLabel for="name" value="Nombre" />
                                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <!-- Apellido -->
                            <div>
                                <InputLabel for="apellido" value="Apellido" />
                                <TextInput id="apellido" v-model="form.apellido" type="text" class="mt-1 block w-full" required />
                                <InputError class="mt-2" :message="form.errors.apellido" />
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <InputLabel for="telefono" value="Teléfono" />
                                <TextInput id="telefono" v-model="form.telefono" type="tel" class="mt-1 block w-full" required />
                                <InputError class="mt-2" :message="form.errors.telefono" />
                            </div>
                        </div>

                        <!-- Columna derecha -->
                        <div class="w-full md:w-1/2 space-y-4">

                            <!-- Email -->
                            <div>
                                <InputLabel for="email" value="Email" />
                                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <!-- Contraseña -->
                            <div>
                                <InputLabel for="password" value="Contraseña" />
                                <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div>
                                <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                                <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" required />
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>
                        </div>
                    </div>

                    <!-- Términos y condiciones -->
                    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                        <!-- Aquí puedes colocar un checkbox con enlace a términos -->
                    </div>

                    <!-- Botón de registro -->
                    <div class="mt-6 text-center">

                        <button type="submit" 
                        class="px-6 py-2 bg-black text-white rounded-lg shadow-lg transition-colors duration-300 hover:bg-pink-200 hover:text-black">
                            Registrarme
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


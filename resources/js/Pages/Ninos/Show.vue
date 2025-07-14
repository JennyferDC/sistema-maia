<template>
    <div class="min-h-screen bg-white p-6">
      <h1 class="text-3xl font-bold text-pink-600 mb-6">Detalles de {{ nino.nombre }}</h1>
  
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
          <p><strong>Fecha de nacimiento:</strong> {{ nino.fecha_nacimiento }}</p>
          <p><strong>Peso:</strong> {{ nino.peso }} kg</p>
          <p><strong>Talla:</strong> {{ nino.talla }} cm</p>
          <p><strong>Sexo:</strong> {{ nino.sexo }}</p>
          <p><strong>Etapa de desarrollo:</strong> {{ nino.etapa_desarrollo.nombre }}</p>
        </div>
  
        <div class="space-y-2">
          <p><strong>Nombre de la madre:</strong> {{ nino.madre.name }}</p>
          <p><strong>Email:</strong> {{ nino.madre.email }}</p>
        </div>
      </div>
  
      <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Recomendaciones</h2>
        <ul class="list-disc ml-5">
          <li v-for="reco in nino.recomendaciones" :key="reco.id">
            {{ reco.contenido }}
          </li>
        </ul>
      </div>
  
      <div class="mt-6">
        <Link href="/ninos" class="text-pink-600 hover:underline">← Volver a la lista</Link>
      </div>
    </div>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  
  const props = defineProps({
    nino: Object
  });
  </script>
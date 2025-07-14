<template>
  <div class="min-h-screen bg-pink-50 p-6">

    <!-- 🎀 ENCABEZADO CON NOMBRE DEL NIÑO -->
    <header class="relative overflow-hidden rounded-3xl shadow-lg mb-10">
      <div class="bg-gradient-to-r from-pink-300 via-fuchsia-300 to-purple-300 pb-16 pt-10 px-6 sm:px-10">
        <h1 class="text-4xl sm:text-5xl font-black tracking-tight text-white flex items-center gap-3">
          <span class="inline-block p-2 bg-white/20 rounded-full backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 14.25c2.485 0 4.5-2.015 4.5-4.5S14.485 5.25 12 5.25 7.5 7.265 7.5 9.75s2.015 4.5 4.5 4.5z" />
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 14.25a3.75 3.75 0 013.75 3.75v.75M8.25 14.25a3.75 3.75 0 00-3.75 3.75v.75" />
            </svg>
          </span>
          Etapas de {{ nino.nombre }}
        </h1>
      </div>

      <!-- Curva decorativa inferior -->
      <svg class="absolute bottom-0 left-0 w-full h-12 text-white" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path
          fill="currentColor"
          d="M0,224L60,229.3C120,235,240,245,360,229.3C480,213,600,171,720,165.3C840,160,960,192,1080,192C1200,192,1320,160,1380,144L1440,128V320H0Z"
        />
      </svg>
    </header>

    <!-- 🔙 BOTÓN PARA VOLVER AL PERFIL DEL NIÑO -->
    <div class="mb-8">
      <Link
        :href="route('ninos.show', nino.id)"
        class="inline-flex items-center gap-2 text-pink-600 hover:text-pink-800 font-medium"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Volver al perfil de {{ nino.nombre }}
      </Link>
    </div>

    <!-- 🔁 ETAPAS DEL DESARROLLO -->
    <div v-for="etapa in etapas" :key="etapa.id" class="mb-12">
      
      <!-- 📌 Nombre y rango de etapa -->
      <div class="mb-2">
        <span class="text-lg font-semibold">{{ getNombre(etapa.nombre_etapa) }}</span>
        <span class="text-gray-500 ml-2">{{ getRango(etapa.nombre_etapa) }}</span>
      </div>

      <!-- 🧩 Lista de hitos -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div
          v-for="hito in etapa.hitos"
          :key="hito.id"
          class="border border-gray-300 rounded-lg p-3 shadow-sm relative"
        >
          <!-- Checkbox personalizado -->
          <label class="flex items-center cursor-pointer">
            <input
              type="checkbox"
              v-model="hito.marcado"
              @change="togglePanel(hito)"
              class="hidden"
            />
            <span
              class="w-5 h-5 rounded-full border-2 border-gray-400 flex items-center justify-center mr-2"
              :class="{ 'bg-green-500 border-green-500': hito.marcado }"
            >
              <svg v-if="hito.marcado" class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
            </span>
            <span class="text-base">{{ hito.nombre_hito }}</span>
          </label>

          <!-- Panel flotante con opciones -->
          <div
            v-if="hito.marcado && hito.mostrarPanel"
            class="bg-gray-50 border mt-2 rounded-md p-3 text-sm"
          >
            <p class="mb-2 font-medium">¿Se cumplió en esta etapa?</p>

            <label class="flex items-center mb-1">
              <input
                type="radio"
                value="en_etapa"
                v-model="hito.origen"
                class="mr-2"
              />
              Sí, en esta etapa
            </label>
            <label class="flex items-center">
              <input
                type="radio"
                value="otra_etapa"
                v-model="hito.origen"
                class="mr-2"
              />
              Lo logró en otra etapa
            </label>
          </div>
        </div>

        <!-- Mensaje si no hay hitos -->
        <span v-if="!etapa.hitos.length" class="text-gray-400 col-span-full">
          Sin hitos registrados
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
// 📦 Props que recibe el componente
const props = defineProps({
  etapas: {
    type: Array,
    required: true
  },
  nino: {
    type: Object,
    required: true
  }
});

// 🧠 Inicializa campos de visualización para cada hito
props.etapas.forEach(etapa => {
  etapa.hitos.forEach(hito => {
    if (hito.marcado === undefined) hito.marcado = false;
    if (hito.mostrarPanel === undefined) hito.mostrarPanel = false;
    if (hito.origen === undefined) hito.origen = 'en_etapa';
  });
});

// ✅ Mostrar u ocultar panel flotante
const togglePanel = (hito) => {
  hito.mostrarPanel = hito.marcado;
};

// 🧠 Extraer solo nombre sin paréntesis
const getNombre = (nombre_etapa) => {
  const idx = nombre_etapa.indexOf('(');
  return idx !== -1 ? nombre_etapa.slice(0, idx).trim() : nombre_etapa;
};

// 🧠 Extraer texto dentro del paréntesis
const getRango = (nombre_etapa) => {
  const match = nombre_etapa.match(/\(([^)]+)\)/);
  return match ? match[1] : '';
};
</script>

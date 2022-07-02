<script setup>
import { PlusIcon } from '@heroicons/vue/outline'

defineProps({
  label: String,
  url: String,
})
</script>

<template>
  <a
    class="bg-blue-500 text-white text-sm font-semibold py-3 px-3 md:py-2 md:px-4 rounded-full md:rounded flex items-center"
    :href="url">
    <PlusIcon class="w-4 h-4 inline-block md:hidden" />
    <span class="hidden md:inline-block">{{ label }}</span>
  </a>
</template>

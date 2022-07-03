<script setup>
import JetLabel from '@/Jetstream/Label.vue'

const props = defineProps({
  label: String,
  error: String
})
</script>

<template>
  <div>
    <JetLabel :value="props.label" />
    <div class="mt-1">
      <slot></slot>
    </div>
    <p v-show="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
  </div>
</template>

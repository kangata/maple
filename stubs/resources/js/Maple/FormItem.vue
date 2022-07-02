<script setup>
import JetLabel from '@/Jetstream/Label.vue'

const props = defineProps({
  label: String
})
</script>

<template>
  <div>
    <JetLabel :value="props.label" />
    <div class="mt-1">
      <slot></slot>
    </div>
  </div>
</template>

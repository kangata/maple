<script setup>
const props = defineProps({
  label: String
})
</script>

<template>
  <div class="mb-2">
    <div class="text-sm text-gray-500 font-semibold">{{ props.label }}</div>
    <div>
      <slot></slot>
    </div>
  </div>
</template>

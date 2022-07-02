<script setup>
import { ref } from 'vue'
import { ElInput } from 'element-plus'
import { AdjustmentsIcon, SearchIcon } from '@heroicons/vue/outline'

defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: 'Search'
  },
  advancedFilter: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits([
  'input',
  'update:modelValue',
  'toggle-filter'
])

const showAdvancedFilter = ref(false)

const debounce = (callback, wait) => {
  let timeoutId = null;
  return (...args) => {
    window.clearTimeout(timeoutId);
    timeoutId = window.setTimeout(() => {
      callback.apply(null, args);
    }, wait);
  };
}

const handleInput = debounce((val) => {
  emit('update:modelValue', val)
  emit('input', val)
}, 500)

const toggleAdvacedFilter = () => {
  showAdvancedFilter.value = !showAdvancedFilter.value

  emit('toggle-filter', showAdvancedFilter.value)
}
</script>

<template>
  <div class="flex items-center">
    <div class="flex-1 bg-white py-1 px-3 rounded-l-full" :class="{ 'rounded-r-full': !advancedFilter }">
      <ElInput v-model="modelValue" class="search" :prefix-icon="SearchIcon" :placeholder="placeholder" clearable @input="handleInput" />
    </div>
    <div v-if="advancedFilter" class="py-2.5 pl-3 pr-4 rounded-r-full flex flex-col justify-center bg-gray-200" :class="{ 'bg-blue-500': showAdvancedFilter }">
      <button type="button" @click="toggleAdvacedFilter">
        <AdjustmentsIcon class="h-5 w-5 stroke-gray-400" :class="{ 'stroke-white': showAdvancedFilter }" />
      </button>
    </div>
  </div>
</template>

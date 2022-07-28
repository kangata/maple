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

const handleInput = (val) => {
  emit('update:modelValue', val)
  emit('input', val)
}

const toggleAdvancedFilter = () => {
  showAdvancedFilter.value = !showAdvancedFilter.value

  emit('toggle-filter', showAdvancedFilter.value)
}
</script>

<template>
  <div class="flex items-center">
    <div class="flex-1 bg-white py-1 px-3 rounded-l-full" :class="{ 'rounded-r-full': !advancedFilter }">
      <ElInput
        class="search"
          :model-value="modelValue"
          :prefix-icon="SearchIcon"
          :placeholder="placeholder"
          clearable
          @input="handleInput"
        />
    </div>
    <button v-if="advancedFilter"
      class="py-2.5 pl-3 pr-4 rounded-r-full flex flex-col justify-center bg-gray-200"
      :class="{ 'bg-blue-500': showAdvancedFilter }"
      type="button"
      @click="toggleAdvancedFilter"
    >
      <AdjustmentsIcon class="h-5 w-5 stroke-gray-400" :class="{ 'stroke-white': showAdvancedFilter }" />
    </button>
  </div>
</template>

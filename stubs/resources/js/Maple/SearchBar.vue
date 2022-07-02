<script setup>
import { ref } from 'vue'
import Search from '@/Maple/Search.vue'

defineProps({
  search: String,

  advancedFilter: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits([
  'update:search',
  'search',
])

const showFilter = ref(false)

const handleSearch = (val) => {
  emit('update:search', val)
  emit('search', val)
}
</script>

<template>
  <div class="flex justify-between items-center mb-4 -mx-2">
    <div class="w-full md:w-96 p-2">
      <Search
        v-model="search"
        :advanced-filter="advancedFilter"
        @input="handleSearch"
        @toggle-filter="showFilter = !showFilter" />
    </div>
    <div v-if="$slots.create" class="w-auto p-2">
      <slot name="create" />
    </div>
  </div>

  <Transition>
    <div v-show="showFilter" class="bg-white rounded-lg shadow p-6 mb-8">
      <slot name="filter" />
    </div>
  </Transition>
</template>

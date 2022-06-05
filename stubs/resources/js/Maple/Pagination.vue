<script setup>
import { ElPagination } from 'element-plus'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/outline'

const props = defineProps({
  currentPage: {
    type: Number,
    required: true
  },
  pageSize: {
    type: Number,
    required: true
  },
  lastPage: {
    type: Number,
    required: true
  },
  from: {
    type: Number,
    required: true
  },
  to: {
    type: Number,
    required: true
  },
  total: {
    type: Number,
    required: true
  },
  layouts: {
    type: String,
    default: 'prev, pager-with-count, next'
  }
})

const emit = defineEmits(['current-change'])

const layouts = props.layouts.replace(/\s+/g, '').split(',')

function onCurrentChange(page) {
  emit('current-change', page)
}
</script>

<template>
  <div class="flex flex-col">
    <div class="flex justify-between items-center">
      <button v-if="layouts.includes('prev')" :disabled="props.currentPage == 1" type="button" @click.prevent="onCurrentChange(props.currentPage - 1)">
        <chevron-left-icon :class="{
          'stroke-gray-200 hover:stroke-gray-200': props.currentPage == 1,
          'stroke-gray-400 hover:stroke-blue-500': props.currentPage > 1
        }" class="h-5 w-5" />
      </button>

      <el-pagination v-if="layouts.includes('pager-with-count')" :current-page="props.currentPage" :page-size="props.pageSize" :total="props.total" layout="pager"
        @current-change="onCurrentChange" />

      <button v-if="layouts.includes('next')" :disabled="props.currentPage == props.lastPage" type="button"
        @click.prevent="onCurrentChange(props.currentPage + 1)">
        <chevron-right-icon :class="{
          'stroke-gray-200 hover:stroke-gray-200': props.currentPage == props.lastPage,
          'stroke-gray-400 hover:stroke-blue-500': props.currentPage < props.lastPage,
        }" class="h-5 w-5" />
      </button>
    </div>

    <div v-if="layouts.includes('pager-with-count') && props.from && props.to && props.total" class="flex justify-center">
      <span class="text-sm text-gray-500 mt-3">
        {{ `${props.from}-${props.to} of ${props.total}` }}
      </span>
    </div>
  </div>
</template>

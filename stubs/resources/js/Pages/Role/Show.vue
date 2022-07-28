<script setup>
import { ref } from 'vue'
import { ElButton, ElInput, ElNotification } from 'element-plus'
import AppLayout from '@/Layouts/AppLayout.vue'
import Content from '@/Layouts/Content.vue'
import MapleFormItem from '@/Maple/FormItem.vue'
import MapleDescItem from '@/Maple/DescriptionItem.vue'

const props = defineProps({
  guards: Object,
  role: Object,
})

const permissions = props.role.permissions.map((p) => p.id)
</script>

<template>
  <AppLayout title="Role Details">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Role Details
      </h2>
    </template>

    <Content>
      <div class="bg-white rounded-lg shadow p-6">
        <MapleDescItem label="Name">{{ props.role.name }}</MapleDescItem>
        <MapleDescItem label="Guard Name">{{ props.role.guard_name }}</MapleDescItem>

        <div class="mt-4">
          <div class="text-sm text-gray-500 font-semibold mb-1">Permissions</div>
          <div v-for="guard in props.guards" :key="guard.name">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div v-for="collection in guard.collections" :key="collection.name">
                <h6 class="text-sm font-semibold">{{ collection.name.toUpperCase() }}</h6>
                <ul>
                  <li v-for="permission in collection.permissions" :key="permission.name">
                    <label>
                      <span class="inline-block h-3 w-3 rounded-full"
                        :class="permissions.includes(permission.id) ? 'bg-green-500' : 'bg-red-500'"></span>
                      <span class="ml-2 text-gray-700 font-light">{{ permission.action }}</span>
                    </label>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Content>
  </AppLayout>
</template>

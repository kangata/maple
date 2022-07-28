<script setup>
import { ref } from 'vue'
import { ElButton, ElInput, ElNotification } from 'element-plus'
import AppLayout from '@/Layouts/AppLayout.vue'
import Content from '@/Layouts/Content.vue'
import MapleFormItem from '@/Maple/FormItem.vue'

const props = defineProps({
  guards: Object,
})

const input = ref({
  name: null,
  guard_name: 'web',
  permissions: [],
})

const errors = ref(null)

const loading = ref(false)

const create = async () => {
  errors.value = null
  loading.value = true

  try {
    const { data } = await axios.post(route('json.roles.store'), input.value)

    ElNotification({
      title: 'Success',
      message: data.message,
      type: 'success'
    })

    setTimeout(() => {
      window.location.href = route('roles.index')
    }, 1000)
  } catch (err) {
    let title = 'Error';
    let message = err.message

    const type = 'error'

    if (err.response) {
      title = `${title} (${err.response.status})`
      message = err.response.data.message ?? err.response.statusText

      if ([422].includes(err.response.status)) {
        errors.value = {}

        Object.keys(err.response.data.errors).map((key) => {
          errors.value[key] = err.response.data.errors[key][0]
        })
      }
    }

    ElNotification({ title, message, type })

    loading.value = false
  }
}
</script>

<template>
  <AppLayout title="Create Role">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Role
      </h2>
    </template>

    <Content>
      <div class="bg-white rounded-lg shadow p-6">
        <MapleFormItem label="Name" class="mb-4" :error="errors?.name">
          <ElInput v-model="input.name" size="large" />
        </MapleFormItem>

        <MapleFormItem label="Guard Name" class="mb-4" :error="errors?.guard_name">
          <ElInput v-model="input.guard_name" size="large" readonly />
        </MapleFormItem>

        <div>
          <div v-for="guard in props.guards" :key="guard.name">
          <div class="text-sm text-gray-500 font-semibold mb-1">Permissions</div>
            <div class="grid grid-cols-3 gap-4">
              <div v-for="collection in guard.collections" :key="collection.name">
                <h6 class="text-sm select-none">{{ collection.name.toUpperCase() }}</h6>
                <ul>
                  <li v-for="permission in collection.permissions" :key="permission.name">
                    <label class="select-none cursor-pointer">
                      <input v-model="input.permissions" type="checkbox" :value="permission.id"
                        class="border-gray-200 focus:ring-0" />
                      <span class="ml-2 text-gray-700 font-light">{{ permission.action }}</span>
                    </label>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-8 flex justify-end">
          <ElButton type="primary" :loading="loading" @click="create">CREATE ROLE</ElButton>
        </div>
      </div>
    </Content>
  </AppLayout>
</template>

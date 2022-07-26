<script setup>
import { ref } from 'vue'
import { ElButton, ElInput, ElNotification, ElSelect, ElOption } from 'element-plus'
import AppLayout from '@/Layouts/AppLayout.vue'
import Content from '@/Layouts/Content.vue'
import MapleFormItem from '@/Maple/FormItem.vue'

const props = defineProps({
  roles: Array
})

const input = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  roles: [],
})

const errors = ref(null)

const loading = ref(false)

const create = async () => {
  errors.value = null
  loading.value = true

  try {
    const { data } = await axios.post(route('json.users.store'), input.value)

    ElNotification({
      title: 'Success',
      message: data.message,
      type: 'success'
    })

    setTimeout(() => {
      window.location.href = route('users.index')
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
  <AppLayout title="Create User">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create User
      </h2>
    </template>

    <Content>
      <div class="bg-white rounded-lg shadow p-6">
        <MapleFormItem label="Name" class="mb-4" :error="errors?.name">
          <ElInput v-model="input.name" size="large" />
        </MapleFormItem>

        <MapleFormItem label="Email" class="mb-4" :error="errors?.email">
          <ElInput v-model="input.email" size="large" />
        </MapleFormItem>

        <MapleFormItem label="Password" class="mb-4" :error="errors?.password">
          <ElInput v-model="input.password" size="large" type="password" show-password />
        </MapleFormItem>

        <MapleFormItem label="Password Confirmation" class="mb-4" :error="errors?.password_confirmation">
          <ElInput v-model="input.password_confirmation" size="large" type="password" show-password />
        </MapleFormItem>

        <MapleFormItem label="Roles" class="mb-4" :error="errors?.password_confirmation">
          <ElSelect v-model="input.roles" size="large" class="w-full" multiple>
            <ElOption v-for="role in roles" :key="role.id" :value="role.id" :label="role.name"></ElOption>
          </ElSelect>
        </MapleFormItem>

        <div class="mt-8 flex justify-end">
          <ElButton type="primary" :loading="loading" @click="create">CREATE USER</ElButton>
        </div>
      </div>
    </Content>
  </AppLayout>
</template>

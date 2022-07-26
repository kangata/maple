<script setup>
import { ref } from 'vue'

import {
  ElMessageBox,
  ElNotification,
  ElTable,
  ElTableColumn
} from 'element-plus'
import { EyeIcon, PencilAltIcon, TrashIcon } from '@heroicons/vue/outline'
import AppLayout from '@/Layouts/AppLayout.vue'
import Content from '@/Layouts/Content.vue'
import MaplePagination from '@/Maple/Pagination.vue'
import MapleSearchBar from '@/Maple/SearchBar.vue'
import MapleCreateButton from '@/Maple/CreateButton.vue'

const props = defineProps({
  actions: Object,
})

const data = ref([])

const paginator = ref({
  current_page: 1,
  per_page: 15,
  last_page: 1,
  from: 0,
  to: 0,
  total: 0,
})

const filter = ref({
  search: '',
  sort: {
    prop: 'name',
    order: 'ascending',
  },
})

async function getUsers() {
  try {
    const resp = await axios.get('/json/users', {
      params: {
        page: paginator.value.current_page,
        search: filter.value.search,
      }
    })

    paginator.value = resp.data.meta
    data.value = resp.data.data.map((user) => {
      user.roles = user.roles.map((role) => role.name).join(', ')

      return user
    })
  } catch (err) {
    console.error(err)
  }
}

const destroy = (id) => {
  ElMessageBox.confirm(
    'This action will permanently delete the user. Continue?',
    'Warning',
    {
      confirmButtonText: 'OK',
      cancelButtonText: 'Cancel',
      type: 'warning',
    }
  ).then(async () => {
    try {
      const resp = await axios.delete(`/json/users/${id}`)

      ElNotification({
        title: 'Success',
        message: resp.data.message,
        type: 'success',
      })

      handleChangePage(1)
    } catch (err) {
      let title = 'Error';
      let message = err.message
      const type = 'error'

      if (err.response) {
        title = `${title} (${err.response.status})`
        message = err.response.data.message ?? err.response.statusText
      }

      ElNotification({ title, message, type })
    }
  }).catch(() => { })
}

function handleChangePage(page) {
  paginator.value.current_page = page

  getUsers()
}

const handleSearch = _.debounce(() => {
  handleChangePage(1)
}, 500)

const handleChangeSort = ({ prop, order }) => {
  filter.value.sort = {
    prop, order
  }

  handleChangePage(1)
}

getUsers()
</script>

<template>
  <AppLayout title="Users">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Users
      </h2>
    </template>

    <Content>
      <MapleSearchBar v-model:search="filter.search" @search="handleSearch">
        <template #create>
          <MapleCreateButton label="CREATE USER" :url="route('users.create')" />
        </template>
      </MapleSearchBar>

      <div class="bg-white rounded-lg shadow p-6">
        <ElTable :data="data" :default-sort="filter.sort" @sort-change="handleChangeSort">
          <ElTableColumn prop="name" label="Name" width="250" sortable />
          <ElTableColumn prop="email" label="Email" min-width="250" sortable />
          <ElTableColumn label="Roles" min-width="250">
            <template #default="scope">
              {{ scope.row.roles }}
            </template>
          </ElTableColumn>
          <ElTableColumn prop="updated_at" label="Updated At" width="200" sortable />
          <ElTableColumn min-width="50">
            <template #default="scope">
              <div class="flex justify-around">
                <a v-if="props.actions?.show" :href="route('users.show', scope.row.id)" class="mx-1">
                  <eye-icon class="h-5 w-5 stroke-gray-400 hover:stroke-blue-500" />
                </a>
                <a v-if="props.actions?.edit" :href="route('users.edit', scope.row.id)" class="mx-1">
                  <pencil-alt-icon class="h-5 w-5 stroke-gray-400 hover:stroke-blue-500" />
                </a>
                <a v-if="props.actions?.destroy && $page.props.user.id != scope.row.id" href="#" @click.prevent="destroy(scope.row.id)" class="mx-1">
                  <trash-icon class="h-5 w-5 stroke-gray-400 hover:stroke-red-500" />
                </a>
              </div>
            </template>
          </ElTableColumn>
        </ElTable>

        <MaplePagination v-if="paginator.last_page > 1" :current-page="paginator.current_page"
          :page-size="paginator.per_page" :last-page="paginator.last_page" :from="paginator.from" :to="paginator.to"
          :total="paginator.total" class="mt-6" @current-change="handleChangePage" />
      </div>
    </Content>
  </AppLayout>
</template>

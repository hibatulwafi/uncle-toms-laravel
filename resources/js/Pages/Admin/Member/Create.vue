<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
  mdiAccountMultiple,
  mdiPlus,
  mdiSquareEditOutline,
  mdiTrashCan,
  mdiAlertBoxOutline,
} from "@mdi/js";
import LayoutAuthenticated from "@/Layouts/Admin/LayoutAuthenticated.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import BaseButton from "@/Components/BaseButton.vue";
import CardBox from "@/Components/CardBox.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import NotificationBar from "@/Components/NotificationBar.vue";
import Pagination from "@/Components/Admin/Pagination.vue";
import Sort from "@/Components/Admin/Sort.vue";

const props = defineProps({
  items: {
    type: Object,
    default: () => ({}),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  can: {
    type: Object,
    default: () => ({}),
  },
});

const form = useForm({
  search: props.filters.search,
});

const formDelete = useForm({});

function destroy(id) {
  if (confirm("Are you sure you want to delete?")) {
    formDelete.delete(route("admin.members.destroy", id));
  }
}
</script>

<template>
  <LayoutAuthenticated>

    <Head title="Members" />
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiAccountMultiple" title="Members" main>
        <BaseButton v-if="can.create" :route-name="route('admin.members.create')" :icon="mdiPlus" label="Add Member"
          color="info" rounded-full small />
      </SectionTitleLineWithButton>
      <NotificationBar :key="Date.now()" v-if="$page.props.flash.message" color="success" :icon="mdiAlertBoxOutline">
        {{ $page.props.flash.message }}
      </NotificationBar>
      <CardBox class="mb-6" has-table>
        <form @submit.prevent="form.get(route('admin.members.index'))">
          <div class="py-2 flex">
            <div class="flex pl-4">
              <input type="search" v-model="form.search" class="
                                  rounded-md
                                  shadow-sm
                                  border-gray-300
                                  focus:border-indigo-300
                                  focus:ring
                                  focus:ring-indigo-200
                                  focus:ring-opacity-50
                              " placeholder="Search" />
              <BaseButton label="Search" type="submit" color="info" class="ml-4 inline-flex items-center px-4 py-2" />
            </div>
          </div>
        </form>
      </CardBox>
      <CardBox class="mb-6" has-table>
        <table>
          <thead>
            <tr>
              <th>Photo</th>
              <th>
                <Sort label="Name" attribute="name" />
              </th>
              <th>Email</th>
              <th>Phone</th>
              <th>Created</th>
              <th v-if="can.edit || can.delete">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="member in items.data" :key="member.id">
              <td data-label="Photo">
                <div class="w-32 rounded">
                  <div v-if="member.photo">
                    <img :src="`/storage/${member.photo}`" :alt="member.name"
                      class="block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800" />
                  </div>
                  <div v-else>
                    No Photo
                  </div>
                </div>
              </td>
              <td data-label="Name">
                <Link :href="route('admin.members.show', member.id)" class="
                                      no-underline
                                      hover:underline
                                      text-cyan-600
                                      dark:text-cyan-400
                                  ">
                {{ member.name }}
                </Link>
              </td>
              <td data-label="Email">
                {{ member.email }}
              </td>
              <td data-label="Phone">
                {{ member.phone }}
              </td>
              <td data-label="created_at">
                {{ new Date(member.created_at).toLocaleString() }}
              </td>
              <td v-if="can.edit || can.delete" class="before:hidden lg:w-1 whitespace-nowrap">
                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                  <BaseButton v-if="can.edit" :route-name="route('admin.members.edit', member.id)" color="info"
                    :icon="mdiSquareEditOutline" small />
                  <BaseButton v-if="can.delete" color="danger" :icon="mdiTrashCan" small @click="destroy(member.id)" />
                </BaseButtons>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="py-4">
          <Pagination :data="items" />
        </div>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
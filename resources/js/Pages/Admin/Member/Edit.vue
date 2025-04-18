<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { mdiAccountMultiple, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/Layouts/Admin/LayoutAuthenticated.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";

const props = defineProps({
  member: {
    type: Object,
    default: () => ({}),
  },
});

const form = useForm({
  _method: "put",
  name: props.member.name,
  email: props.member.email,
  phone: props.member.phone,
  birth_date: props.member.birth_date,
  gender: props.member.gender,
  address: props.member.address,
  photo: null, // Untuk file foto yang baru
  password: null, // Untuk password yang baru
});
</script>

<template>
  <LayoutAuthenticated>

    <Head title="Update Member" />
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiAccountMultiple" title="Update Member" main>
        <BaseButton :route-name="route('admin.members.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
          color="white" rounded-full small />
      </SectionTitleLineWithButton>
      <CardBox form @submit.prevent="form.post(route('admin.members.update', props.member.id))">
        <FormField label="Name" :class="{ 'text-red-400': form.errors.name }">
          <FormControl v-model="form.name" type="text" placeholder="Enter Name" :error="form.errors.name">
            <div class="text-red-400 text-sm" v-if="form.errors.name">
              {{ form.errors.name }}
            </div>
          </FormControl>
        </FormField>

        <FormField label="Email" :class="{ 'text-red-400': form.errors.email }">
          <FormControl v-model="form.email" type="email" placeholder="Enter Email" :error="form.errors.email">
            <div class="text-red-400 text-sm" v-if="form.errors.email">
              {{ form.errors.email }}
            </div>
          </FormControl>
        </FormField>

        <FormField label="Phone" :class="{ 'text-red-400': form.errors.phone }">
          <FormControl v-model="form.phone" type="text" placeholder="Enter Phone" :error="form.errors.phone">
            <div class="text-red-400 text-sm" v-if="form.errors.phone">
              {{ form.errors.phone }}
            </div>
          </FormControl>
        </FormField>

        <FormField label="Birth Date" :class="{ 'text-red-400': form.errors.birth_date }">
          <FormControl v-model="form.birth_date" type="date" :error="form.errors.birth_date">
            <div class="text-red-400 text-sm" v-if="form.errors.birth_date">
              {{ form.errors.birth_date }}
            </div>
          </FormControl>
        </FormField>

        <FormField label="Gender" :class="{ 'text-red-400': form.errors.gender }">
          <FormControl v-model="form.gender" type="select" :error="form.errors.gender"
            :options="[{ value: 'male', label: 'Male' }, { value: 'female', label: 'Female' }, { value: 'other', label: 'Other' }]">
            <div class="text-red-400 text-sm" v-if="form.errors.gender">
              {{ form.errors.gender }}
            </div>
          </FormControl>
        </FormField>

        <FormField label="Address" :class="{ 'text-red-400': form.errors.address }">
          <FormControl v-model="form.address" type="textarea" placeholder="Enter Address" :error="form.errors.address">
            <div class="text-red-400 text-sm" v-if="form.errors.address">
              {{ form.errors.address }}
            </div>
          </FormControl>
        </FormField>

        <div class="w-32 rounded">
          <div v-if="member.photo">
            <img :src="`/storage/${member.photo}`" :alt="member.name"
              class="block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800" />
          </div>
          <div v-else>
            No Photo
          </div>
        </div>

        <FormField label="Photo" :class="{ 'text-red-400': form.errors.photo }">
          <FormControl v-model="form.photo" type="file" placeholder="Select Photo" :error="form.errors.photo"
            @input="form.photo = $event.target.files[0]">
            <div class="text-red-400 text-sm" v-if="form.errors.photo">
              {{ form.errors.photo }}
            </div>
          </FormControl>
        </FormField>

        <FormField label="Password" :class="{ 'text-red-400': form.errors.password }">
          <FormControl v-model="form.password" type="password"
            placeholder="Enter New Password (Leave blank to keep current)" :error="form.errors.password">
            <div class="text-red-400 text-sm" v-if="form.errors.password">
              {{ form.errors.password }}
            </div>
          </FormControl>
        </FormField>

        <template #footer>
          <BaseButtons>
            <BaseButton type="submit" color="info" label="Submit" :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing" />
          </BaseButtons>
        </template>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
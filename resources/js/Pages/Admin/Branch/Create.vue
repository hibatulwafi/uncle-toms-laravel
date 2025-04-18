<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
  mdiMultimedia,
  mdiArrowLeftBoldOutline,
} from "@mdi/js";
import LayoutAuthenticated from "@/Layouts/Admin/LayoutAuthenticated.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";

const form = useForm({
  branch_name: null,
  address: null,
  phone: null,
  photo: null,
  gmap_link: null,
});
</script>

<template>
  <LayoutAuthenticated>

    <Head title="Create Branches" />
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiMultimedia" title="Add Branches" main>
        <BaseButton :route-name="route('admin.branches.index')" :icon="mdiArrowLeftBoldOutline" label="Back"
          color="white" rounded-full small />
      </SectionTitleLineWithButton>
      <CardBox form @submit.prevent="form.post(route('admin.branches.store'))">
        <FormField label="Branch Name" :class="{ 'text-red-400': form.errors.branch_name }">
          <FormControl v-model="form.branch_name" type="text" placeholder="Enter Branch Name"
            :error="form.errors.branch_name">
            <div class="text-red-400 text-sm" v-if="form.errors.branch_name">
              {{ form.errors.branch_name }}
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

        <FormField label="Phone" :class="{ 'text-red-400': form.errors.phone }">
          <FormControl v-model="form.phone" type="text" placeholder="Enter Phone Number" :error="form.errors.phone">
            <div class="text-red-400 text-sm" v-if="form.errors.phone">
              {{ form.errors.phone }}
            </div>
          </FormControl>
        </FormField>

        <FormField label="Photo" :class="{ 'text-red-400': form.errors.photo }">
          <FormControl v-model="form.photo" type="file" placeholder="Select Photo" :error="form.errors.photo"
            @input="form.photo = $event.target.files[0]">
            <div class="text-red-400 text-sm" v-if="form.errors.photo">
              {{ form.errors.photo }}
            </div>
          </FormControl>
        </FormField>

        <FormField label="Google Maps Link" :class="{ 'text-red-400': form.errors.gmap_link }">
          <FormControl v-model="form.gmap_link" type="text" placeholder="Enter Google Maps Link"
            :error="form.errors.gmap_link">
            <div class="text-red-400 text-sm" v-if="form.errors.gmap_link">
              {{ form.errors.gmap_link }}
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
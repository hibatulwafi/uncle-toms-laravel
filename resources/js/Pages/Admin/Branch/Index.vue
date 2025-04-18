<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    mdiPlus,
    mdiSquareEditOutline,
    mdiTrashCan,
    mdiAlertBoxOutline,
    mdiHomeAccount,
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
    branches: {
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
        formDelete.delete(route("admin.branches.destroy", id));
    }
}

const photoModalOpen = ref(false);
const qrModalOpen = ref(false);
const selectedBranch = ref({});

function openPhotoModal(branch) {
    selectedBranch.value = branch;
    photoModalOpen.value = true;
}

function openQrModal(branch) {
    selectedBranch.value = branch;
    qrModalOpen.value = true;
}
</script>

<template>
    <LayoutAuthenticated>

        <Head title="Branches" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiHomeAccount" title="Branches" main>
                <BaseButton v-if="can.create" :route-name="route('admin.branches.create')" :icon="mdiPlus" label="Add"
                    color="info" rounded-full small />
            </SectionTitleLineWithButton>
            <NotificationBar :key="Date.now()" v-if="$page.props.flash.message" color="success"
                :icon="mdiAlertBoxOutline">
                {{ $page.props.flash.message }}
            </NotificationBar>
            <CardBox class="mb-6" has-table>
                <form @submit.prevent="form.get(route('admin.branches.index'))">
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
                            <BaseButton label="Search" type="submit" color="info"
                                class="ml-4 inline-flex items-center px-4 py-2" />
                        </div>
                    </div>
                </form>
            </CardBox>
            <CardBox class="mb-6" has-table>
                <table>
                    <thead>
                        <tr>
                            <th>
                                <Sort label="Name" attribute="branch_name" />
                            </th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Branch Photo</th>
                            <th>QR Code</th>
                            <th v-if="can.edit || can.delete">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="branch in branches.data" :key="branch.id">
                            <td data-label="Name">
                                {{ branch.branch_name }}
                            </td>
                            <td data-label="Address">
                                {{ branch.address }}
                            </td>
                            <td data-label="Phone">
                                {{ branch.phone }}
                            </td>
                            <td data-label="Photo">
                                <BaseButton color="info" label="View" small @click="openPhotoModal(branch)" />
                            </td>
                            <td data-label="QR Code">
                                <BaseButton color="info" label="View" small @click="openQrModal(branch)" />
                            </td>
                            <td v-if="can.edit || can.delete" class="before:hidden lg:w-1 whitespace-nowrap">
                                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                                    <BaseButton v-if="can.edit" :route-name="route('admin.branches.edit', branch.id)"
                                        color="info" :icon="mdiSquareEditOutline" small />
                                    <BaseButton v-if="can.delete" color="danger" :icon="mdiTrashCan" small
                                        @click="destroy(branch.id)" />
                                </BaseButtons>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="py-4">
                    <Pagination :data="branches" />
                </div>
            </CardBox>
        </SectionMain>

        <!-- Modal QR Code -->
        <div v-if="qrModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full text-center">
                <h2 class="text-xl font-semibold mb-4">{{ selectedBranch.branch_name }}</h2>
                <div v-if="selectedBranch.qrcode_image">
                    <h3 class="font-semibold">QR Code</h3>
                    <img :src="selectedBranch.qrcode_image" alt="QR Code" class="w-40 h-40 mx-auto my-4" />
                    <a :href="selectedBranch.qrcode_image" download="qrcode.png">
                        <BaseButton color="primary" label="Download QR Code" />
                    </a>
                </div>
                <BaseButton color="danger" label="Close" @click="qrModalOpen = false" class="mt-4" />
            </div>
        </div>

        <!-- Modal Photo -->
        <div v-if="photoModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full text-center">
                <h2 class="text-xl font-semibold mb-4">{{ selectedBranch.branch_name }}</h2>
                <div v-if="selectedBranch.photo">
                    <h3 class="font-semibold">Branch Photo</h3>
                    <img :src="'/storage/' + selectedBranch.photo" alt="Branch Photo"
                        class="w-60 h-60 object-cover mx-auto my-4 rounded-lg" />
                    <a :href="'/storage/' + selectedBranch.photo" download="branch-photo.jpg">
                        <BaseButton color="primary" label="Download Photo" />
                    </a>
                </div>
                <BaseButton color="danger" label="Close" @click="photoModalOpen = false" class="mt-4" />
            </div>
        </div>

    </LayoutAuthenticated>
</template>
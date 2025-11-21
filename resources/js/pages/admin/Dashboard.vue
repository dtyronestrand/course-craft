<template>
    <AdminLayout>
    <header class="flex flex-row px-8 justify-between w-full">
    <div>
    <Search />
    </div>
    <div class="flex flex-row gap-4 items-center">
    <Icon name="Bell"/>
  <span class="rounded-full text-center bg-primary text-primary-content w-8 h-8 flex items-center justify-center font-bold">
    {{ getInitials(page.props.auth.user.first_name, page.props.auth.user.last_name) }}
    </span>
    </div>
    </header>
        <h1 class="text-7xl text-center mx-auto p-4 my-8 prose">{{ page.props.auth.user.first_name }}'s Dashboard</h1>

</AdminLayout>
</template>

<script setup lang="ts">

import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import Search from '@/components/Search.vue';
import Icon from '@/components/Icon.vue';
import { useInitials } from '@/composables/useInitials';

const page = usePage();
const { getInitials } = useInitials();
</script>

<style scoped>

</style>
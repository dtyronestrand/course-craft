<template>
<component :is="layoutComponent">


</component>


</template>

<script setup lang="ts">
import New from '@/layouts/New.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import {usePage} from '@inertiajs/vue3';
const page = usePage();
const layoutComponent = page.props.auth.user.is_admin ? AdminLayout : New;
</script>

<style scoped>

</style>
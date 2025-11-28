<template>
  <AdminLayout>
    <div class="mx-12">
    <h1 class="text-7xl mb-8">Users</h1>
    <button class="btn btn-info">Add New User</button>
    <div class="grid grid-cols-3 gap-4 mt-8">
        <div v-for="user in (page.props.users as User[])" :key="user.id" class="max-w-md w-full bg-base-100/10 backdrop-blur-lg runded-3xl tranform hover:scale-[1.02] transition-tranform duration-500 ease-in-out p-8 relative overflow-hidden border border-primary/20">
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-primary rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
    <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-accent rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
         <div class="absolute inset-0 bg-linear-to-br from-primary/10 via-transparent to-accent/10 rounded-3xl"></div>
             <div class="flex justify-center mb-6 relative z-10 mt-5">
      <div class="relative group">
        <div class="absolute inset-0  bg-linear-to-br from-primary to-accent rounded-full blur-md group-hover:blur-lg transition-all duration-500"></div>
        <img v-if="user.avatar" class="w-40 h-40 object-cover rounded-full border-4 border-primary/80 shadow-xl hover:scale-105 transform transition-all duration-400 relative z-10" 
             src="https://avatars.githubusercontent.com/u/124576166?v=4" 
             alt="Profile Image">
        <div v-else class="w-40 h-40 flex items-center text-primary-content text-3xl font-bold justify-center bg-primary/20 rounded-full border-4 border-primary/80 shadow-xl hover:scale-105 transform transition-all duration-400 relative z-10">{{ getInitials(user.first_name, user.last_name) }}</div>
      </div>
    </div>
        <div class="text-center mb-6 relative z-10">
      <h2 class="text-3xl font-bold text-base-content mb-2 tracking-tight">
        <span class="relative inline-block">
       {{user.first_name}} {{user.last_name}}
          <span class="absolute bottom-0 left-0 w-full h-1 bg-linear-to-r from-primary to-accent rounded-full transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
        </span>
      </h2>
      <p class="text-lg font-medium text-accent tracking-wider"></p>
    </div>
        </div>

    </div>
    </div>
  </AdminLayout>

</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { getInitials } from '@/composables/useInitials';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface User {
  id: number;
  first_name: string;
  last_name: string;
  avatar?: string;

}

const page = usePage();

</script>

<style scoped>

</style>
<template>
    <AdminLayout>
        <div class="mx-12">
            <h1 class="mb-8 text-7xl">Users</h1>
            <button
                class="frosted-backdrop btn w-max border-info bg-info/10 text-info shadow-md shadow-info/10"
            >
                Add New User
            </button>
            <div class="mt-8 grid grid-cols-3 gap-4">
                <div
                    v-for="user in page.props.users as User[]"
                    :key="user.id"
                    class="runded-3xl tranform transition-tranform relative w-full max-w-md overflow-hidden border border-primary/20 bg-base-100/10 p-8 backdrop-blur-lg duration-500 ease-in-out hover:scale-[1.02]"
                >
                    <div
                        class="absolute -top-20 -right-20 filter h-40 w-40 rounded-full bg-primary opacity-70 mix-blend-multiply blur-xl"
                    ></div>
                    <div
                        class="absolute -bottom-20 -left-20 filter h-40 w-40 rounded-full bg-accent opacity-70 mix-blend-multiply blur-xl"
                    ></div>
                    <div
                        class="absolute inset-0 rounded-3xl bg-linear-to-br from-primary/10 via-transparent to-accent/10"
                    ></div>
                    <div class="relative z-10 mt-5 mb-6 flex justify-center">
                        <div class="group relative">
                            <div
                                class="absolute inset-0 rounded-full bg-linear-to-br from-primary to-accent blur-md transition-all duration-500 group-hover:blur-lg"
                            ></div>
                            <img
                                v-if="user.avatar"
                                class="relative z-10 h-40 w-40 transform rounded-full border-4 border-primary/80 object-cover shadow-xl transition-all duration-400 hover:scale-105"
                                src="https://avatars.githubusercontent.com/u/124576166?v=4"
                                alt="Profile Image"
                            />
                            <div
                                v-else
                                class="relative z-10 flex h-40 w-40 transform items-center justify-center rounded-full border-4 border-primary/80 bg-primary/20 text-3xl font-bold text-primary-content shadow-xl transition-all duration-400 hover:scale-105"
                            >
                                {{
                                    getInitials(user.first_name, user.last_name)
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="relative z-10 mb-6 text-center">
                        <h2
                            class="mb-2 text-3xl font-bold tracking-tight text-base-content"
                        >
                            <span class="relative inline-block">
                                {{ user.first_name }} {{ user.last_name }}
                                <span
                                    class="absolute bottom-0 left-0 h-1 w-full origin-left scale-x-0 transform rounded-full bg-linear-to-r from-primary to-accent transition-transform duration-300 group-hover:scale-x-100"
                                ></span>
                            </span>
                        </h2>
                        <p
                            class="text-lg font-medium tracking-wider text-accent"
                        ></p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { getInitials } from '@/composables/useInitials';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { usePage } from '@inertiajs/vue3';

interface User {
    id: number;
    first_name: string;
    last_name: string;
    avatar?: string;
}

const page = usePage();
</script>

<style scoped></style>

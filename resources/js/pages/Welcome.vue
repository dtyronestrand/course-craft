<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex h-screen items-center justify-center bg-base-300 p-5">
        <div
            class="grid grid-cols-1 items-center gap-10 md:grid-cols-2 md:px-10"
        >
            <div>
                <h1 class="mb-2 text-7xl font-bold text-base-content">
                    Course Crafter
                </h1>
                <p class="mt-6 mb-6 text-3xl text-base-content">
                    Let's get started
                </p>
                <div
                    v-if="page.props.auth.user === null"
                    class="flex justify-center space-x-5"
                >
                    <Link
                        :href="login()"
                        class="mt-4 flex w-full items-center justify-center gap-1 rounded-2xl bg-primary p-5 py-3 font-semibold text-primary-content hover:bg-primary/30 active:bg-primary/50"
                        >Login</Link
                    >
                    <Link
                        :href="register()"
                        class="mt-4 flex w-full items-center justify-center gap-1 rounded-2xl bg-secondary p-5 py-3 font-semibold text-secondary-content hover:bg-secondary/30 active:bg-secondary/50"
                    >
                        Register</Link
                    >
                </div>
                <div v-else class="flex justify-center space-x-5">
                    <Link
                        :href="dashboard()"
                        class="mt-4 flex w-full items-center justify-center gap-1 rounded-2xl bg-primary p-5 py-3 font-semibold text-primary-content hover:bg-primary/70 hover:text-primary-content"
                        >Dashboard</Link
                    >
                    <button
                        @click="$inertia.post('/logout')"
                        class="mt-4 flex w-full items-center justify-center gap-1 rounded-2xl bg-error p-5 py-3 font-semibold text-error-content hover:bg-error/70 hover:text-error-content"
                    >
                        Logout
                    </button>
                </div>
            </div>
            <img src="../lib/assets/CCLogo.png" alt="Course Crafter Logo" />
        </div>
    </div>
</template>

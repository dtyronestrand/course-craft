<template>
    <div class="drawer lg:drawer-open">
        <input id="sidebar" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex w-full flex-col overflow-x-hidden">
            <main class="ml-4 flex flex-1 gap-6">
                <slot />
            </main>
            <label for="sidebar" class="drawer-button btn lg:hidden"
                >Open Sidebar</label
            >
        </div>
        <div class="drawer-side">
            <label
                for="sidebar"
                aria-label="close sidebar"
                class="drawer-overlay"
            ></label>
            <div
                class="align-center glass-effect flex w-80 flex-row items-center gap-4 p-4 text-base-content"
            >
                <AppLogo class="m-0" />
            </div>
            <ul
                class="glass-effect menu min-h-full w-80 p-4 text-2xl text-base-content"
            >
                <li>
                    <Link href="/admin/dashboard"
                        ><Icon name="LayoutGrid" /><span class="px-4"
                            >Dashboard</span
                        ></Link
                    >
                </li>
                <li>
                    <Link href="/admin/calendar"
                        ><Icon name="Calendar" /><span class="px-4"
                            >Calendar</span
                        ></Link
                    >
                </li>
                <li>
                    <Link href="/admin/courses"
                        ><Icon name="Folder" /><span class="px-4"
                            >Courses</span
                        ></Link
                    >
                </li>
                <li>
                    <Link href="/admin/users"
                        ><Icon name="PersonStanding" /><span class="px-4"
                            >Users</span
                        ></Link
                    >
                </li>
                <li>
                    <Link href="/admin/settings"
                        ><Icon name="Settings" /><span class="px-4"
                            >Settings</span
                        ></Link
                    >
                </li>
                <li class="mt-100">
                    <button class="btn btn-ghost" @click="logout">
                        <Icon name="ArrowRightToLine" /><span class="px-4"
                            >Log Out</span
                        >
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import Icon from '@/components/Icon.vue';
import { Link, router } from '@inertiajs/vue3';

const logout = () => {
    router.post('/logout');
};
</script>

<style scoped></style>

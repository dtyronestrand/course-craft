<template>
    <div class="drawer lg:drawer-open">
        <input id="sidebar" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex w-full flex-col items-center justify-center overflow-x-hidden">
            <header class="bg-primary text-primary-content w-full flex items-center justify-between mx-4 px-4 py-4">
                <h1 class="text-3xl font-bold">
                    {{ page.props.auth.user.first_name }}
                    {{ page.props.auth.user.last_name }}
                </h1>
                <div class="flex items-center gap-4">
                    <div class="flex flex-row items-center gap-2">
                        <NotificationCenter
                            :notifications="page.props.auth.user.notifications"
                        />
                    </div>
                    <details class="dropdown">
                        <summary
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-accent text-center font-bold text-accent-content"
                        >
                            {{
                                getInitials(
                                    page.props.auth.user.first_name,
                                    page.props.auth.user.last_name,
                                )
                            }}
                        </summary>
                        <ul
                            class="dropdown-content menu -left-20 z-1 w-42 rounded-box bg-base-100 p-2 shadow-sm"
                        >
                            <li><a>Profile</a></li>
                            <li>
                                <Link href="/admin/settings">Settings</Link>
                            </li>
                            <li>
                                <button class="btn btn-ghost" @click="logout">
                                    Logout
                                </button>
                            </li>
                        </ul>
                    </details>
                </div>
            </header>
            <main class="ml-4 flex flex-1 gap-6">
                <slot />
            </main>
            <label for="sidebar" class="drawer-button btn lg:hidden"
                >Open Sidebar</label
            >
        </div>
        <div class="drawer-side border-r border-primary bg-base-100">
            <label
                for="sidebar"
                aria-label="close sidebar"
                class="drawer-overlay"
            ></label>
            <div
                class="align-center flex w-80 flex-row items-center gap-4 p-2 text-base-content"
            >
                <AppLogo class="m-0" />
            </div>
            <ul
                class=" menu min-h-full w-80 p-4 text-2xl text-base-content"
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
import NotificationCenter from '@/components/NotificationCenter.vue';
import { useInitials } from '@/composables/useInitials';
import { Link, router, usePage } from '@inertiajs/vue3';

const { getInitials } = useInitials();
const page = usePage();
const logout = () => {
    router.post('/logout');
};
</script>

<style scoped></style>

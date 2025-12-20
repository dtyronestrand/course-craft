<template>
    <div class="drawer lg:drawer-open">
        <input id="sidebar" type="checkbox" class="drawer-toggle" />
        <div
            class="drawer-content flex w-full flex-col items-center justify-center overflow-x-hidden"
        >
            <header
                class="mx-4 flex w-full items-center justify-between bg-primary px-4 py-4 text-primary-content"
            >
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
                            class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-accent text-center font-bold text-accent-content"
                        >
                            {{
                                getInitials(
                                    page.props.auth.user.first_name,
                                    page.props.auth.user.last_name,
                                )
                            }}
                        </summary>
                        <ul
                            class="dropdown-content menu -left-20 z-1 w-42 rounded-box bg-base-100 p-2 text-base-content shadow-sm"
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
            <main class="flex w-full flex-1 overflow-x-auto">
                <slot />
                <ChatSidebar :open="chatOpen" @close="chatOpen = false" />
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
            <ul class="menu min-h-full w-80 p-4 text-2xl text-base-content">
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
                    <button
                        @click="chatOpen = true"
                        class="flex w-full items-center py-2 transition-colors hover:bg-base-200"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                            />
                        </svg>
                        <span class="flex-1 px-4 text-left">Messages</span>
                        <span
                            v-if="unreadCount > 0"
                            class="ml-2 min-w-[1.5rem] rounded-full bg-red-600 px-2 py-1 text-center text-xs font-bold text-white"
                        >
                            {{ unreadCount > 99 ? '99+' : unreadCount }}
                        </span>
                    </button>
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
import ChatSidebar from '@/components/Chat/ChatSidebar.vue';
import Icon from '@/components/Icon.vue';
import NotificationCenter from '@/components/NotificationCenter.vue';
import { useInitials } from '@/composables/useInitials';
import { useNotifications } from '@/composables/useNotifications';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const { getInitials } = useInitials();
const { unreadCount } = useNotifications();
const chatOpen = ref(false);
const page = usePage();
const logout = () => {
    router.post('/logout');
};
</script>

<style scoped></style>

<template>
    <div
        class="max-w-none"
        style="box-shadow: 0 215px 0 -100px var(--color-neutral) inset"
    >
        <!-- Centered main content -->
        <div class="container px-3 md:mx-auto md:px-0">
            <!-- Top nav bar -->
            <div
                class="flex justify-between bg-neutral py-3 text-neutral-content"
            >
                <!-- left Logo -->
                <Link :href="'/dashboard'">
                    <img
                        src="../lib/assets/CCLogo.png"
                        alt="Course Craft Logo"
                        class="h-8 md:h-10"
                    />
                </Link>
                <div class="ml-auto flex items-center space-x-2">
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

                    <div class="relative flex items-center space-x-1">
                        <Button
                            variant="ghost"
                            size="icon"
                            class="group h-9 w-9 cursor-pointer"
                        >
                            <Search
                                class="size-5 opacity-80 group-hover:opacity-100"
                            />
                        </Button>

                        <div class="hidden space-x-1 lg:flex">
                            <template
                                v-for="item in rightNavItems"
                                :key="item.title"
                            >
                                <TooltipProvider :delay-duration="0">
                                    <Tooltip>
                                        <TooltipTrigger>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                as-child
                                                class="group h-9 w-9 cursor-pointer"
                                            >
                                                <a
                                                    :href="toUrl(item.href)"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    <span class="sr-only">{{
                                                        item.title
                                                    }}</span>
                                                    <component
                                                        :is="item.icon"
                                                        class="size-5 opacity-80 group-hover:opacity-100"
                                                    />
                                                </a>
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>{{ item.title }}</p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </template>
                        </div>
                    </div>

                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative size-10 w-auto rounded-full p-1 focus-within:ring-2 focus-within:ring-primary"
                            >
                                <Avatar
                                    class="size-8 overflow-hidden rounded-full"
                                >
                                    <AvatarImage
                                        v-if="auth.user?.avatar"
                                        :src="auth.user.avatar"
                                        :alt="auth.user.first_name"
                                    />
                                    <AvatarFallback
                                        class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ userInitials }}
                                    </AvatarFallback>
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56">
                            <UserMenuContent :user="auth.user" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
            <!-- Main content -->
            <div class="min-h-64 rounded-lg bg-base-100 p-4">
                <slot />
                <ChatSidebar :open="chatOpen" @close="chatOpen = false" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import ChatSidebar from '@/components/Chat/ChatSidebar.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { useNotifications } from '@/composables/useNotifications';
import { toUrl } from '@/lib/utils';
import type { NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const page = usePage();
const { unreadCount } = useNotifications();
const chatOpen = ref(false);
const auth = computed(() => {
    return page.props.auth;
});
const rightNavItems: NavItem[] = [];

const userInitials = computed(() => {
    const firstName = auth.value?.user?.first_name;
    const lastName = auth.value?.user?.last_name;
    const email = auth.value?.user?.email;

    if (firstName && lastName) {
        return `${firstName.charAt(0)}${lastName.charAt(0)}`.toUpperCase();
    }
    if (firstName) return firstName.charAt(0).toUpperCase();
    if (lastName) return lastName.charAt(0).toUpperCase();
    if (email) return email.charAt(0).toUpperCase();

    return 'U';
});
</script>

<style scoped></style>

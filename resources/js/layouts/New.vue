<template>
<div class="max-w-none" style="box-shadow: 0 215px 0 -100px var(--color-neutral) inset;">
    <!-- Centered main content -->
    <div class=" container md:mx-auto px-3 md:px-0">
        <!-- Top nav bar -->
        <div class=" bg-neutral text-neutral-content py-3 flex justify-between">
            <!-- left Logo -->
            <Link :href="'/dashboard'" >
            <img src="../lib/assets/CCLogo.png" alt="Course Craft Logo" class="md:h-10 h-8"/>
            </Link>
            <div class="ml-auto flex items-center space-x-2">
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
                                        v-if="auth.user.avatar"
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
        <div class="rounded-lg bg-base-100 p-4 min-h-64">
        <slot />
        </div>
    </div>
</div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { toUrl } from '@/lib/utils';
import { Search } from 'lucide-vue-next';
import UserMenuContent from '@/components/UserMenuContent.vue';
import type { NavItem } from '@/types';

const page = usePage();

const auth = computed(()=> {
   return page.props.auth
})
const rightNavItems: NavItem[] = [

];

const userInitials = computed(() => {
    const firstName = auth.value.user?.first_name;
    const lastName = auth.value.user?.last_name;
    const email = auth.value.user?.email;
    
    if (firstName && lastName) {
        return `${firstName.charAt(0)}${lastName.charAt(0)}`.toUpperCase();
    }
    if (firstName) return firstName.charAt(0).toUpperCase();
    if (lastName) return lastName.charAt(0).toUpperCase();
    if (email) return email.charAt(0).toUpperCase();
    
    return 'U';
});
</script>

<style scoped>

</style>
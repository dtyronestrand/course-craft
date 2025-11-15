<template>
    <div class="flex h-screen">
        <!-- SideNavBar -->
        <aside class="w-64 flex-shrink-0 bg-base-300">
            <div class="flex h-full flex-col justify-between p-4">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-3 p-2">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Avatar of Dr. Eleanor Vance" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBOylM6K4GT6aAtpBVV3FZMnSv3UEZcEs1u6l_BbIekWmVu4wCyh4PlWirrsZAAYkd0MtIIvmLy7hUxdN08ea2F6lun2V9gHKwz-sIE72M_sqSPIpCyPJiwbM59yiVXFhFwPvdlusGNPhkPBniAWpLhsfFnzWsPRZhWtyE9DMwEbHE3p8jKJ8DwPMm2433nylB88FX6qaVhQQX7qKp941-NOP6XzEzucN3UByXGezLTWlGqJp5LvAbcXiNrQlJtt3u7CeFyPPxPYzas");'></div>
                        <div class="flex flex-col">
                            <h1 class="text-base-content text-base font-medium leading-normal">Dr. Eleanor Vance</h1>
                            <p class="text-base-content/70 text-sm font-normal leading-normal">Director, ID Team</p>
                        </div>
                    </div>
                    <nav class="flex flex-col gap-2 mt-4">
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/20 text-primary" href="#">
                            <span class="material-symbols-outlined">dashboard</span>
                            <p class="text-sm font-medium leading-normal">Dashboard</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 text-base-content/80 hover:bg-base-100/10 rounded-lg" href="#">
                            <span class="material-symbols-outlined">folder</span>
                            <p class="text-sm font-medium leading-normal">Projects</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 text-base-content/80 hover:bg-base-100/10 rounded-lg" href="#">
                            <span class="material-symbols-outlined">group</span>
                            <p class="text-sm font-medium leading-normal">Team</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 text-base-content/80 hover:bg-base-100/10 rounded-lg" href="#">
                            <span class="material-symbols-outlined">bar_chart</span>
                            <p class="text-sm font-medium leading-normal">Reports</p>
                        </a>
                        <a class="flex items-center gap-3 px-3 py-2 text-base-content/80 hover:bg-base-100/10 rounded-lg" href="#">
                            <span class="material-symbols-outlined">settings</span>
                            <p class="text-sm font-medium leading-normal">Settings</p>
                        </a>
                    </nav>
                </div>
                <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-primary-content text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                    <span class="truncate">New Project</span>
                </button>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 overflow-y-auto bg-base-100 p-8">
            <slot />
        </main>
    </div>
</template>

<script setup lang="ts">
</script>

<style scoped>
    .material-symbols-outlined {
      font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24
    }
</style>

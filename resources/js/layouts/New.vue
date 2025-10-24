<template>
<div class="prose max-w-none" style="box-shadow: 0 215px 0 -100px var(--color-neutral) inset;">
    <!-- Centered main content -->
    <div class=" container md:mx-auto px-3 md:px-0">
        <!-- Top nav bar -->
        <div class=" bg-neutral text-accent-content py-3 flex justify-between">
            <!-- left Logo -->
            <Link :href="'/dashboard'" >
            <img src="../lib/assets/CCLogo.png" alt="Slate Dev Logo" class="md:h-10 h-8"/>
            </Link>
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
</script>

<style scoped>

</style>
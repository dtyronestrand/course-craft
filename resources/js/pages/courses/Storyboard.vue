<template>
 <New>
    <BasicInfo :course="page.props.course as Course" />
    <Storyboard :numberOfModules="page.props.numberOfModules as number" :course="page.props.course as Course"/>
 </New>
</template>

<script setup lang="ts">
import BasicInfo from '@/components/Course/BasicInfo.vue';
import New from '@/layouts/New.vue';
import {Course} from "@/types";
import Storyboard from '@/components/Course/Storyboard.vue';
import {usePage} from "@inertiajs/vue3";

const page = usePage();
</script>

<style scoped>

</style>
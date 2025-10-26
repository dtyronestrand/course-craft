<template>
 <New>
    <BasicInfo :course="page.props.course as Course" />
    <Map :numberOfModules="page.props.numberOfModules as number" :course="page.props.course as Course"/>
 </New>
</template>

<script setup lang="ts">
import BasicInfo from '@/components/Course/BasicInfo.vue';
import New from '@/layouts/New.vue';
import {Course} from "@/types";
import Map from '@/components/Course/Map.vue';
import {usePage} from "@inertiajs/vue3";

const page = usePage();
</script>

<style scoped>

</style>
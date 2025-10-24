<template>
    <div v-if="!isEditing">
        <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Module {{ module.order_index }} {{ module.title  }} Overview</h3>
        <div class="p-4" v-html="module.items[index].itemable.content"></div>
        <div><h4 class="prose">Learning Objectives</h4>
        <ol class="list-none list-inside">
            <li v-for="(objective, objIndex) in module.module_objectives" :key="objIndex">
              {{ module.order_index  }}.{{ objective.number }}: {{ objective.objective }}
            </li>
        </ol>
        </div>
        <button class="btn btn-success mt-4" @click="isEditing=true">Edit Overview</button>
    </div>
<div v-else>
    <form @submit.prevent="updateOverview" class="border border-secondary ">
        <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Module {{ module.order_index }} {{ module.title  }} Overview</h3>
        <h4 class="p-4 text-2xl">Overview Content</h4>
        <TipTap v-model="form.content" />
           <button type="submit" class="btn btn-md btn-success text-success-content mt-4">Update Overview</button>
    </form>
    </div>
</template>

<script setup lang="ts">
import { CourseModule } from "@/types";
import { ref } from "vue";
import {useForm} from "@inertiajs/vue3";
import TipTap from "@/components/TipTap.vue";
const props = defineProps<{
    module: CourseModule;
    index: number;
}>();

const isEditing = ref(false);


const form = useForm({
    content: props.module.items[props.index].itemable.content
});

const updateOverview = () => {
    form.put(`/module_overviews/${props.module.items[props.index].itemable_id}`, {
        onSuccess: () => {
            isEditing.value = false;
        }
    });
};
</script>

<style scoped>

</style>
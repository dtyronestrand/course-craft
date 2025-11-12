<template>
    {{ module.items[index].itemable_type }}
    <div class="max-w-none prose mt-4 border" v-if="!isEditing">
    <div class="flex flex-row justify-between items-center text-xl p-4 w-full border-b border-secondary bg-primary ">
        <h4 >Module {{ module.order_index }} {{ module.title  }} Overview</h4>
    <div class="flex flex-row gap-4">
              <EditButton background="success" @click="isEditing = true" />
       <DeleteButton @click="deleteOverview" />
    </div>
        </div>
        <div class="p-4" v-html="module.items[index].itemable.content"></div>
        <div class="max-w-none prose"><h5 class="text-lg w-full p-4 bg-neutral border-b border-t">Learning Objectives</h5>
        <ol class="list-none list-inside">
            <li v-for="(objective, objIndex) in module.module_objectives" :key="objIndex">
              {{ module.order_index  }}.{{ objective.number }}: {{ objective.objective }}
            </li>
        </ol>
        </div>
      
    </div>
<div v-else>
    <form @submit.prevent="updateOverview" class="border border-secondary ">
        <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Module {{ module.order_index }} {{ module.title  }} Overview</h3>
        <h4 class="p-4 text-2xl">Overview Content</h4>
        <TipTap v-model="form.content" />
        <div class="flex flex-row gap-4 p-4">
           <UpdateButton :itemType="props.module.items[index].itemable_type"/>
           <CancelButton @click="isEditing=false"/>
        </div>
    </form>
    </div>
</template>

<script setup lang="ts">
import { CourseModule } from "@/types";
import { ref } from "vue";
import {useForm, router} from "@inertiajs/vue3";
import TipTap from "@/components/TipTap.vue";
import EditButton from "@/components/EditButton.vue";
import DeleteButton from "@/components/DeleteButton.vue";
import UpdateButton from "@/components/Course/Module/Items/Buttons/UpdateButton.vue";
import CancelButton from "../Buttons/CancelButton.vue";
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

const deleteOverview = ()=> {
    router.delete(`/module_overviews/${props.module.items[props.index].itemable_id}`, {
        onSuccess: () => {
         router.reload();
        }
    });
}
</script>

<style scoped>

</style>
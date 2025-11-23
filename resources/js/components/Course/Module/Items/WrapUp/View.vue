<template>
    <div class="max-w-none prose mt-4 border" v-if="!isEditing">
    <details>
    <summary class="flex flex-row justify-between items-center text-xl p-4 w-full border-b border-secondary bg-primary ">
        <h4 class="text-primary-content" >Module {{ module.order_index }} {{ module.title  }} Wrap Up</h4>
       <div class="flex flex-row gap-4">
              <EditButton background="success" @click="isEditing = true" />
       <DeleteButton @click="deleteWrapUp" />
    </div>
    </summary>
        
        <div class="p-4" v-html="module.items[index].itemable.content"></div>
      
    </details>
      
    </div>
<div v-else>
    <form @submit.prevent="updateWrapUp" class="border border-secondary ">
        <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Module {{ module.order_index }} {{ module.title  }} Wrap Up</h3>
        <h4 class="p-4 text-2xl">Wrap Up Content</h4>
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
import {router, useForm} from "@inertiajs/vue3";
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

const updateWrapUp = () => {
    form.put(`/module_wrapUps/${props.module.items[props.index].itemable_id}`, {
        onSuccess: () => {
            isEditing.value = false;
        }
    });
};
const deleteWrapUp = ()=> {
    form.get(`/module_wrapUps/${props.module.items[props.index].itemable_id}/delete`, {
        onSuccess: () => {
         router.reload();
        }
    });
}
</script>

<style scoped>

</style>
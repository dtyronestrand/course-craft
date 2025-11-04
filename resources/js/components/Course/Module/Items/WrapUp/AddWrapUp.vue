<template>
    <form @submit.prevent="wrapUpSubmit" class="border my-8 border-secondary ">
        <h3 class="text-3xl p-4 border-b border-secondary bg-primary">Module {{ props.module.order_index }} {{ props.module.title  }} Wrap Up</h3>
        <h4 class="p-4 text-2xl">Wrap Up Content</h4>
        <TipTap v-model="wrapUpData.content" />
  <div class="p-4 flex flex-row gap-4">
           <input type="hidden" name="module" :value="props.module.id" />
           <button type="submit" class="btn btn-md btn-success text-success-content mt-4">Save Item</button>
           <button @click.prevent="emit('close')" class="btn btn-md btn-error text-error-content mt-4">Cancel</button>
    </div>
    </form>
</template>

<script setup lang="ts">
import { CourseModule } from '@/types';
import {useForm} from '@inertiajs/vue3';
import { ref } from 'vue';
import TipTap from '@/components/TipTap.vue';
interface Props {
    module: CourseModule;
}
const props = defineProps<Props>();
const wrapUpData = ref({
    content: '',
})
const form = useForm({
    content: wrapUpData.value.content,
    module: props.module.id,
});

const emit = defineEmits<{
    (e: 'close'): void;

}>();

const wrapUpSubmit = () => {
    form.content = wrapUpData.value.content;
    form.post('/module_wrapUps', {
        onSuccess: () => {
            emit('close');
        }
    });
};
</script>

<style scoped>

</style>
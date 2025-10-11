<template>
    <div v-if="selectedObjectivesList.length" class=" mb-4">
        <ul class="list-disc list-inside">
            <li v-for="(obj, index) in selectedObjectivesList" :key="index">
                {{ obj }}
            </li>
        </ul>
    </div>
    <details ref="dropdownRef" class="dropdown  cursor-pointer relative mb-4">
    <summary  class="selected-items "  >
   Select Module Objectives that align with this item.
    </summary>
    <div class="options-wrapper glass menu dropdown-content bg-base-200 rounded-box z-1 w-52 p-2 shadow-sm">
    <div class="option hover:bg-primary p-4  border border-neutral box-border last:rounded-bl-lg last:rounded-br-lg" v-for="(objective, index) in props.objectives" :key="index">
        <input type="checkbox" :value="objective.number" v-model="selectedObjectives" class="checkbox checkbox-primary mr-2">
        <span>{{ objective.number }}: {{ objective.objective }}</span>
         
    </div>
             <button
                class="btn btn-success mt-2 w-full"
                type="button"
                @click="closeDropdown"
            >
                Done
            </button>
    </div>
    </details>
</template>

<script setup lang="ts">
import {ref, computed, watch} from 'vue';
import {ModuleObjective} from "@/types";
const props = defineProps<{
    objectives: ModuleObjective[];
    modelValue?: string[];
}>();

const selectedObjectives = ref<string[]>(props.modelValue || []);

const emit = defineEmits<{
    'update:modelValue': [objectives: string[]]
}>();

const selectedObjectivesList = computed(() => {
    return selectedObjectives.value;
});

watch(selectedObjectives, (newValue) => {
    emit('update:modelValue', newValue);
}, { deep: true });

const dropdownRef = ref<HTMLElement | null>(null);
function closeDropdown(){
    if (dropdownRef.value) {
        (dropdownRef.value as HTMLDetailsElement).open = false;
    }
}
</script>

<style scoped>

</style>
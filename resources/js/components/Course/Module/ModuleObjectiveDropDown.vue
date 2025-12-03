<template>
    <div v-if="selectedObjectivesList.length" class="mb-4">
        <ul class="list-inside list-disc">
            <li v-for="(obj, index) in selectedObjectivesList" :key="index">
                {{ obj }}
            </li>
        </ul>
    </div>
    <details ref="dropdownRef" class="dropdown relative mb-4 cursor-pointer">
        <summary class="selected-items">
            Select Module Objectives that align with this item.
        </summary>
        <div
            class="options-wrapper dropdown-content menu z-1 w-52 rounded-box glass bg-base-200 p-2 shadow-sm"
        >
            <div
                class="option box-border border border-neutral p-4 last:rounded-br-lg last:rounded-bl-lg hover:bg-primary"
                v-for="(objective, index) in props.objectives"
                :key="index"
            >
                <input
                    type="checkbox"
                    :value="objective.number"
                    v-model="selectedObjectives"
                    class="checkbox mr-2 checkbox-primary"
                />
                <span>{{ objective.number }}: {{ objective.objective }}</span>
            </div>
            <button
                class="btn mt-2 w-full btn-success"
                type="button"
                @click="closeDropdown"
            >
                Done
            </button>
        </div>
    </details>
</template>

<script setup lang="ts">
import { ModuleObjective } from '@/types';
import { computed, ref, watch } from 'vue';
const props = defineProps<{
    objectives: ModuleObjective[];
    modelValue?: string[];
}>();

const selectedObjectives = ref<string[]>(props.modelValue || []);

const emit = defineEmits<{
    'update:modelValue': [objectives: string[]];
}>();

const selectedObjectivesList = computed(() => {
    return selectedObjectives.value;
});

watch(
    selectedObjectives,
    (newValue) => {
        emit('update:modelValue', newValue);
    },
    { deep: true },
);

const dropdownRef = ref<HTMLElement | null>(null);
function closeDropdown() {
    if (dropdownRef.value) {
        (dropdownRef.value as HTMLDetailsElement).open = false;
    }
}
</script>

<style scoped></style>

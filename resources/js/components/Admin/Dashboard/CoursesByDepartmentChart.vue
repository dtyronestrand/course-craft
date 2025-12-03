<template>
    <Bar id="courses-by-department" :data="chartData" :options="chartOptions" />
</template>
<script setup lang="ts">
import type { ChartData } from 'chart.js';
import {
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    Title,
    Tooltip,
} from 'chart.js';
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
);

interface Props {
    coursesByDepartment: Record<string, number>;
}

const props = defineProps<Props>();
const chartData = computed<ChartData<'bar'>>(() => {
    return {
        labels: Object.keys(props.coursesByDepartment).map((key) =>
            key.toUpperCase(),
        ),
        datasets: [
            {
                axis: 'y',
                label: 'Number of Courses',
                backgroundColor: ['yellow', 'green', 'red'],
                data: Object.values(props.coursesByDepartment),
            },
        ],
    };
});

const chartOptions = {
    indexAxis: 'y' as const,
    plugins: {
        legend: {
            display: false,
        },
    },
};
</script>

<style scoped></style>

<template>
    
        <Bar id="course-status" :data="chartData" :options="chartOptions" />

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
    courseStatusCounts: Record<string, number>;
}

const props = defineProps<Props>();
const chartData = computed<ChartData<'bar'>>(() => {
    return {
        labels: Object.keys(props.courseStatusCounts).map((key) =>
            key.toUpperCase(),
        ),
        datasets: [
            {
                label: 'Number of Courses',
                backgroundColor: ['yellow', 'green', 'red'],
                data: Object.values(props.courseStatusCounts),
            },
        ],
    };
});

const chartOptions = {
    plugins: {
        legend: {
            display: false,
        },
    },
};
</script>

<style scoped></style>

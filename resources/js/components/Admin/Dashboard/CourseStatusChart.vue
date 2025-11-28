<template>
    <div>
        <Bar id="course-status" :data="chartData" :options="chartOptions"/>
    </div>
</template>

<script setup lang="ts">
import {Bar} from 'vue-chartjs';
import {Chart as ChartJS, Title, Tooltip, Legend,BarElement, CategoryScale, LinearScale} from 'chart.js';
import {computed} from 'vue';
import type {ChartData} from 'chart.js';
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

interface Props {
    courseStatusCounts: Record<string, number>;

}

const props = defineProps<Props>();
const chartData = computed<ChartData<"bar">>(() => {
    return {
        labels: Object.keys(props.courseStatusCounts).map(key => key.toUpperCase()),
        datasets: [
            {
                label: 'Number of Courses',
                backgroundColor: 'yellow',
                data: Object.values(props.courseStatusCounts),
            },
        ],
    };
});

const chartOptions = {
    plugins:{
        legend: {
            display: false
        }
    }
}


</script>

<style scoped>

</style>
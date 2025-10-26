    <template>
    <div class="p-12   bg-base-100 text-base-content">
    <div class="bg-primary rounded-t-lg border p-8 ">
<h1 class="text-7xl text-base-content mb-4"> {{ props.course.prefix }} {{ props.course.number }} </h1>
<h2 class="text-5xl mb-4 text-base-content ">{{props.course.title }}</h2>
 <div class="bg-base-200 border  p-8">
 <div v-for="(user, index) in props.course.users" :key="index">
 <p>{{ user.pivot?.role }}: {{ user.first_name }} {{ user.last_name  }}</p>
 </div>
 <h3 class="text-3xl my-4">Learning Objectives:</h3>
 <div v-for="(objective,index) in props.course.objectives" :key="index">
 <p class="text-lg">{{ objective.number }}. {{ objective.objective }}</p>
    </div>
    </div>
    </div>
    </div>
    </template>
<script setup lang="ts">
import {Course} from '@/types';

interface Props {
    course: Course;
}
const props = defineProps<Props>();
</script>
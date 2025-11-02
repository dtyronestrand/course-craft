<template>
    <New>
    <div class="p-12   bg-base-100 text-base-content">
    <div class="bg-primary rounded-t-lg border p-8 ">
<h1 class="text-7xl text-base-content mb-4"> {{ page.props.course.prefix }} {{ page.props.course.number }} </h1>
<h2 class="text-5xl mb-4 text-base-content ">{{ page.props.course.title }}</h2>
<div class="flex flex-row gap-4 mb-4 ">
<Map @changeDisplay="handleDisplay($event)"/>
<Storyboard @changeDisplay="handleDisplay($event)"/>

<Delete @delete="handleDelete"/>
</div>
    </div>
    <div class="bg-base-200 border  p-8">
 <div v-for="(user, index) in page.props.course.users" :key="index">
 <p>{{ user.pivot.role }}: {{ user.first_name }} {{ user.last_name  }}</p>
 </div>
 <h3 class="text-3xl my-4">Learning Objectives:</h3>
 <div v-for="(objective,index) in page.props.course.objectives" :key="index">
 <p class="text-lg">{{ objective.number }}. {{ objective.objective }}</p>
    </div>
    </div>
    </div>
    <div>
    <component :is="currentDisplay" :numberOfModules="page.props.numberOfModules" :course="page.props.course" v-if="currentDisplay"/>
    <div v-if="page.props.auth.user.is_google_connected" >
<button @click="exportToDrive" :disabled="isExporting" class="btn btn-info text-info-content px-4 py-2 disabled:opacity-50"><span v-if="isExporting">Creating Document...</span><span v-else>Export to Drive</span></button>
<div v-if="exportError" class="mt-2 text-sm text-error">Error: {{ exportError }}</div>
<div v-if="exportSuccessUrl" class="mt-2 text-sm text-success">Successfully exported! <a :href="exportSuccessUrl" target="_blank" class="underline">Open Document</a></div> 
</div>
<div v-else>
<p class="mb-2 text-sm text-base-content">Connect your Google Drive to export.</p>
<a href="/google/redirect" class="inline-flex items-center px-4 py-2 bg-info text-info-content">Connect Google Drive</a>
</div>
    </div>
    </New>
</template>

<script setup lang="ts">
import { usePage , router} from '@inertiajs/vue3';
import {Storyboard, Map, Delete} from '@/components/CourseActions'

import { shallowRef , defineAsyncComponent, nextTick, ref, Component} from 'vue';
import New from '@/layouts/New.vue';
interface Course {
    id: number;
    prefix: string;
    number: string | number;
    title: string;
    document_id: string | null;
    objectives: { number: string; objective: string; }[];
    users: { id: number; first_name: string; last_name: string; pivot: { role: string; } }[];
    course_modules: any[];
}

interface PageProps {
    [key: string]: unknown;
    name: string;
    quote: { message: string; author: string; };
    auth: any;
    sidebarOpen: boolean;
    course: Course;
    numberOfModules: number;
}

const currentDisplay = shallowRef<Component | null>(null);
const page = usePage<PageProps>();

const isExporting = ref(false);
const exportError = ref<string | null>(null);
const exportSuccessUrl = ref<string | null>(null);
const documentTitle = ref(`${page.props.course.prefix} ${page.props.course.number} - ${page.props.course.title}`);
const documentContent = ref(`${page.props.course.objectives.map(obj => obj.objective).join('\n')}`);
const MapComponent = defineAsyncComponent(() => import('@/components/Course/Map.vue'));
const StoryboardComponent = defineAsyncComponent(() => import('@/components/Course/Storyboard.vue'));

const handleDisplay = async (display: string) => {
    currentDisplay.value = null;
    await nextTick();
    
    if (display === 'map'){
        currentDisplay.value = MapComponent;
    } else if (display === 'storyboard'){
        currentDisplay.value = StoryboardComponent;
    }
};
const handleDelete = () => {
    if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
router.delete(`/courses/${page.props.course.id}`);
    }
};

async function exportToDrive() {
  isExporting.value = true;
  exportError.value = null;
  exportSuccessUrl.value = null;

  const meta = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = meta?.getAttribute('content') ?? '';
  if (!csrfToken) {
    exportError.value = 'CSRF token not found.';
    isExporting.value = false;
    return;
  }

  try {
    let response;
    if (page.props.course.document_id){
      response = await fetch('/export/google-doc/update', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          title: documentTitle.value,
          content: documentContent.value,
          document_id: page.props.course.document_id
        })
      });
    } else {
      response = await fetch('/export/google-doc', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          title: documentTitle.value,
          content: documentContent.value,
        })
      });
    }
  
    const data = await response.json();

    // Handle errors
    if (!response.ok) {

      // --- THIS IS THE SELF-HEALING LOGIC ---
      if (response.status === 401) {
        // 1. Show the error message
        exportError.value = data.error || 'Your connection expired. Please reconnect.';

        // 2. Tell Inertia to reload *only* the 'auth' prop
        //    The backend deleted the token, so the new prop
        //    will be `is_google_connected: false`
        router.reload({ 
            only: ['auth']
        });

      } else {
        // It's a different error (like a 500)
        throw new Error(data.error || `HTTP error! status: ${response.status}`);
      }
      // --- END OF SELF-HEALING LOGIC ---

    } else {
      // Handle Success
      console.log('File created:', data.url);
      exportSuccessUrl.value = data.url;
    }

  } catch (error) {
    // Handle any network or parsing error
    console.error('Export failed:', error);
    exportError.value = error instanceof Error ? error.message : 'An unknown error occurred';

  } finally {
    // Reset loading state
    isExporting.value = false;
  }
}
</script>

<style scoped>

</style>
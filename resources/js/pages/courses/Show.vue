<template>
    <New>
    <div class="p-12   bg-base-100 text-base-content">
    <div class="bg-base-200 rounded-t-lg border p-8 ">
<h1 class="text-7xl text-base-content mb-4"> {{ page.props.course.prefix }} {{ page.props.course.number }} </h1>
<h2 class="text-5xl mb-4 text-base-content ">{{ page.props.course.title }}</h2>
<div class="flex flex-row gap-4 mb-4 ">
<Map @changeDisplay="handleDisplay($event)"/>
<Storyboard @changeDisplay="handleDisplay($event)"/>

<Delete @delete="handleDelete"/>
</div>
<h3>Deliverables</h3>
<table class="table-auto w-full mb-4">
    <thead>
        <tr>
            <th class="text-left p-2">Deliverable</th>
            <th class="text-left p-2">Due Date</th>
            <th class="text-left p-2">Status</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(deliverable, index) in page.props.course.deliverables" :key="index">
            <td class="p-2">{{ deliverable.name }}</td>
            <td class="p-2">{{ deliverable.pivot.due_date ? new Date(deliverable.pivot.due_date).toLocaleDateString() : 'N/A' }}</td>
            <td class="p-2"><input type="checkbox" v-model="deliverable.pivot.is_done" @change="updateDeliverableStatus(deliverable)"/></td>
        </tr>
    </tbody>
</table>
    </div>
    <div class="bg-base-300 border  p-8">
    {{ page.props.course.deliverables }}
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
    <div v-if="page.props.auth.user?.hasGoogleAccess" >
<button @click="exportToDrive" :disabled="isExporting" class="bg-info text-info-content appearance-none border-[0.125em] border-secondary rounded-[0.9375em] box-border cursor-pointer inline-block font-bold m-0 min-height-[3.75em] min-width-0 outline-none py-[0.25em] px-[1.5em] text-center decoration-none transition-[all duration-300 cubic-bezier(.23,1, 0.32,1)] user-select-none touch-manipulation will-change-transform disabled:pointer-events-none hover:text-secondary-content hover:bg-secondary hover:shadow-[rgba(0,0,0,0.25) 0 8px 15px] -translate-y-0.5 active:shadow-none active:translate-y-0" ><span v-if="isExporting">Creating Document...</span><span v-else>Export to Google Drive</span></button>
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

import { shallowRef , defineAsyncComponent, nextTick, ref} from 'vue';
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
    deliverables: { id: number; name: string; pivot: { due_date: string | null; is_done: boolean; date_completed: string | null; } }[];
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

const currentDisplay = shallowRef<any>(null);
const page = usePage<PageProps>();

const isExporting = ref(false);
const exportError = ref<string | null>(null);
const exportSuccessUrl = ref<string | null>(null);
const documentTitle = ref(`${page.props.course.prefix} ${page.props.course.number} - ${page.props.course.title}`);
const documentContent = ref(`${page.props.course.objectives.map(obj => obj.objective).join('\n')}`);
const MapComponent = defineAsyncComponent(() => import('@/components/Course/Map.vue'));
const StoryboardComponent = defineAsyncComponent(() => import('@/components/Course/StoryboardComponent.vue'));

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
         course_id: page.props.course_id,
         title: documentTitle.value,
         content: documentContent.value,
         document_id: page.props.course.document_id,
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
       course_id: page.props.course.id,
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

const updateDeliverableStatus = (deliverable: any) => {
    router.put(`/courses/${page.props.course.id}/deliverables/${deliverable.id}`, {
        is_done: deliverable.pivot.is_done,
    }, {
        preserveScroll: true,
        only: ['course'],
    });
};
</script>

<style scoped>

</style>
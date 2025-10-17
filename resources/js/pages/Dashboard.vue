<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import CoursesTable from '@/components/ CoursesTable.vue';
import {usePage, router} from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import CreateCourse from '@/components/CreateCourse.vue';

const isCreateCourseModalOpen = ref(false);

const saveCourse = (courseData: any) => {
    // Handle course creation logic here
    router.post('/courses', courseData, {
        onFinish: () => {
           router.reload();
        },
    });
    isCreateCourseModalOpen.value = false;
};

const page = usePage();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

onMounted(() => {
    const fabElement = document.getElementById("floating-snap-btn-wrapper");
    if (!fabElement) return;
    
    let oldPositionX: string, oldPositionY: string;

    const move = (e: MouseEvent | TouchEvent) => {
        if (!fabElement.classList.contains("fab-active")) {
            if (e.type === "touchmove") {
                const touchEvent = e as TouchEvent;
                fabElement.style.top = (touchEvent.touches[0].clientY - 37.5) + "px";
                fabElement.style.left = (touchEvent.touches[0].clientX - 36.5) + "px";
            } else {
                const mouseEvent = e as MouseEvent;
                fabElement.style.top = (mouseEvent.clientY - 37.5) + "px";
                fabElement.style.left = (mouseEvent.clientX - 36.5) + "px";
            }
        }
    };

    const mouseDown = (e: MouseEvent | TouchEvent) => {
        oldPositionY = fabElement.style.top;
        oldPositionX = fabElement.style.left;
        if (e.type === "mousedown") {
            window.addEventListener("mousemove", move);
        } else {
            window.addEventListener("touchmove", move);
        }
        fabElement.style.transition = "none";
    };

    const mouseUp = (e: MouseEvent | TouchEvent) => {
        if (e.type === "mouseup") {
            window.removeEventListener("mousemove", move);
        } else {
            window.removeEventListener("touchmove", move);
        }
        snapToSide(e);
        fabElement.style.transition = "0.3s ease-in-out left";
    };

    const snapToSide = (e: MouseEvent | TouchEvent) => {
        const wrapperElement = document.getElementById('main-wrapper');
        const windowWidth = window.innerWidth;
        let currPositionX: number, currPositionY: number;
        
        if (e.type === "touchend") {
            const touchEvent = e as TouchEvent;
            currPositionX = touchEvent.changedTouches[0].clientX;
            currPositionY = touchEvent.changedTouches[0].clientY;
        } else {
            const mouseEvent = e as MouseEvent;
            currPositionX = mouseEvent.clientX;
            currPositionY = mouseEvent.clientY;
        }
        
        if (currPositionY < 75) {
            fabElement.style.top = "20px";
        }
        if (wrapperElement && currPositionY > wrapperElement.clientHeight - 75) {
            fabElement.style.top = (wrapperElement.clientHeight - 95) + "px";
        }
        if (currPositionX < windowWidth / 2) {
            fabElement.style.left = "20px";
            fabElement.classList.remove('right');
            fabElement.classList.add('left');
        } else {
            fabElement.style.left = (windowWidth - 93) + "px";
            fabElement.classList.remove('left');
            fabElement.classList.add('right');
        }
    };

    fabElement.addEventListener("mousedown", mouseDown);
    fabElement.addEventListener("mouseup", mouseUp);
    fabElement.addEventListener("touchstart", mouseDown);
    fabElement.addEventListener("touchend", mouseUp);
    fabElement.addEventListener("click", (e) => {
        if (
            oldPositionY === fabElement.style.top &&
            oldPositionX === fabElement.style.left
        ) {
            fabElement.classList.toggle("fab-active");
        }
    });
});


</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
       
         
             <CoursesTable :courses="page.props.courses as any[]" />
                </div>

<div id="floating-snap-btn-wrapper" class="absolute top-[40%] left-[30px] w-[73px] h-[75px] rounded-[50%] transform-[translate(-50%, -50%)]">
<div role="button" @click="isCreateCourseModalOpen = !isCreateCourseModalOpen" class="glass absolute top-0 left-0 flex justify-center items-center w-full h-full rounded-[50%] bg-primary  text-primary-content z-[1000] shadow-[0px 2px 17px -1px rgba(0,0,0,0.3)]">
<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg> 
</div>
</div>
    </AppLayout>
    <CreateCourse v-if="isCreateCourseModalOpen" @create-course="saveCourse" @close="isCreateCourseModalOpen = false" />
</template>
<style scoped>

</style>
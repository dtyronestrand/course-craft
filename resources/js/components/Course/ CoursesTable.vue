<template>
    <div class="flex-1 p-[0.5rem] rounded-lg glass overflow-y-auto">
    <div class="w-full overflow-x-auto mb-[20px] rounded-lg shadow-[0 2px 4px rgba(0, 0, 0, 0.1)]">
    <table class="min-w-[600px] w-full border-collapse">
    <thead>
    <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
    <th v-for="header in headerGroup.headers" :key="header.id" scope="col">
      <FlexRender :render="header.column.columnDef.header" :props=" header.getContext()" />
    </th>
    </tr>
    
    </thead>
    <tbody>
        <tr v-for="row in table.getRowModel().rows" :key="row.id" >
            <td v-for="cell in row.getVisibleCells()" :key="cell.id" class="p-[10px] border-b border-accent text-center">
                <FlexRender :render="cell.column.columnDef.cell" :props=" cell.getContext()" />
            </td>
        </tr>
    </tbody>
    </table>
    </div>

    </div>
</template>

<script setup lang="ts">
import {
    useVueTable,
    FlexRender,
    getCoreRowModel,
} from '@tanstack/vue-table'
import { ref , h} from 'vue'
import CourseActionButtons from '../CourseActions/CourseActionButtons.vue'
interface Props {
    courses: any[]
}
const props = defineProps<Props>()    
const columnsCourses = [
    {
        accessorKey: 'prefix',
        header: 'Prefix',
    },
    {
        accessorKey: 'number',
        header: 'Number',
    },
    {
        accessorKey: 'title',
        header: 'Title'
    },
        {
        accessorKey: 'pivot.role',
        header: 'Role'
    },
    {
        accessorKey: 'actions',
        header:'',
        cell: ({ row }: { row: any }) => h(CourseActionButtons, { id: row.original.id }),
    },

]
const data = ref(props.courses)

const table = useVueTable({
    data: data.value,
   columns: columnsCourses,
   getCoreRowModel: getCoreRowModel(),
})


</script>

<style scoped>
th,td{
    padding: 12px 15px;
    white-space: nowrap;
    text-align: left;
    border-bottom: 1px solid var(--color-secondary);
}
th{
   background-color:  oklch(from var(--color-primary) l c h / 0.55);
   backdrop-filter: blur(20px);
   border: 1px solid oklch(from var(--color-accent) l c h /0.1);
   box-shadow: 0 8px 32px 0 oklch(from var(--color-accent) l c h /0.3);
    color: var(--color-primary-content);
    font-weight: 600;
    position: relative;
    cursor: pointer;
    user-select: none;
    position: sticky;
    top: 0;

}

th i {
    margin-left:0.5rem;
    opacity: 0.7;
}

th:hover {
    background: var(--color-neutral);
    color: var(--color-neutral-content);
}

tr:nth-child(even){
    background: var(--color-base-200);
    color: var(--color-base-content);

}
tr:hover{
    background: var(--color-primary);
    color: var(--color-primary-content);
}
</style>
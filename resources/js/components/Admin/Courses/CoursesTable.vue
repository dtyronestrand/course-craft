<template>
 <div class="flex-1 p-2 rounded-lg glass overflow-y-auto">
    <div class="w-full overflow-x-auto mb-5 rounded-lg shadow-[0 2px 4px rgba(0, 0, 0, 0.1)]">
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
            <td v-for="cell in row.getVisibleCells()" :key="cell.id" class="p-2.5 border-b border-accent text-center">
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
import type { Course } from '@/types';
interface Props {
    courses: Course[];
}
const props = defineProps<Props>()
const data = ref(props.courses)

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
        accessorKey: 'team',
        header: 'Team',
        cell: ({row}: any) => {
            const users = row.original.users;
            if(!users || users.length === 0){
                return h('span', 'No Team Assigned');
            }
            return h('ul', users.map((user: any) => h('li', {key: user.id}, `${user.first_name} ${user.last_name}: ${user.pivot.role}`)));
        }
    },
]

const table = useVueTable({
    get data() {
        return data.value;
    },
    columns: columnsCourses,
    getCoreRowModel: getCoreRowModel(),
})
</script>

<style scoped>

</style>
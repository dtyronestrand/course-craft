<template>
    <table class="w-full text-left text-sm">
<thead>
<tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id" class="text-xs text-primary uppercase border-b border-primary/20">
<th v-for="header in headerGroup.headers" :key="header.id" scope="col" class="px-4 py-3">
<FlexRender :render="header.column.columnDef.header" :props="header.getContext()"/>
</th>
</tr>
</thead>
<tbody>
<tr v-for="row in table.getRowModel().rows" :key="row.id" class="border-b border-base-300">
<td v-for="cell in row.getVisibleCells()" :key="cell.id" class="px-4 py-3">
<FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()"/>
</td>
</tr>
</tbody>
    </table>
</template>

<script setup lang="ts">
import {useVueTable, FlexRender, getCoreRowModel, createColumnHelper} from '@tanstack/vue-table';
import { ref , h} from 'vue'

interface Props {
    courses: any[]
}

const props = defineProps<Props>()    
const data = ref(props.courses)

const  columnHelper = createColumnHelper<any>()

const defaultColumns = [
    columnHelper.group({
        header: 'Course',
        footer: props => props.column.id,
        columns: [
            columnHelper.accessor('prefix', {
                cell: info => info.getValue(),
                footer: props => props.column.id,
            }),
            columnHelper.accessor(row => row.number, {
                id: 'number',
                cell: info => info.getValue(),
                header: () => h('span', 'Number'),
                footer: props => props.column.id,
            }),
        ],
    }),
   columnHelper.group({
    header: ' ',
    columns: [
    columnHelper.accessor( 'status', {
        id: 'status',
        header: () => h('span', 'Status'),
        cell: (info)  => {
        const status = info.getValue();
        const style = status === 'pending' 
            ? 'bg-info/20 border border-info shadow-md shadow-info/20 px-3 py-1 rounded-full text-center text-info  font-bold inline-block'
            : status === 'completed'
            ? 'bg-success px-3 py-1 rounded-full text-center text-success-content inline-block'
            : 'bg-warning/20 border border-warning shadow-md shadow-warning/20 px-3 py-1 rounded-full text-center text-warning font-bold inline-block';
        return h('span', {class:style}, status.charAt(0).toUpperCase() + status.slice(1));
    }
}),

        
]
    }),

    ]

const table = useVueTable({
    data: data.value,
    columns: defaultColumns,
    getCoreRowModel: getCoreRowModel(),
})

</script>

<style scoped>

</style>
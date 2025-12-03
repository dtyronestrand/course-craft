<template>
 <div class="flex-1 p-2 rounded-lg glass-effect border border-primary/10 shadow-lg shadow-primary/10 overflow-y-auto">
    <div class="w-full overflow-x-auto mb-5 rounded-lg shadow-[0 2px 4px rgba(0, 0, 0, 0.1)]">
    <table class="w-full">
    <thead >
    <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
    <th v-for="header in headerGroup.headers" :key="header.id" :colSpan="header.colSpan" :class="header.column.getCanSort() ? 'cursor-pointer select-none' : ''" @click="header.column.getToggleSortingHandler()?.($event)" scope="col">
        <template v-if="!header.isPlaceholder">
              <FlexRender
                :render="header.column.columnDef.header"
                :props="header.getContext()"
              />

              {{
                { asc: ' 🔼', desc: ' 🔽' }[
                  header.column.getIsSorted() as string
                ]
              }}
            </template>
    </th>
    </tr>
    
    </thead>
    <tbody>
        <tr v-for="row in table.getRowModel().rows" :key="row.id" >
            <td v-for="cell in row.getVisibleCells()" :key="cell.id">
                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
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
    SortingState,
    getSortedRowModel,
    createColumnHelper,
} from '@tanstack/vue-table'
import {h, computed, ref} from 'vue'
import type { Course } from '@/types';

interface Props {
    courses: Course[];
}
const props = defineProps<Props>()

const allRoles = computed(() => {
    const roles = new Set<string>();
    props.courses.forEach(course => {
        course.users.forEach(user => {
            if (user.pivot?.role) roles.add(user.pivot.role);
        });
    });
    return Array.from(roles);
});

const data = computed(() => props.courses.map(course => {
    const transformed: any = { ...course };
    course.users.forEach(user => {
        if (user.pivot?.role) {
            const role = user.pivot.role;
            const name = `${user.first_name} ${user.last_name}`;
            transformed[role] = transformed[role] ? `${transformed[role]}, ${name}` : name;
        }
    });
    return transformed;
}))

const columnHelper = createColumnHelper<any>();
const columnsCourses = computed(() => [
    columnHelper.group({
        header: ' Course',
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
                enableSorting: false,
                footer: props => props.column.id,
            }),
            columnHelper.accessor(row => row.title, {
                id: 'title',
                cell: info => info.getValue(),
                header: () => h('span', 'Title'),
                enableSorting: false,
                footer: props => props.column.id,
            }),
        ],
    }),
        columnHelper.group({
               header: 'Team',
               columns: allRoles.value.map(role => 
                columnHelper.accessor(role, {
                    cell: info => info.getValue() || '-',
                    header: () => h('span', role),
                    footer: props => props.column.id,
                  
                })
               )
        }),
])
const sorting = ref<SortingState>([]);
const table = useVueTable({
    get data() {
        return data.value;
    },
    get columns() {
        return columnsCourses.value;
    },
    state: {
        get sorting() {
            return sorting.value
        },
    },
    onSortingChange: updateOrValue => {
        sorting.value = typeof updateOrValue === 'function' ? updateOrValue(sorting.value) : updateOrValue
    },
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
})
</script>

<style scoped>
table {
    border: 1px solid rgba( from var(--color-primary) R G B / 0.5);
 
}
tbody {
    border-bottom: 1px solid rgba( from var(--color-primary) R G B / 0.2);
}

th,td{
    border-bottom: 1px solid rgba( from var(--color-primary) R G B / 0.2);
    border-right: 1px solid rgba( from var(--color-primary) R G B / 0.2);
    padding: 2px 4px;
}
th:last-child, td:last-child{
    border-right: none;
    
}
tr:last-child td {
    border-bottom: 1px solid rgba( from var(--color-primary) R G B / 0.5);
}

th {
    padding: 8px;
}
</style>
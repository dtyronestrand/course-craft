<template>
    <div class="flex-rom flex items-center gap-4">
        <DebouncedInput
            :modelValue="globalFilter ?? ''"
            @update:modelValue="(value: string) => (globalFilter = value)"
            placeholder="Search all columns..."
            class="w-w-max frosted-backdrop my-4 rounded border border-primary/10 bg-primary/10 px-4 py-2 text-primary shadow-sm shadow-primary"
        />
    </div>
    <table class="w-full text-left text-sm">
        <thead>
            <tr
                v-for="headerGroup in table.getHeaderGroups()"
                :key="headerGroup.id"
                class="border-b border-primary/20 text-xs text-primary uppercase"
            >
                <th
                    v-for="header in headerGroup.headers"
                    :key="header.id"
                    :colSpan="header.colSpan"
                    scope="col"
                    class="px-4 py-3"
                >
                    <div class="flex flex-row items-center">
                        <FlexRender
                            :render="header.column.columnDef.header"
                            :props="header.getContext()"
                        />
                        <template
                            v-if="
                                !header.isPlaceholder &&
                                header.column.getCanFilter()
                            "
                        >
                            <Filter :column="header.column" :table="table" />
                        </template>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="row in table.getRowModel().rows"
                :key="row.id"
                class="border-b border-base-300"
            >
                <td
                    v-for="cell in row.getVisibleCells()"
                    :key="cell.id"
                    class="px-4 py-3"
                >
                    <FlexRender
                        :render="cell.column.columnDef.cell"
                        :props="cell.getContext()"
                    />
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script setup lang="ts">
import DebouncedInput from '@/components/DebouncedInput.vue';
import Filter from '@/components/Filter.vue';
import type { Course } from '@/types';
import {
    ColumnFiltersState,
    createColumnHelper,
    FlexRender,
    getCoreRowModel,
    getFacetedMinMaxValues,
    getFacetedRowModel,
    getFacetedUniqueValues,
    getFilteredRowModel,
    getGroupedRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { computed, h, ref } from 'vue';

interface Props {
    courses: Course[];
}

const props = defineProps<Props>();

const data = computed(() =>
    props.courses.map((course) => {
        const transformed: any = { ...course };
        course.users.forEach((user) => {
            if (user.pivot?.role) {
                const role = user.pivot.role;
                const name = `${user.first_name} ${user.last_name}`;
                transformed[role] = transformed[role]
                    ? `${transformed[role]}, ${name}`
                    : name;
            }
        });
        return transformed;
    }),
);
const allRoles = computed(() => {
    const roles = new Set<string>();
    props.courses.forEach((course) => {
        course.users.forEach((user) => {
            if (user.pivot?.role) roles.add(user.pivot.role);
        });
    });
    return Array.from(roles);
});

const columnHelper = createColumnHelper<any>();

const defaultColumns = [
    columnHelper.group({
        header: 'Course',
        footer: (props) => props.column.id,
        columns: [
            columnHelper.accessor('prefix', {
                cell: (info) => info.getValue(),
                footer: (props) => props.column.id,
            }),
            columnHelper.accessor((row) => row.number, {
                id: 'number',
                cell: (info) => info.getValue(),
                header: () => h('span', 'Number'),
                enableColumnFilter: false,
                enableGlobalFilter: false,
                footer: (props) => props.column.id,
            }),
            columnHelper.accessor('status', {
                id: 'status',
                header: () => h('span', 'Status'),
                cell: (info) => {
                    const status = info.getValue();
                    const style =
                        status === 'pending'
                            ? 'bg-info/20 border border-info shadow-md frosted-backdrop shadow-info/20 px-3 py-1 rounded-full text-center text-info  font-bold inline-block'
                            : status === 'completed'
                              ? 'bg-success px-3 py-1 froted-backdrop rounded-full text-center text-success-content inline-block'
                              : 'bg-warning/20 border frosted-backdrop border-warning shadow-md shadow-warning/20 px-3 py-1 rounded-full text-center text-warning font-bold inline-block';
                    return h(
                        'span',
                        { class: style },
                        status.charAt(0).toUpperCase() + status.slice(1),
                    );
                },
            }),
        ],
    }),

    columnHelper.group({
        header: 'Team',
        columns: allRoles.value.map((role) =>
            columnHelper.accessor(role, {
                cell: (info) => info.getValue() || '-',
                header: () => h('span', role),
                footer: (props) => props.column.id,
            }),
        ),
    }),
];

const columnFilters = ref<ColumnFiltersState>([]);
const globalFilter = ref('');
const table = useVueTable({
    data: data.value,
    columns: defaultColumns,
    state: {
        get columnFilters() {
            return columnFilters.value;
        },
        get globalFilter() {
            return globalFilter.value;
        },
    },
    onColumnFiltersChange: (updateOrValue) => {
        columnFilters.value =
            typeof updateOrValue === 'function'
                ? updateOrValue(columnFilters.value)
                : updateOrValue;
    },
    onGlobalFilterChange: (updateOrValue) => {
        globalFilter.value =
            typeof updateOrValue === 'function'
                ? updateOrValue(globalFilter.value)
                : updateOrValue;
    },
    getCoreRowModel: getCoreRowModel(),
    getGroupedRowModel: getGroupedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getFacetedRowModel: getFacetedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
    getFacetedMinMaxValues: getFacetedMinMaxValues(),
});
</script>

<style scoped></style>

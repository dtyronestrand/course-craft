<template>

            
                <table class="w-full border-collapse  bg-base-100 flex-1 overflow-y-auto rounded-lg border border-primary p-8 shadow-lg shadow-primary">
                    <thead>
                        <tr
                            v-for="headerGroup in table.getHeaderGroups()"
                            :key="headerGroup.id"
                        >
                            <th
                                v-for="header in headerGroup.headers"
                                :key="header.id"
                                :colSpan="header.colSpan"
                                :class="
                                    header.column.getCanSort()
                                        ? 'cursor-pointer select-none'
                                        : ''
                                "
                                @click="
                                    header.column.getToggleSortingHandler()?.(
                                        $event,
                                    )
                                "
                                scope="col"
                            >
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
                        <tr
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                            class="cursor-pointer hover:bg-primary/5"
                            @click="openModal(row.original)"
                        >
                            <td
                                v-for="cell in row.getVisibleCells()"
                                :key="cell.id"
                            >
                                <FlexRender
                                    :render="cell.column.columnDef.cell"
                                    :props="cell.getContext()"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
   
      
   
    <CourseDetailsModal
        v-if="isModalOpened && selectedCourse"
        :isOpen="isModalOpened"
        :course="selectedCourse"
        :developmentCycles="props.developmentCycles"
        @modal-close="closeModal"
   
    />
</template>

<script setup lang="ts">
import type { Course } from '@/types';
import {
    createColumnHelper,
    FlexRender,
    getCoreRowModel,
    getSortedRowModel,
    SortingState,
    useVueTable,
} from '@tanstack/vue-table';
import { computed, h, ref } from 'vue';
import CourseDetailsModal from './CourseDetailsModal.vue';

interface Props {
    courses: Course[];
    developmentCycles: Array<any>;
}
const props = defineProps<Props>();
const isModalOpened = ref(false);
const selectedCourse = ref<Course | null>(null);
const closeModal = () => {
    isModalOpened.value = false;
    selectedCourse.value = null;
};
const openModal = (course: Course) => {
    selectedCourse.value = course;
    isModalOpened.value = true;
};
const allRoles = computed(() => {
    const roles = new Set<string>();
    props.courses.forEach((course) => {
        course.users.forEach((user) => {
            if (user.pivot?.role) roles.add(user.pivot.role);
        });
    });
    return Array.from(roles);
});

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

const columnHelper = createColumnHelper<any>();
const columnsCourses = computed(() => [
    columnHelper.group({
        header: ' Course',
        footer: (props) => props.column.id,
        columns: [
            columnHelper.accessor('prefix', {
                header: () => h('span', 'Prefix'),
                cell: (info) => info.getValue(),
                footer: (props) => props.column.id,
            }),
            columnHelper.accessor((row) => row.number, {
                id: 'number',
                cell: (info) => info.getValue(),
                header: () => h('span', 'Number'),
                enableSorting: false,
                footer: (props) => props.column.id,
            }),
            columnHelper.accessor((row) => row.title, {
                id: 'title',
                cell: (info) => info.getValue(),
                header: () => h('span', 'Title'),
                enableSorting: false,
                footer: (props) => props.column.id,
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
]);
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
            return sorting.value;
        },
    },
    onSortingChange: (updateOrValue) => {
        sorting.value =
            typeof updateOrValue === 'function'
                ? updateOrValue(sorting.value)
                : updateOrValue;
    },
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
});
</script>

<style scoped>
table {
    border-collapse: collapse;
}
tbody {
    border-bottom: 1px solid rgba(from var(--color-primary) R G B / 0.2);
}

th,
td {
    border-bottom: 1px solid rgba(from var(--color-primary) R G B / 0.2);
    border-right: 1px solid rgba(from var(--color-primary) R G B / 0.2);
    padding: 4px 8px;
}
th:last-child,
td:last-child {
    border-right: none;
}
tr:last-child td {
    border-bottom: 1px solid rgba(from var(--color-primary) R G B / 0.5);
}

th {
    padding: 8px;
}
</style>

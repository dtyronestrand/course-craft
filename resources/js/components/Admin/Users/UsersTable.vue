<template>
    <div
        class="w-full overflow-y-auto rounded-lg border border-primary p-2 shadow-lg shadow-primary"
    >
        <div
            class="mt-8 overflow-x-auto rounded-lg border border-primary bg-base-100"
        >
            <table class="w-full">
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
        </div>

        <div class="mt-4 ml-4 flex items-center gap-2">
            <button
                class="btn rounded bg-primary p-1 text-primary-content"
                @click="() => table.setPageIndex(0)"
                :disabled="!table.getCanPreviousPage()"
            >
                «
            </button>
            <button
                class="btn rounded bg-primary p-1 text-primary-content"
                @click="() => table.previousPage()"
                :disabled="!table.getCanPreviousPage()"
            >
                ‹
            </button>
            <button
                class="btn rounded bg-primary p-1 text-primary-content"
                @click="() => table.nextPage()"
                :disabled="!table.getCanNextPage()"
            >
                ›
            </button>
            <button
                class="btn rounded bg-primary p-1 text-primary-content"
                @click="() => table.setPageIndex(table.getPageCount() - 1)"
                :disabled="!table.getCanNextPage()"
            >
                »
            </button>
            <span class="flex items-center gap-1">
                <div>Page</div>
                <strong>
                    {{ table.getState().pagination.pageIndex + 1 }} of
                    {{ table.getPageCount() }}
                </strong>
            </span>
            <span class="flex items-center gap-1">
                | Go to page:
                <input
                    type="number"
                    :value="goToPageNumber"
                    @change="handleGoToPage"
                    class="w-16 rounded border bg-base-200 p-1 text-base-content"
                />
            </span>
            <select
                :value="table.getState().pagination.pageSize"
                @change="handlePageSizeChange"
            >
                <option
                    :key="pageSize"
                    :value="pageSize"
                    v-for="pageSize in pageSizes"
                >
                    Show {{ pageSize }}
                </option>
            </select>
        </div>
        <div>{{ table.getRowModel().rows.length }} Rows</div>
    </div>
</template>

<script setup lang="ts">
import { Course } from '@/types';
import {
    createColumnHelper,
    FlexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    SortingState,
    useVueTable,
} from '@tanstack/vue-table';
import { computed, h, ref } from 'vue';
interface Props {
    users: {
        id: number;
        first_name: string;
        last_name: string;
        courses_count: number;
        courses: Course[];
    }[];
}

const props = defineProps<Props>();
const data = ref(props.users);
const columnHelper = createColumnHelper<any>();
const columns = computed(() => [
    columnHelper.accessor('first_name', {
        header: 'First Name',
        cell: (info) => h('span', info.getValue()),
        enableSorting: false,
    }),
    columnHelper.accessor('last_name', {
        header: 'Last Name',
        cell: (info) => h('span', info.getValue()),
    }),
    columnHelper.accessor('profile.title', {
        header: 'Title',
        cell: (info) => h('span', info.getValue()),
    }),
    columnHelper.accessor('courses_count', {
        header: 'Capacity',
        cell: (info) => {
            const percentage = Math.round((info.getValue() / 10) * 100);
            return h(
                'div',
                {
                    class: 'flex flex-row items-center justify-center p-4 gap-4 w-full',
                },
                [
                    h('progress', {
                        value: info.getValue() * 10,
                        max: 100,
                        class: 'progress',
                    }),
                    h('p', `${percentage}%`),
                ],
            );
        },
    }),
]);
const sorting = ref<SortingState>([]);
const INITIAL_PAGE_INDEX = 0;
const goToPageNumber = ref(INITIAL_PAGE_INDEX + 1);
const pageSizes = [10, 20, 30, 40, 50, 60];

const table = useVueTable({
    get data() {
        return data.value;
    },
    get columns() {
        return columns.value;
    },
    state: {
        get sorting() {
            return sorting.value;
        },
    },
    onSortingChange: (updaterOrValue) => {
        sorting.value =
            typeof updaterOrValue === 'function'
                ? updaterOrValue(sorting.value)
                : updaterOrValue;
    },
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
});

function handlePageSizeChange(e: Event) {
    const target = e.target as HTMLSelectElement;
    table.setPageSize(Number(target.value));
}
function handleGoToPage(e: Event) {
    const target = e.target as HTMLInputElement;
    const page = target.value ? Number(target.value) - 1 : 0;
    goToPageNumber.value = page + 1;
    table.setPageIndex(page);
}
const openModal = (user: any) => {
    // Emit an event to open the modal with user details
    // You can implement this function based on your modal logic
    console.log('Open modal for user:', user);
};
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
    border-bottom: 1px solid rgba(from var(--color-primary) R G B / 1);
    border-right: 1px solid rgba(from var(--color-primary) R G B / 1);
    padding-left: 16px;
}
th:last-child,
td:last-child {
    border-right: none;
}
th:first-child,
td:first-child {
    border-left: 1px solid rgba(from var(--color-primary) R G B / 1);
}
tr:last-child td {
    border-bottom: 1px solid rgba(from var(--color-primary) R G B / 0.5);
}

th {
    padding: 8px;
}
</style>

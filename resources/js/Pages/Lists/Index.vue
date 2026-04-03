<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    shared_lists: {
        type: Array ,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Lists" />

    <AuthenticatedLayout>
        <template #header>
            <div 
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <h2 
                    class="text-xl font-semibold leading-tight text-gray-800"
                >
                    Lists
                </h2>
                <Link :href="route('lists.create')">
                    <PrimaryButton>New List</PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <p
                            v-if="shared_lists.length === 0"
                            class="text-gray-600"
                        >
                            You have no lists yet.
                            <Link
                                :href="route('lists.create')"
                                class="font-medium text-indigo-600 underline hover:text-indigo-800"
                            >
                                Create one
                            </Link>
                            .
                        </p>
                        <ul
                            v-else
                            class="divide-y divide-gray-100"
                        >
                            <li
                                v-for="list in shared_lists"
                                :key="list.id"
                                class="flex items-center justify-between py-4 first:pt-0 last:pb-0"
                            >
                                <Link
                                    :href="route('lists.show', list.id)"
                                    class="font-medium text-gray-900 hover:text-indigo-600"
                                >
                                    {{ list.name }}
                                </Link>
                                <Link
                                    :href="route('lists.show', list.id)"
                                    class="text-sm text-gray-500 hover:text-gray-700"
                                >
                                    Open
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
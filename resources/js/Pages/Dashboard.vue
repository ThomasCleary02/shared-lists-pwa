<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    shared_lists: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <div
                            class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <p class="text-gray-900">Your lists</p>
                            <Link :href="route('lists.create')">
                                <PrimaryButton>New list</PrimaryButton>
                            </Link>
                        </div>

                        <p
                            v-if="shared_lists.length === 0"
                            class="text-gray-600"
                        >
                            No lists yet. Create one to get started.
                        </p>

                        <ul
                            v-else
                            class="divide-y divide-gray-100"
                        >
                            <li
                                v-for="list in shared_lists"
                                :key="list.id"
                                class="py-3 first:pt-0 last:pb-0"
                            >
                                <Link
                                    :href="route('lists.show', list.id)"
                                    class="font-medium text-indigo-600 hover:text-indigo-800"
                                >
                                    {{ list.name }}
                                </Link>
                            </li>
                        </ul>

                        <p class="mt-6 text-sm text-gray-500">
                            <Link
                                :href="route('lists.index')"
                                class="text-indigo-600 hover:text-indigo-800"
                            >
                                View all lists
                            </Link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-dvh flex-col items-center bg-gray-100 px-4 pb-[max(1.5rem,env(safe-area-inset-bottom))] pt-[max(1.5rem,env(safe-area-inset-top))] sm:justify-center sm:px-6 sm:pb-6 sm:pt-0"
    >
        <div>
            <Link
                href="/"
                class="inline-flex rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
            >
                <ApplicationLogo class="h-20 w-20 text-indigo-600" />
            </Link>
        </div>

        <div
            class="mt-6 w-full max-w-md overflow-hidden bg-white px-4 py-6 shadow-md sm:rounded-lg sm:px-6"
        >
            <slot />
        </div>
    </div>
</template>

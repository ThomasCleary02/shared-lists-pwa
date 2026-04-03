<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Shared Lists" />

    <div
        class="min-h-dvh bg-gradient-to-b from-gray-50 to-gray-100 text-gray-900"
    >
        <header
            class="border-b border-gray-200/80 bg-white/90 backdrop-blur supports-[backdrop-filter]:bg-white/70"
        >
            <div
                class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-4 sm:px-6"
            >
                <Link
                    href="/"
                    class="flex items-center gap-2.5 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                >
                    <ApplicationLogo class="h-9 w-9 shrink-0 text-indigo-600" />
                    <span class="text-lg font-semibold tracking-tight"
                        >Shared Lists</span
                    >
                </Link>

                <nav
                    v-if="canLogin"
                    class="flex flex-wrap items-center justify-end gap-2 sm:gap-3"
                >
                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="route('dashboard')"
                            class="inline-flex min-h-11 min-w-[2.75rem] items-center justify-center rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 active:scale-[0.98]"
                        >
                            Open app
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="inline-flex min-h-11 items-center justify-center rounded-lg px-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="inline-flex min-h-11 items-center justify-center rounded-lg bg-indigo-600 px-4 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 active:scale-[0.98]"
                        >
                            Get started
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <main
            class="mx-auto max-w-5xl px-4 pb-[max(2rem,env(safe-area-inset-bottom))] pt-10 sm:px-6 sm:pt-16"
        >
            <div class="mx-auto max-w-2xl text-center sm:max-w-3xl">
                <p
                    class="text-sm font-medium uppercase tracking-wide text-indigo-600"
                >
                    Collaborative checklists
                </p>
                <h1
                    class="mt-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-tight"
                >
                    Lists you create, share, and check off together
                </h1>
                <p
                    class="mt-4 text-base leading-relaxed text-gray-600 sm:text-lg"
                >
                    Built for phones first. Add the app to your home screen for
                    a focused, app-like experience when you are on the go.
                </p>

                <div
                    class="mt-8 flex flex-col items-stretch gap-3 sm:flex-row sm:justify-center sm:gap-4"
                >
                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="route('lists.index')"
                            class="inline-flex min-h-12 items-center justify-center rounded-xl bg-indigo-600 px-6 text-base font-semibold text-white shadow-md transition hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 active:scale-[0.99]"
                        >
                            My lists
                        </Link>
                        <Link
                            :href="route('dashboard')"
                            class="inline-flex min-h-12 items-center justify-center rounded-xl px-6 text-base font-semibold text-indigo-700 ring-2 ring-indigo-200 transition hover:bg-indigo-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                        >
                            Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="inline-flex min-h-12 items-center justify-center rounded-xl bg-indigo-600 px-6 text-base font-semibold text-white shadow-md transition hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 active:scale-[0.99]"
                        >
                            Create a free account
                        </Link>
                        <Link
                            :href="route('login')"
                            class="inline-flex min-h-12 items-center justify-center rounded-xl px-6 text-base font-semibold text-gray-800 ring-2 ring-gray-200 transition hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                        >
                            I already have an account
                        </Link>
                    </template>
                </div>
            </div>

            <ul
                class="mx-auto mt-14 grid max-w-3xl gap-4 sm:mt-20 sm:grid-cols-3"
                role="list"
            >
                <li
                    class="rounded-2xl border border-gray-200 bg-white p-5 text-left shadow-sm"
                >
                    <h2 class="font-semibold text-gray-900">Own your lists</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">
                        Create lists with a clear name and keep them organized
                        in one place.
                    </p>
                </li>
                <li
                    class="rounded-2xl border border-gray-200 bg-white p-5 text-left shadow-sm"
                >
                    <h2 class="font-semibold text-gray-900">Check things off</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">
                        Add items, mark them complete, and remove what you no
                        longer need.
                    </p>
                </li>
                <li
                    class="rounded-2xl border border-gray-200 bg-white p-5 text-left shadow-sm sm:col-span-1"
                >
                    <h2 class="font-semibold text-gray-900">Share (soon)</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">
                        Inviting collaborators is next on the roadmap—your UI
                        is already structured for it.
                    </p>
                </li>
            </ul>
        </main>

        <footer
            class="mt-auto border-t border-gray-200/80 py-8 text-center text-sm text-gray-500"
        >
            <p>Shared Lists — install from the browser for a home screen app.</p>
        </footer>
    </div>
</template>

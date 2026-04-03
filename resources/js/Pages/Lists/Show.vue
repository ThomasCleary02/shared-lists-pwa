<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    shared_list: {
        type: Object,
        required: true,
    },
});

const items = computed(() => props.shared_list.items ?? []);

const itemForm = useForm({
    content: '',
});

const submitItem = () => {
    itemForm.post(route('list_items.store', props.shared_list.id), {
        preserveScroll: true,
        onSuccess: () => itemForm.reset('content'),
    });
};

const deleteList = () => {
    if (
        confirm(
            `Delete "${props.shared_list.name}"? This cannot be undone.`,
        )
    ) {
        router.delete(route('lists.destroy', props.shared_list.id));
    }
};

const toggleComplete = (item, checked) => {
    router.patch(
        route('list_items.update', item.id),
        {
            content: item.content,
            is_complete: checked,
        },
        { preserveScroll: true },
    );
};

const deleteItem = (item) => {
    if (confirm('Remove this item?')) {
        router.delete(route('list_items.destroy', item.id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head :title="shared_list.name" />

    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800"
                >
                    {{ shared_list.name }}
                </h2>
                <div class="flex flex-wrap items-center gap-2">
                    <Link :href="route('lists.index')">
                        <SecondaryButton type="button">
                            Back to lists
                        </SecondaryButton>
                    </Link>
                    <DangerButton type="button" @click="deleteList">
                        Delete list
                    </DangerButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="border-b border-gray-100 p-6">
                        <form @submit.prevent="submitItem">
                            <InputLabel for="content" value="New item" />

                            <TextInput
                                id="content"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="itemForm.content"
                                placeholder="What needs to be done?"
                                autocomplete="off"
                            />

                            <InputError
                                class="mt-2"
                                :message="itemForm.errors.content"
                            />

                            <div class="mt-4">
                                <PrimaryButton
                                    :class="{ 'opacity-25': itemForm.processing }"
                                    :disabled="itemForm.processing"
                                >
                                    Add item
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <ul
                        v-if="items.length"
                        class="divide-y divide-gray-100"
                        role="list"
                    >
                        <li
                            v-for="item in items"
                            :key="item.id"
                            class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <div
                                class="flex min-w-0 flex-1 items-start gap-3"
                            >
                                <Checkbox
                                    :checked="Boolean(item.is_complete)"
                                    @update:checked="
                                        (v) => toggleComplete(item, v)
                                    "
                                />
                                <span
                                    class="text-gray-800"
                                    :class="{
                                        'text-gray-400 line-through':
                                            item.is_complete,
                                    }"
                                >
                                    {{ item.content }}
                                </span>
                            </div>
                            <DangerButton
                                type="button"
                                class="shrink-0 self-start sm:self-center"
                                @click="deleteItem(item)"
                            >
                                Remove
                            </DangerButton>
                        </li>
                    </ul>

                    <p
                        v-else
                        class="p-6 text-gray-500"
                    >
                        No items yet. Add one above.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
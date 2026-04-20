<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    create as createFolder,
    index as foldersIndex,
    store as storeFolder,
} from '@/routes/gallery/folders';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Gallery',
                href: foldersIndex(),
            },
            {
                title: 'Create Folder',
                href: createFolder(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Create Folder" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Create Folder</h2>

            <Link
                :href="foldersIndex.url()"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
                Back
            </Link>
        </div>

        <div
            class="max-w-2xl rounded-xl border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border"
        >
            <Form
                v-bind="storeFolder.form()"
                class="space-y-4"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="title">Folder Title</Label>
                    <Input
                        id="title"
                        name="title"
                        type="text"
                        required
                        placeholder="My Gallery Folder"
                    />
                    <InputError :message="errors.title" />
                </div>

                <div class="grid gap-2">
                    <Label for="description">Description</Label>
                    <Input
                        id="description"
                        name="description"
                        type="text"
                        placeholder="Optional description..."
                    />
                    <InputError :message="errors.description" />
                </div>

                <Button type="submit" :disabled="processing"
                    >Create Folder</Button
                >
            </Form>
        </div>
    </div>
</template>

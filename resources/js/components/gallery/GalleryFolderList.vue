<script setup lang="ts">
import { Form, Link } from '@inertiajs/vue3';
import { FolderOpen, Images } from 'lucide-vue-next';
import {
    create as createFolder,
    destroy as destroyFolder,
    show as showFolder,
} from '@/routes/gallery/folders';
import type { GalleryFolderSummary } from '@/types';

defineProps<{
    folders: GalleryFolderSummary[];
    canCreate: boolean;
    canEdit: boolean;
    canDelete: boolean;
}>();

const confirmDeleteFolder = (event: Event): void => {
    if (!window.confirm('Delete this folder and all photos inside it?')) {
        event.preventDefault();
    }
};
</script>

<template>
    <div
        v-if="folders.length > 0"
        class="grid gap-4 md:grid-cols-2 xl:grid-cols-3"
    >
        <article
            v-for="folder in folders"
            :key="folder.id"
            class="overflow-hidden rounded-lg border border-sidebar-border/70 bg-background dark:border-sidebar-border"
        >
            <Link :href="showFolder.url(folder.id)" class="block">
                <div class="aspect-[4/3] bg-muted">
                    <img
                        v-if="folder.cover_image_url"
                        :src="folder.cover_image_url"
                        :alt="folder.title"
                        class="h-full w-full object-cover"
                    />

                    <div
                        v-else
                        class="flex h-full w-full items-center justify-center bg-muted text-muted-foreground"
                    >
                        <FolderOpen class="h-12 w-12" />
                    </div>
                </div>
            </Link>

            <div class="space-y-4 p-4">
                <div class="space-y-1">
                    <div class="flex items-start justify-between gap-3">
                        <Link
                            :href="showFolder.url(folder.id)"
                            class="text-base font-semibold hover:underline"
                        >
                            {{ folder.title }}
                        </Link>

                        <span
                            class="inline-flex items-center gap-1 rounded-md border px-2 py-1 text-xs text-muted-foreground"
                        >
                            <Images class="h-3.5 w-3.5" />
                            {{ folder.images_count }}
                        </span>
                    </div>

                    <p class="line-clamp-2 text-sm text-muted-foreground">
                        {{ folder.description || 'No description added yet.' }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <Link
                        :href="showFolder.url(folder.id)"
                        class="rounded-md bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700"
                    >
                        {{ canEdit ? 'View/Edit' : 'View' }}
                    </Link>

                    <Form
                        v-if="canDelete"
                        v-bind="destroyFolder.form.delete(folder.id)"
                        v-slot="{ processing }"
                    >
                        <button
                            type="submit"
                            class="rounded-md border border-red-300 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 disabled:opacity-50 dark:hover:bg-red-950/30"
                            :disabled="processing"
                            @click="confirmDeleteFolder"
                        >
                            Delete
                        </button>
                    </Form>
                </div>
            </div>
        </article>
    </div>

    <div
        v-else
        class="flex min-h-72 flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-sidebar-border/70 bg-background p-8 text-center dark:border-sidebar-border"
    >
        <FolderOpen class="h-12 w-12 text-muted-foreground" />
        <div class="space-y-1">
            <h3 class="text-base font-semibold">No gallery folders yet</h3>
            <p class="max-w-md text-sm text-muted-foreground">
                Create a folder first, then open it to upload multiple photos.
            </p>
        </div>

        <Link
            v-if="canCreate"
            :href="createFolder.url()"
            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
        >
            Create Folder
        </Link>
    </div>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GalleryFolderList from '@/components/gallery/GalleryFolderList.vue';
import { can } from '@/lib/can';
import {
    create as createFolder,
    index as foldersIndex,
} from '@/routes/gallery/folders';
import type { GalleryFolderSummary } from '@/types';

defineProps<{
    folders: GalleryFolderSummary[];
}>();

const canCreateFolder = can('gallery.folders.create');
const canEditFolder = can('gallery.folders.edit');
const canDeleteFolder = can('gallery.folders.delete');

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Gallery',
                href: foldersIndex(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Gallery" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="space-y-1">
                <h2 class="text-lg font-semibold">Gallery Folders</h2>
                <p class="text-sm text-muted-foreground">
                    {{
                        canEditFolder
                            ? 'Open a folder to view, edit, and upload photos.'
                            : 'Open a folder to view photos.'
                    }}
                </p>
            </div>

            <Link
                v-if="canCreateFolder"
                :href="createFolder.url()"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
                Create Folder
            </Link>
        </div>

        <GalleryFolderList
            :folders="folders"
            :can-create="canCreateFolder"
            :can-edit="canEditFolder"
            :can-delete="canDeleteFolder"
        />
    </div>
</template>

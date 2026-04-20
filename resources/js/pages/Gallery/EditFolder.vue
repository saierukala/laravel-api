<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GalleryFolderDetailsForm from '@/components/gallery/GalleryFolderDetailsForm.vue';
import GalleryImageGrid from '@/components/gallery/GalleryImageGrid.vue';
import GalleryImageUpload from '@/components/gallery/GalleryImageUpload.vue';
import { can } from '@/lib/can';
import { index as foldersIndex } from '@/routes/gallery/folders';
import type { GalleryFolderDetail } from '@/types';

const props = defineProps<{
    folder: GalleryFolderDetail;
}>();

const canEditFolder = can('gallery.folders.edit');
const canUploadImages = canEditFolder && can('gallery.images.create');
const canDeleteImages = canEditFolder && can('gallery.images.delete');

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Gallery', href: foldersIndex() },
            { title: 'Folder', href: foldersIndex() },
        ],
    },
});
</script>

<template>
    <Head :title="props.folder.title" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="space-y-1">
                <h2 class="text-lg font-semibold">{{ props.folder.title }}</h2>
                <p class="text-sm text-muted-foreground">
                    {{ props.folder.images.length }} photo{{
                        props.folder.images.length === 1 ? '' : 's'
                    }}
                    in this folder.
                </p>
            </div>

            <Link
                :href="foldersIndex.url()"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
                Back
            </Link>
        </div>

        <GalleryFolderDetailsForm
            v-if="canEditFolder"
            :folder="props.folder"
            :can-edit="canEditFolder"
        />

        <GalleryImageUpload
            v-if="canUploadImages"
            :folder-id="props.folder.id"
        />

        <GalleryImageGrid
            :folder-id="props.folder.id"
            :images="props.folder.images"
            :can-delete="canDeleteImages"
        />
    </div>
</template>

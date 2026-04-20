<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Expand, ImageOff } from 'lucide-vue-next';
import { shallowRef } from 'vue';
import GalleryPhotoBrowser from '@/components/gallery/GalleryPhotoBrowser.vue';
import { destroy as destroyImage } from '@/routes/gallery/images';
import type { GalleryImage } from '@/types';

const props = defineProps<{
    folderId: number;
    images: GalleryImage[];
    canDelete: boolean;
}>();

const isBrowserOpen = shallowRef(false);
const selectedImageIndex = shallowRef(0);

const confirmDeleteImage = (event: Event): void => {
    if (!window.confirm('Delete this photo from the folder?')) {
        event.preventDefault();
    }
};

const formatFileSize = (bytes: number | null): string => {
    if (!bytes) {
        return 'Unknown';
    }

    if (bytes < 1024) {
        return `${bytes} B`;
    }

    if (bytes < 1024 * 1024) {
        return `${(bytes / 1024).toFixed(1)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
};

const openBrowser = (index: number): void => {
    selectedImageIndex.value = index;
    isBrowserOpen.value = true;
};
</script>

<template>
    <section
        class="rounded-lg border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border"
    >
        <div class="mb-4 flex items-center justify-between gap-3">
            <div class="space-y-1">
                <h3 class="text-lg font-semibold">Photos</h3>
                <p class="text-sm text-muted-foreground">
                    {{ images.length }} photo{{
                        images.length === 1 ? '' : 's'
                    }}
                    in this folder.
                </p>
            </div>
        </div>

        <div
            v-if="images.length > 0"
            class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
        >
            <article
                v-for="(image, index) in images"
                :key="image.id"
                class="group overflow-hidden rounded-lg border border-sidebar-border/70 bg-muted dark:border-sidebar-border"
            >
                <button
                    type="button"
                    class="group/photo relative aspect-square w-full overflow-hidden text-left"
                    :aria-label="`Open ${image.original_name}`"
                    @click="openBrowser(index)"
                >
                    <img
                        :src="image.url"
                        :alt="image.original_name"
                        class="h-full w-full object-cover transition duration-200 group-hover:scale-105"
                    />

                    <span
                        class="absolute inset-x-0 bottom-0 flex items-center justify-between gap-3 bg-gradient-to-t from-black/75 to-transparent p-3 text-xs font-medium text-white opacity-0 transition group-hover/photo:opacity-100 group-focus-visible/photo:opacity-100"
                    >
                        <span class="truncate">View photo</span>
                        <Expand class="h-4 w-4 shrink-0" />
                    </span>
                </button>

                <div class="space-y-3 bg-background p-3">
                    <div class="space-y-1">
                        <p class="truncate text-sm font-medium">
                            {{ image.original_name }}
                        </p>
                        <p class="text-xs text-muted-foreground">
                            {{ formatFileSize(image.size) }}
                            <span v-if="image.width && image.height">
                                / {{ image.width }} x {{ image.height }}
                            </span>
                        </p>
                    </div>

                    <Form
                        v-if="canDelete"
                        v-bind="
                            destroyImage.form.delete({
                                folder: folderId,
                                image: image.id,
                            })
                        "
                        v-slot="{ processing }"
                    >
                        <button
                            type="submit"
                            class="w-full rounded-md border border-red-300 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 disabled:opacity-50 dark:hover:bg-red-950/30"
                            :disabled="processing"
                            @click="confirmDeleteImage"
                        >
                            Delete
                        </button>
                    </Form>
                </div>
            </article>
        </div>

        <div
            v-else
            class="flex min-h-48 flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-sidebar-border/70 bg-muted/20 p-8 text-center dark:border-sidebar-border"
        >
            <ImageOff class="h-10 w-10 text-muted-foreground" />
            <div class="space-y-1">
                <h4 class="text-sm font-semibold">No photos yet</h4>
                <p class="text-sm text-muted-foreground">
                    Upload multiple photos above and they will appear here.
                </p>
            </div>
        </div>

        <GalleryPhotoBrowser
            v-model:open="isBrowserOpen"
            v-model:selected-index="selectedImageIndex"
            :images="props.images"
        />
    </section>
</template>

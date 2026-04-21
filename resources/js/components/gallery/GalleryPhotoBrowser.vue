<script setup lang="ts">
import { useEventListener } from '@vueuse/core';
import { ChevronLeft, ChevronRight, X } from 'lucide-vue-next';
import { computed } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
} from '@/components/ui/dialog';
import type { GalleryImage } from '@/types';

const props = defineProps<{
    images: GalleryImage[];
}>();

const isOpen = defineModel<boolean>('open', { required: true });
const selectedIndex = defineModel<number>('selectedIndex', { required: true });

const activeImage = computed(() => props.images[selectedIndex.value] ?? null);
const imageCount = computed(() => props.images.length);
const hasMultipleImages = computed(() => imageCount.value > 1);
const positionLabel = computed(() =>
    activeImage.value
        ? `${selectedIndex.value + 1} of ${imageCount.value}`
        : '',
);

const goToImage = (index: number): void => {
    if (imageCount.value === 0) {
        return;
    }

    selectedIndex.value = (index + imageCount.value) % imageCount.value;
};

const showPrevious = (): void => {
    goToImage(selectedIndex.value - 1);
};

const showNext = (): void => {
    goToImage(selectedIndex.value + 1);
};

const closeBrowser = (): void => {
    isOpen.value = false;
};

const formatFileSize = (bytes: number | null): string => {
    if (!bytes) {
        return 'Unknown size';
    }

    if (bytes < 1024) {
        return `${bytes} B`;
    }

    if (bytes < 1024 * 1024) {
        return `${(bytes / 1024).toFixed(1)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
};

useEventListener(window, 'keydown', (event: KeyboardEvent) => {
    if (!isOpen.value || !hasMultipleImages.value) {
        return;
    }

    if (event.key === 'ArrowLeft') {
        event.preventDefault();
        showPrevious();
    }

    if (event.key === 'ArrowRight') {
        event.preventDefault();
        showNext();
    }
});
</script>

<template>
    <Dialog :open="isOpen" @update:open="isOpen = $event">
        <DialogContent
            class="max-h-[calc(100dvh-0.5rem)] overflow-hidden border-zinc-800 bg-zinc-950 p-0 text-white shadow-2xl sm:max-h-[calc(100vh-2rem)] sm:max-w-[calc(100vw-2rem)]"
            :show-close-button="false"
        >
            <DialogTitle class="sr-only">Photo Browser</DialogTitle>
            <DialogDescription class="sr-only">
                Browse the photos in this gallery folder.
            </DialogDescription>

            <div
                v-if="activeImage"
                class="grid max-h-[calc(100dvh-0.5rem)] min-h-0 grid-rows-[auto_minmax(0,1fr)_auto] overflow-hidden sm:max-h-[calc(100vh-2rem)]"
            >
                <header
                    class="flex items-start justify-between gap-2 border-b border-white/10 px-2.5 py-2 sm:px-4 sm:py-3"
                >
                    <div class="min-w-0 flex-1 pr-2">
                        <p
                            class="truncate text-[11px] font-medium tracking-[0.01em] text-zinc-200 sm:text-sm"
                        >
                            {{ activeImage.original_name }}
                        </p>
                        <p
                            class="mt-0.5 truncate text-[9px] leading-3.5 text-zinc-500 sm:text-xs sm:leading-4"
                        >
                            {{ positionLabel }} /
                            {{ formatFileSize(activeImage.size) }}
                            <span
                                v-if="activeImage.width && activeImage.height"
                                class="hidden sm:inline"
                            >
                                / {{ activeImage.width }} x
                                {{ activeImage.height }}
                            </span>
                        </p>
                    </div>

                    <button
                        type="button"
                        class="rounded-md border border-white/15 bg-white/10 p-1.5 text-white transition hover:bg-white/20 sm:p-2"
                        aria-label="Close photo browser"
                        @click="closeBrowser"
                    >
                        <X class="h-4 w-4 sm:h-5 sm:w-5" />
                    </button>
                </header>

                <div
                    class="relative flex min-h-0 items-center justify-center bg-black px-1.5 py-1.5 sm:px-4 sm:py-4 lg:px-6 lg:py-5"
                >
                    <button
                        v-if="hasMultipleImages"
                        type="button"
                        class="absolute top-1/2 left-1.5 z-10 -translate-y-1/2 rounded-md border border-white/15 bg-black/60 p-1.5 text-white shadow-lg transition hover:bg-white/15 sm:left-3 sm:p-2.5 lg:left-4"
                        aria-label="Previous photo"
                        @click="showPrevious"
                    >
                        <ChevronLeft class="h-4 w-4 sm:h-6 sm:w-6" />
                    </button>

                    <img
                        :key="activeImage.id"
                        :src="activeImage.url"
                        :alt="activeImage.original_name"
                        class="h-full max-h-full w-full max-w-full object-contain"
                    />

                    <button
                        v-if="hasMultipleImages"
                        type="button"
                        class="absolute top-1/2 right-1.5 z-10 -translate-y-1/2 rounded-md border border-white/15 bg-black/60 p-1.5 text-white shadow-lg transition hover:bg-white/15 sm:right-3 sm:p-2.5 lg:right-4"
                        aria-label="Next photo"
                        @click="showNext"
                    >
                        <ChevronRight class="h-4 w-4 sm:h-6 sm:w-6" />
                    </button>
                </div>

                <footer
                    v-if="hasMultipleImages"
                    class="flex gap-1.5 overflow-x-auto border-t border-white/10 bg-zinc-950 px-2.5 py-2 sm:gap-2 sm:px-4 sm:py-3"
                >
                    <button
                        v-for="(image, index) in images"
                        :key="image.id"
                        type="button"
                        class="h-10 w-10 shrink-0 overflow-hidden rounded-md border transition sm:h-14 sm:w-14 lg:h-16 lg:w-16"
                        :class="
                            index === selectedIndex
                                ? 'border-white'
                                : 'border-white/10 opacity-60 hover:opacity-100'
                        "
                        :aria-label="`Open ${image.original_name}`"
                        @click="goToImage(index)"
                    >
                        <img
                            :src="image.url"
                            :alt="image.original_name"
                            class="h-full w-full object-cover"
                        />
                    </button>
                </footer>
            </div>
        </DialogContent>
    </Dialog>
</template>

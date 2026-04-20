<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Upload } from 'lucide-vue-next';
import { computed, shallowRef, useTemplateRef } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { upload as uploadImages } from '@/routes/gallery/images';

const props = defineProps<{
    folderId: number;
}>();

const fileInput = useTemplateRef<HTMLInputElement>('fileInput');
const selectedFiles = shallowRef<File[]>([]);
const form = useForm<{
    images: File[];
}>({
    images: [],
});

const selectedFileLabel = computed(() => {
    if (selectedFiles.value.length === 0) {
        return 'Choose photos to upload.';
    }

    return `${selectedFiles.value.length} photo${selectedFiles.value.length === 1 ? '' : 's'} selected`;
});

const imageError = computed(() => {
    const fileError = Object.entries(form.errors).find(([key]) =>
        key.startsWith('images.'),
    );

    return form.errors.images ?? fileError?.[1];
});

const handleFileChange = (event: Event): void => {
    const input = event.target as HTMLInputElement;
    const files = Array.from(input.files ?? []);

    selectedFiles.value = files;
    form.images = files;
    form.clearErrors();
};

const submit = (): void => {
    form.post(uploadImages.url(props.folderId), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            selectedFiles.value = [];

            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
    });
};
</script>

<template>
    <section
        class="rounded-lg border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border"
    >
        <div class="mb-4 space-y-1">
            <h3 class="text-lg font-semibold">Upload Photos</h3>
            <p class="text-sm text-muted-foreground">
                Select several image files and upload them to this folder
                together.
            </p>
        </div>

        <form class="space-y-4" @submit.prevent="submit">
            <label
                for="gallery-images"
                class="flex cursor-pointer flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-sidebar-border/70 bg-muted/30 p-6 text-center transition hover:bg-muted/50 dark:border-sidebar-border"
            >
                <Upload class="h-9 w-9 text-muted-foreground" />
                <div class="space-y-1">
                    <p class="text-sm font-medium">{{ selectedFileLabel }}</p>
                    <p class="text-xs text-muted-foreground">
                        JPG, PNG, GIF, or WebP. Up to 10 MB each.
                    </p>
                </div>
                <input
                    id="gallery-images"
                    ref="fileInput"
                    type="file"
                    multiple
                    accept="image/*"
                    class="sr-only"
                    @change="handleFileChange"
                />
            </label>

            <ul v-if="selectedFiles.length > 0" class="grid gap-2 text-sm">
                <li
                    v-for="file in selectedFiles"
                    :key="`${file.name}-${file.lastModified}`"
                    class="flex items-center justify-between gap-3 rounded-md border px-3 py-2"
                >
                    <span class="truncate">{{ file.name }}</span>
                    <span class="shrink-0 text-xs text-muted-foreground">
                        {{ (file.size / 1024).toFixed(1) }} KB
                    </span>
                </li>
            </ul>

            <InputError :message="imageError" />

            <Button
                type="submit"
                :disabled="form.processing || selectedFiles.length === 0"
            >
                Upload Photos
            </Button>
        </form>
    </section>
</template>

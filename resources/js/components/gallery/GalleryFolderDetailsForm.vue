<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { update as updateFolder } from '@/routes/gallery/folders';
import type { GalleryFolderDetail } from '@/types';

defineProps<{
    folder: GalleryFolderDetail;
    canEdit: boolean;
}>();
</script>

<template>
    <section
        class="rounded-lg border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border"
    >
        <div class="mb-4 space-y-1">
            <h3 class="text-lg font-semibold">Folder Details</h3>
            <p class="text-sm text-muted-foreground">
                Keep the folder name clear before adding more photos.
            </p>
        </div>

        <Form
            v-if="canEdit"
            v-bind="updateFolder.form.put(folder.id)"
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
                    :default-value="folder.title"
                />
                <InputError :message="errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="description">Description</Label>
                <Input
                    id="description"
                    name="description"
                    type="text"
                    :default-value="folder.description || ''"
                />
                <InputError :message="errors.description" />
            </div>

            <Button type="submit" :disabled="processing">Save Changes</Button>
        </Form>

        <dl v-else class="grid gap-4 text-sm sm:grid-cols-2">
            <div class="space-y-1">
                <dt class="font-medium">Folder Title</dt>
                <dd class="text-muted-foreground">{{ folder.title }}</dd>
            </div>

            <div class="space-y-1">
                <dt class="font-medium">Description</dt>
                <dd class="text-muted-foreground">
                    {{ folder.description || 'No description added yet.' }}
                </dd>
            </div>
        </dl>
    </section>
</template>

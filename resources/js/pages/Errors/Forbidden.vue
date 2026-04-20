<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard } from '@/routes';

defineProps<{
    message: string;
}>();

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Head title="Forbidden" />

    <div class="flex h-full flex-1 items-center justify-center p-6">
        <div
            class="w-full max-w-2xl rounded-2xl border border-sidebar-border/70 bg-background p-8 text-center shadow-sm dark:border-sidebar-border"
        >
            <div
                class="mx-auto mb-4 w-fit rounded-full bg-destructive/10 px-4 py-2 text-sm font-semibold text-destructive"
            >
                403 Forbidden
            </div>

            <h1 class="text-3xl font-semibold tracking-tight">Access denied</h1>

            <p class="mt-3 text-sm leading-6 text-muted-foreground">
                {{ message }}
            </p>

            <div class="mt-6 flex items-center justify-center gap-3">
                <Link
                    :href="dashboard()"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Go to Dashboard
                </Link>

                <button
                    type="button"
                    class="rounded-md border px-4 py-2 text-sm font-medium"
                    @click="goBack"
                >
                    Go Back
                </button>
            </div>
        </div>
    </div>
</template>

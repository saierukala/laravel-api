<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { can } from '@/lib/can';
import { index as rolesIndex, update as rolesUpdate } from '@/routes/roles';

defineProps<{
    role: {
        id: number;
        name: string;
        permissions: string[];
    };
    availablePermissions: string[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Roles',
                href: rolesIndex(),
            },
            {
                title: 'Edit Role',
                href: rolesIndex(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Edit Role" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Edit Role</h2>

            <Link
                v-if="can('roles.view')"
                :href="rolesIndex()"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
                Back
            </Link>
        </div>

        <div
            class="max-w-3xl rounded-xl border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border"
        >
            <Form
                v-bind="rolesUpdate.form(role.id)"
                class="space-y-6"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Role Name</Label>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        required
                        :default-value="role.name"
                        placeholder="Manager"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-3">
                    <div class="grid gap-1">
                        <Label>Permissions</Label>
                        <p class="text-sm text-muted-foreground">
                            Update the permissions assigned to this role.
                        </p>
                    </div>

                    <div
                        v-if="availablePermissions.length > 0"
                        class="grid gap-3 rounded-lg border border-sidebar-border/70 p-4 sm:grid-cols-2 dark:border-sidebar-border"
                    >
                        <label
                            v-for="permission in availablePermissions"
                            :key="permission"
                            :for="`permission-${permission}`"
                            class="flex items-start gap-3 rounded-md border border-transparent p-2 hover:bg-muted/40"
                        >
                            <input
                                :id="`permission-${permission}`"
                                name="permissions[]"
                                type="checkbox"
                                :value="permission"
                                :checked="role.permissions.includes(permission)"
                                class="mt-0.5 h-4 w-4 rounded border-input text-primary"
                            />
                            <span class="text-sm">{{ permission }}</span>
                        </label>
                    </div>

                    <p v-else class="text-sm text-muted-foreground">
                        No permissions are available yet.
                    </p>

                    <InputError :message="errors.permissions" />
                </div>

                <Button type="submit" :disabled="processing"
                    >Save Changes</Button
                >
            </Form>
        </div>
    </div>
</template>

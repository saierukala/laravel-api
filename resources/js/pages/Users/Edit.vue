<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { can } from '@/lib/can';
import { index as usersIndex, update as usersUpdate } from '@/routes/users';

type Props = {
    user: {
        id: number;
        name: string;
        email: string;
        roles: string[];
    };
    availableRoles: string[];
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Users',
                href: usersIndex(),
            },
            {
                title: 'Edit User',
                href: usersIndex(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Edit User" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Edit User</h2>

            <Link
                v-if="can('users.view')"
                :href="usersIndex()"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
                Back
            </Link>
        </div>

        <div
            class="max-w-3xl rounded-xl border border-sidebar-border/70 bg-background p-4 dark:border-sidebar-border"
        >
            <Form
                v-bind="usersUpdate.form(user.id)"
                :reset-on-success="['password', 'password_confirmation']"
                class="space-y-4"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        required
                        :default-value="user.name"
                        placeholder="Full name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        name="email"
                        type="email"
                        required
                        :default-value="user.email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">New Password</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Leave blank to keep current password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation"
                        >Confirm New Password</Label
                    >
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Confirm new password"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <div class="grid gap-3">
                    <div class="grid gap-1">
                        <Label>Roles</Label>
                        <p class="text-sm text-muted-foreground">
                            Update the roles assigned to this user.
                        </p>
                    </div>

                    <div
                        v-if="availableRoles.length > 0"
                        class="grid gap-3 rounded-lg border border-sidebar-border/70 p-4 sm:grid-cols-2 dark:border-sidebar-border"
                    >
                        <label
                            v-for="role in availableRoles"
                            :key="role"
                            :for="`role-${role}`"
                            class="flex items-start gap-3 rounded-md border border-transparent p-2 hover:bg-muted/40"
                        >
                            <input
                                :id="`role-${role}`"
                                name="roles[]"
                                type="checkbox"
                                :value="role"
                                :checked="user.roles.includes(role)"
                                class="mt-0.5 h-4 w-4 rounded border-input text-primary"
                            />
                            <span class="text-sm">{{ role }}</span>
                        </label>
                    </div>

                    <p v-else class="text-sm text-muted-foreground">
                        No roles are available yet.
                    </p>

                    <InputError :message="errors.roles" />
                </div>

                <Button type="submit" :disabled="processing"
                    >Save Changes</Button
                >
            </Form>
        </div>
    </div>
</template>
